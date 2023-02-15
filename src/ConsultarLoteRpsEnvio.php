<?php

namespace PauloAK\NfseLajeado;

use DOMDocument;
use PauloAK\NfseLajeado\Common\Rps\Prestador;
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
     * @return string An array with the NFS-e number and verification code
     */
    public function sendHml()
    {
        return $this->send(true);
    }

    /**
     * Sends the request to the Lajeado's NFSe production (default) or homologation server
     * 
     * @param bool $isHml If true, sends to the homologation server (default: false)
     * 
     * @return array An array with the NFS-e number and verification code
     */
    public function send($isHml = false)
    {
        $client = Utils::getSoapClient('NFSEconsulta', $isHml);

        $response = $client->consultarLoteRps([
            'xml' => $this->toXml()
        ]);

        // Parse response
        $dom = new DOMDocument();
        $dom->loadXML($response->return);

        $number = $dom->getElementsByTagName('Numero')->item(0)->nodeValue;
        $verificationNumber = $dom->getElementsByTagName('CodigoVerificacao')->item(0)->nodeValue;

        return [
            'number' => $number,
            'verificationNumber' => $verificationNumber
        ];
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