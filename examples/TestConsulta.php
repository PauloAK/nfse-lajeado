<?php

require __DIR__ . '/../vendor/autoload.php';

use PauloAK\NfseLajeado\Common\Rps\Prestador;
use PauloAK\NfseLajeado\ConsultarLoteRpsEnvio;

$consulta = (new ConsultarLoteRpsEnvio)
    ->prestador(
        (new Prestador)
        ->cnpj('00.000.000/0001-00')
        ->im('00000')
    )
    ->protocolo('00000');

$data = $consulta->sendHml();

var_dump($data);