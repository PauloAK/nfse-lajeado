<?php

namespace PauloAK\NfseLajeado\Envio\Rps;

class Servico {
    private string $valorServicos;
    private string $valorDeducoes;
    private string $valorPis;
    private string $valorCofins;
    private string $valorInss;
    private string $valorIr;
    private string $valorCsll;
    private string $issRetido;
    private string $valorIss;
    private string $outrasRetencoes;
    private string $baseCalculo;
    private string $aliquota;
    private string $valorLiquidoNfse;
    private string $descontoIncondicionado;
    private string $descontoCondicionado;
    private string $itemListaServico;
    private string $codigoCnae;
    private string $discriminacao;
    private string $codigoMunicipio;

    public function valorServicos($valorServicos)
    {
        $this->valorServicos = $valorServicos;
        return $this;
    }

    public function valorDeducoes($valorDeducoes)
    {
        $this->valorDeducoes = $valorDeducoes;
        return $this;
    }

    public function valorPis($valorPis)
    {
        $this->valorPis = $valorPis;
        return $this;
    }

    public function valorCofins($valorCofins)
    {
        $this->valorCofins = $valorCofins;
        return $this;
    }

    public function valorInss($valorInss)
    {
        $this->valorInss = $valorInss;
        return $this;
    }

    public function valorIr($valorIr)
    {
        $this->valorIr = $valorIr;
        return $this;
    }

    public function valorCsll($valorCsll)
    {
        $this->valorCsll = $valorCsll;
        return $this;
    }

    public function issRetido($issRetido)
    {
        $this->issRetido = $issRetido;
        return $this;
    }

    public function valorIss($valorIss)
    {
        $this->valorIss = $valorIss;
        return $this;
    }

    public function outrasRetencoes($outrasRetencoes)
    {
        $this->outrasRetencoes = $outrasRetencoes;
        return $this;
    }

    public function baseCalculo($baseCalculo)
    {
        $this->baseCalculo = $baseCalculo;
        return $this;
    }

    public function aliquota($aliquota)
    {
        $this->aliquota = $aliquota;
        return $this;
    }

    public function valorLiquidoNfse($valorLiquidoNfse)
    {
        $this->valorLiquidoNfse = $valorLiquidoNfse;
        return $this;
    }

    public function descontoIncondicionado($descontoIncondicionado)
    {
        $this->descontoIncondicionado = $descontoIncondicionado;
        return $this;
    }

    public function descontoCondicionado($descontoCondicionado)
    {
        $this->descontoCondicionado = $descontoCondicionado;
        return $this;
    }

    public function itemListaServico($itemListaServico)
    {
        $this->itemListaServico = $itemListaServico;
        return $this;
    }

    public function codigoCnae($codigoCnae)
    {
        $this->codigoCnae = $codigoCnae;
        return $this;
    }

    public function discriminacao($discriminacao)
    {
        $this->discriminacao = $discriminacao;
        return $this;
    }

    public function codigoMunicipio($codigoMunicipio)
    {
        $this->codigoMunicipio = $codigoMunicipio;
        return $this;
    }
}