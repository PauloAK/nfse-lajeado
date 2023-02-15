<?php

namespace PauloAK\NfseLajeado;

use DOMDocument;
use PauloAK\NfseLajeado\Common\Rps\Prestador;
use PauloAK\NfseLajeado\Helpers\Response;
use PauloAK\NfseLajeado\Helpers\Utils;
use Spatie\ArrayToXml\ArrayToXml;

class ConsultarLoteRpsEnvio
{
    private ?Prestador $prestador = null;
    private ?string $protocolo = null;

    /**
     * Sets Prestador
     */
    public function prestador(Prestador $prestador)
    {
        $this->prestador = $prestador;
        return $this;
    }

    /**
     * Sets Protocolo
     */
    public function protocolo($protocolo)
    {
        $this->protocolo = $protocolo;
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
        $client = Utils::getSoapClient('NFSEconsulta', $isHml);

        $xml = $this->toXml();

        $wsResponse = $client->consultarLoteRps([
            'xml' => $xml
        ]);

        // Parse response
        $dom = new DOMDocument();
        $dom->loadXML($wsResponse->return);

        $response = (new Response)
            ->setHml($isHml)
            ->setRequestXml($xml)
            ->setResponseXml($wsResponse->return);

        // Check if there is a error code present
        $code = Utils::getNodeValue($dom, 'Codigo');

        // Success if no Codigo node is present
        if (!$code) {
            $response->setSuccess(true);
            $response->setData([
                'number' => Utils::getNodeValue($dom, 'Numero'),
                'verificationNumber' => Utils::getNodeValue($dom, 'CodigoVerificacao')
            ]);

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
        return html_entity_decode(ArrayToXml::convert($this->toArray(), [
            'rootElementName' => 'ConsultarLoteRpsEnvio',
            '_attributes' => [
                'xmlns' => 'http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd',
            ],
        ]));
    }

    /**
     * Returns the raw array representation
     */
    private function toArray(): array
    {
        return array_filter([
            'Prestador' => $this->prestador->toArray(),
            'Protocolo' => $this->protocolo,
        ]);
    }

}