<?php

namespace PauloAK\NfseLajeado\Common;

use PauloAK\NfseLajeado\Common\Rps\Prestador;
use PauloAK\NfseLajeado\Common\Rps\Servico;
use PauloAK\NfseLajeado\Common\Rps\Tomador;
use PauloAK\NfseLajeado\Helpers\Constants;
use PauloAK\NfseLajeado\Helpers\Signer;
use Spatie\ArrayToXml\ArrayToXml;

class Rps {
    public ?string $numero = null;
    private ?string $serie = null;
    private ?string $tipo = null;
    private ?string $dataEmissao = null;
    private ?string $naturezaOperacao = null;
    private ?string $optanteSimplesNacional = null;
    private ?string $incentivadorCultural = null;
    private ?string $status = null;
    private ?Servico $servico = null;
    private ?Prestador $prestador = null;
    private ?Tomador $tomador = null;

    function __construct()
    {
        $this->incentivadorCultural(Constants::NAO);
        $this->status(Constants::STATUS_NORMAL);
        $this->dataEmissao(date('Y-m-d\TH:i:s'));
        $this->tipo(Constants::TIPO_RPS_DFS);
    }

    public function numero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    public function serie($serie)
    {
        $this->serie = $serie;
        return $this;
    }

    public function tipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function dataEmissao($dataEmissao)
    {
        $this->dataEmissao = $dataEmissao;
        return $this;
    }

    public function naturezaOperacao($naturezaOperacao)
    {
        $this->naturezaOperacao = $naturezaOperacao;
        return $this;
    }

    public function optanteSimplesNacional($optanteSimplesNacional)
    {
        $this->optanteSimplesNacional = $optanteSimplesNacional;
        return $this;
    }

    public function incentivadorCultural($incentivadorCultural)
    {
        $this->incentivadorCultural = $incentivadorCultural;
        return $this;
    }

    public function status($status)
    {
        $this->status = $status;
        return $this;
    }

    public function servico(Servico $servico)
    {
        $this->servico = $servico;
        return $this;
    }

    public function prestador(Prestador $prestador)
    {
        $this->prestador = $prestador;
        return $this;
    }

    public function tomador(Tomador $tomador)
    {
        $this->tomador = $tomador;
        return $this;
    }

    public function toXml()
    {
        $xml = ArrayToXml::convert($this->toArray(), [
            'rootElementName' => 'InfRps',
            '_attributes' => [
                'Id' => 'R' . $this->numero,
            ]
        ]);

        $xml = "<Rps>" . $xml . "</Rps>";

        return $xml;
    }

    public function signAndSave(string $certPath, string $certPass)
    {
        $signer = new Signer($certPath, $certPass);
        return $signer->sign($this->toXml(), 'InfRps', 'Rps');
    }

    private function toArray(): array
    {
        return array_filter([
            'IdentificacaoRps' => array_filter([
                'Numero' => $this->numero,
                'Serie' => $this->serie,
                'Tipo' => $this->tipo,
            ]),
            'DataEmissao' => $this->dataEmissao,
            'NaturezaOperacao' => $this->naturezaOperacao,
            'OptanteSimplesNacional' => $this->optanteSimplesNacional,
            'IncentivadorCultural' => $this->incentivadorCultural,
            'Status' => $this->status,
            'Servico' => $this->servico->toArray(),
            'Prestador' => $this->prestador->toArray(),
            'Tomador' => $this->tomador->toArray(),
        ]);
    }
}