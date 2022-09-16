<?php

namespace PauloAK\NfseLajeado\Envio;

class Lote {
    private string $numeroLote;
    private string $cnpj;
    private string $im;
    private array $rps = [];

    public function numeroLote($numeroLote)
    {
        $this->numeroLote = $numeroLote;
        return $this;
    }

    public function cnpj($cnpj)
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    public function im($im)
    {
        $this->im = $im;
        return $this;
    }

    public function addRps(Rps $rps)
    {
        $this->rps[] = $rps;
        return $this;
    }

    public function assinar()
    {
        // TODO
    }

    public function enviar()
    {

    }

}