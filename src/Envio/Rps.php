<?php

namespace PauloAK\NfseLajeado\Envio;

use PauloAK\NfseLajeado\Envio\Rps\Prestador;
use PauloAK\NfseLajeado\Envio\Rps\Servico;
use PauloAK\NfseLajeado\Envio\Rps\Tomador;

class Rps {
    private string $numero;
    private string $serie;
    private string $tipo;
    private string $dataEmissao;
    private string $naturezaOperacao;
    private string $optanteSimplesNacional;
    private string $incentivadorCultural;
    private string $status;
    private Servico $servico;
    private Prestador $prestador;
    private Tomador $tomador;

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

    public function assinar()
    {
        // TODO
    }
}