<?php

namespace PauloAK\NfseLajeado\Common\Rps;

class Servico {
    private ?string $valorServicos = null;
    private ?string $valorDeducoes = null;
    private ?string $valorPis = null;
    private ?string $valorCofins = null;
    private ?string $valorInss = null;
    private ?string $valorIr = null;
    private ?string $valorCsll = null;
    private ?string $issRetido = null;
    private ?string $valorIss = null;
    private ?string $outrasRetencoes = null;
    private ?string $baseCalculo = null;
    private ?string $aliquota = null;
    private ?string $valorLiquidoNfse = null;
    private ?string $descontoIncondicionado = null;
    private ?string $descontoCondicionado = null;
    private ?string $itemListaServico = null;
    private ?string $codigoCnae = null;
    private ?string $discriminacao = null;
    private ?string $codigoMunicipio = '4311403';

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

    public function toArray(): array
    {
        return array_filter([
            'Valores' => array_filter([
                'ValorServicos' => $this->valorServicos,
                'ValorDeducoes' => $this->valorDeducoes,
                'ValorPis' => $this->valorPis,
                'ValorCofins' => $this->valorCofins,
                'ValorInss' => $this->valorInss,
                'ValorIr' => $this->valorIr,
                'ValorCsll' => $this->valorCsll,
                'IssRetido' => $this->issRetido,
                'ValorIss' => $this->valorIss,
                'OutrasRetencoes' => $this->outrasRetencoes,
                'BaseCalculo' => $this->baseCalculo ?: $this->valorServicos,
                'Aliquota' => $this->aliquota,
                'ValorLiquidoNfse' => $this->valorLiquidoNfse ?: $this->valorServicos,
                'DescontoIncondicionado' => $this->descontoIncondicionado,
                'DescontoCondicionado' => $this->descontoCondicionado,
            ]),
            'ItemListaServico' => $this->itemListaServico,
            'CodigoCnae' => $this->codigoCnae,
            'Discriminacao' => $this->discriminacao,
            'CodigoMunicipio' => $this->codigoMunicipio,
        ]);
    }
}