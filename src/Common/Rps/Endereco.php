<?php

namespace PauloAK\NfseLajeado\Common\Rps;

use PauloAK\NfseLajeado\Helpers\Utils;

class Endereco {
    private $rua = null;
    private $numero = null;
    private $complemento = null;
    private $bairro = null;
    private $codigoMunicipio = null;
    private $uf = null;
    private $cep = null;

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

    public function toArray(): array
    {
        return array_filter([
            'Endereco' => $this->rua,
            'Numero' => $this->numero,
            'Complemento' => $this->complemento,
            'Bairro' => $this->bairro,
            'CodigoMunicipio' => $this->codigoMunicipio,
            'Uf' => $this->uf,
            'Cep' => $this->cep,
        ]);
    }
}