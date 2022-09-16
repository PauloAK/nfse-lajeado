<?php

namespace PauloAK\NfseLajeado\Envio\Rps;

use PauloAK\NfseLajeado\Helpers\Utils;

class Endereco {
    private string $rua;
    private string $numero;
    private string $complemento;
    private string $bairro;
    private string $codigoMunicipio;
    private string $uf;
    private string $cep;

    public function rua($rua)
    {
        $this->rua = $rua;
        return $this;
    }

    public function numero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    public function complemento($complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }

    public function bairro($bairro)
    {
        $this->bairro = $bairro;
        return $this;
    }

    public function codigoMunicipio($codigoMunicipio)
    {
        $this->codigoMunicipio = $codigoMunicipio;
        return $this;
    }

    public function uf($uf)
    {
        $this->uf = $uf;
        return $this;
    }

    public function cep($cep)
    {
        $this->cep = Utils::onlyNumbers($cep);
        return $this;
    }
}