<?php

namespace PauloAK\NfseLajeado\Common\Rps;

use PauloAK\NfseLajeado\Helpers\Utils;

class Prestador {
    private string $cnpj;
    private string $im;

    public function cnpj($cnpj)
    {
        $this->cnpj = Utils::onlyNumbers($cnpj);
        return $this;
    }

    public function im($im)
    {
        $this->im = $im;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'Cnpj' => $this->cnpj,
            'InscricaoMunicipal' => $this->im,
        ];
    }
}