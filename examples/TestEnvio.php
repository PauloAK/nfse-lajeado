<?php

require __DIR__ . '/../vendor/autoload.php';

use PauloAK\NfseLajeado\EnviarLoteRpsEnvio;
use PauloAK\NfseLajeado\Common\Rps;
use PauloAK\NfseLajeado\Common\Rps\Endereco;
use PauloAK\NfseLajeado\Common\Rps\Prestador;
use PauloAK\NfseLajeado\Common\Rps\Servico;
use PauloAK\NfseLajeado\Common\Rps\Tomador;
use PauloAK\NfseLajeado\Helpers\Constants;

$tomador = (new Tomador)
    ->cpfCnpj('000.000.000-00')
    ->telefone('51 99999-9999')
    ->email('email@example.com')
    ->razaoSocial('Example')
    ->endereco(
        (new Endereco)
            ->rua('Av. Test')
            ->numero('0000')
            ->bairro('Centro')
            ->codigoMunicipio('4311403') // IBGE
            ->uf('RS')
            ->cep('95900-000')
            ->complemento('')
    );

$servico = (new Servico)
    ->codigoCnae('0000000')
    ->itemListaServico('000')
    ->discriminacao('Test RPS')
    ->issRetido(Constants::NAO)
    ->valorServicos(99.99);

$prestador = (new Prestador)
    ->cnpj('00.000.0000/0001-00')
    ->im('00000');

$rps = (new Rps)
    ->tomador($tomador)
    ->prestador($prestador)
    ->servico($servico)
    ->serie('00000')
    ->numero(1)
    ->naturezaOperacao(Constants::NATUREZA_OPERACAO_IMPOSTO_RECOLHIDO_PELO_REGIME_UNICO_TRIBUTACAO)
    ->optanteSimplesNacional(Constants::SIM);

$lote = (new EnviarLoteRpsEnvio(__DIR__ . '/../certificate.pfx', 'rand_pass'))
    ->numeroLote(2)
    ->cnpj('00.000.000/0001-00')
    ->im('00000')
    ->rps($rps);

$data = $lote->sendHml();

var_dump($data);