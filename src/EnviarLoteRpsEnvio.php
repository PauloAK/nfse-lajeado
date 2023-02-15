<?php

namespace PauloAK\NfseLajeado;

use DOMDocument;
use Exception;
use PauloAK\NfseLajeado\Common\Rps;
use PauloAK\NfseLajeado\Helpers\Response;
use PauloAK\NfseLajeado\Helpers\Signer;
use PauloAK\NfseLajeado\Helpers\Utils;
use Spatie\ArrayToXml\ArrayToXml;

class EnviarLoteRpsEnvio
{
    private ?string $numeroLote = null;
    private ?string $cnpj = null;
    private ?string $im = null;
    private ?Rps $rps = null;

    private ?string $certPath;
    private ?string $certPass;

    /**
     * Constructor
     * 
     * @param string $certPath Path to the certificate .PFX file
     * @param string $certPass Certificate password
     */
    function __construct(string $certPath, string $certPass)
    {
        $this->certPath = $certPath;
        $this->certPass = $certPass;
    }

    /**
     * Sets Numero do Lote
     */
    public function numeroLote($numeroLote)
    {
        $this->numeroLote = $numeroLote;
        return $this;
    }

    /**
     * Sets CNPJ
     */
    public function cnpj($cnpj)
    {
        $this->cnpj = Utils::onlyNumbers($cnpj);
        return $this;
    }

    /**
     * Sets Inscrição Municipal
     */
    public function im($im)
    {
        $this->im = $im;
        return $this;
    }

    /**
     * Sets RPS
     * 
     * @param Rps $rps
     */
    public function rps(Rps $rps)
    {
        $this->rps = $rps;
        return $this;
    }

    /**
     * Sends the RPS to the Lajeado's NFSe homologation server
     * 
     * @return Response
     */
    public function sendHml(): Response
    {
        return $this->send(true);
    }

    /**
     * Sends the RPS to the Lajeado's NFSe production (default) or homologation server
     * 
     * @param bool $isHml If true, sends to the homologation server (default: false)
     * 
     * @return Response
     */
    public function send($isHml = false): Response
    {
        $client = Utils::getSoapClient('NFSEremessa', $isHml);

        $xml = $this->toXml();

        $signer = new Signer($this->certPath, $this->certPass);
        $signedXml = $signer->sign($xml, 'LoteRps', 'EnviarLoteRpsEnvio');

        $wsResponse = $client->RecepcionarLoteRpsLimitado([
            'xml' => $signedXml
        ]);

        // Parse response
        $dom = new DOMDocument();
        $dom->loadXML($wsResponse->return);

        $response = (new Response)
            ->setHml($isHml)
            ->setRequestXml($signedXml)
            ->setResponseXml($wsResponse->return);

        // Check if there is a error code present
        $code = Utils::getNodeValue($dom, 'Codigo');

        // Success if no Codigo node is present
        if (!$code) {
            $response->setSuccess(true);
            $response->setData([
                'nextLoteNumber' => $this->numeroLote + 1,
                'nextRpsNumber' => $this->rps->numero + 1,
                'protocolNumber' => Utils::getNodeValue($dom, 'Protocolo')
            ]);

            return $response;
        }


        // Error Cases
        // Auto retry logic to increment the Lote number and RPS number if they are already used

        // Lote number already used, increment and try again
        if ($code == 'E500') {
            $this->numeroLote($this->numeroLote + 1);
            return $this->send($isHml);
        }

        // RPS number already used, remap and try again
        if ($code == 'E10') {
            $this->rps->numero($this->rps->numero + 1);
            return $this->send($isHml);
        }

        // If there is any other error code
        $response->setSuccess(false);
        $response->setErrorMessage(Utils::getNodeValue($dom, 'Mensagem'));
        $response->setErrorCode($code);

        return $response;
    }

    /**
     * Returns the XML representation
     */
    public function toXml()
    {
        $xml = html_entity_decode(ArrayToXml::convert($this->toArray(), [
            'rootElementName' => 'LoteRps',
            '_attributes' => [
                'Id' => 'L' . $this->numeroLote,
            ],
        ]));

        $xml = '<EnviarLoteRpsEnvio xmlns="http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd">' . $xml . '</EnviarLoteRpsEnvio>';

        return $xml;
    }

    /**
     * Returns the raw array representation
     */
    private function toArray(): array
    {
        return array_filter([
            'NumeroLote' => $this->numeroLote,
            'Cnpj' => $this->cnpj,
            'InscricaoMunicipal' => $this->im,
            'QuantidadeRps' => 1,
            'ListaRps' => $this->rps->signAndSave($this->certPath, $this->certPass)
        ]);
    }

}