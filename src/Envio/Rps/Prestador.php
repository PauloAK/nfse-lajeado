<?php

namespace PauloAK\NfseLajeado\Envio\Rps;

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
}