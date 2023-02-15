<?php

require __DIR__ . '/../vendor/autoload.php';

use PauloAK\NfseLajeado\CancelarRps;

$cancelamento = (new CancelarRps(__DIR__ . '/../certificate.pfx', 'rand_pass'))
    ->cnpj('00.000.000/0001-00')
    ->im('00000')
    ->numero('00000');

$data = $cancelamento->sendHml();

var_dump($data);