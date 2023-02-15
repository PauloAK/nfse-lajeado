<?php

namespace PauloAK\NfseLajeado;

use DOMDocument;
use Exception;
use PauloAK\NfseLajeado\Common\Rps\Prestador;
use PauloAK\NfseLajeado\Helpers\Response;
use PauloAK\NfseLajeado\Helpers\Signer;
use PauloAK\NfseLajeado\Helpers\Utils;
use Spatie\ArrayToXml\ArrayToXml;

class CancelarRps
{
    private ?string $numero = null;
    private ?string $cnpj = null;
    private ?string $im = null;
    private string $codigoCancelamento = 'E506';
    private string $codigoMunicipio = '4311403';

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
     * Sets Numero
     */
    public function numero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * Sets Cnpj
     */
    public function cnpj($cnpj)
    {
        $this->cnpj = Utils::onlyNumbers($cnpj);
        return $this;
    }

    /**
     * Sets InscricaoMunicipal
     */
    public function im($im)
    {
        $this->im = $im;
        return $this;
    }

    /**
     * Sets CodigoCancelamento
     */
    public function codigoCancelamento($codigoCancelamento)
    {
        $this->codigoCancelamento = $codigoCancelamento;
        return $this;
    }

    /**
     * Sets CodigoMunicipio
     */
    public function codigoMunicipio($codigoMunicipio)
    {
        $this->codigoMunicipio = $codigoMunicipio;
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
     * Sends the request to the Lajeado's NFSe production (default) or homologation server
     * 
     * @param bool $isHml If true, sends to the homologation server (default: false)
     * 
     * @return Response
     */
    public function send($isHml = false): Response
    {
        $client = Utils::getSoapClient('NFSEcancelamento', $isHml);

        $xml = $this->toXml();

        $signer = new Signer($this->certPath, $this->certPass);
        $signedXml = $signer->sign($xml, 'InfPedidoCancelamento', 'Pedido');

        $wsResponse = $client->cancelarNfse([
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

        if (!$code) {
            $response->setSuccess(true);
            return $response;
        }

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
            'rootElementName' => 'InfPedidoCancelamento',
            '_attributes' => [
                'Id' => 'C' . $this->numero,
            ],
        ]));

        $xml = '<Pedido xmlns="http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd">' . $xml . '</Pedido>';
        $xml = '<CancelarNfseEnvio xmlns="http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd">' . $xml . '</CancelarNfseEnvio>';

        return $xml;
    }

    /**
     * Returns the raw array representation
     */
    private function toArray(): array
    {
        return array_filter([
            'IdentificacaoNfse' => [
                'Numero' => $this->numero,
                'Cnpj' => $this->cnpj,
                'InscricaoMunicipal' => $this->im,
                'CodigoMunicipio' => $this->codigoMunicipio,
            ],
            'CodigoCancelamento' => $this->codigoCancelamento,
        ]);
    }

}