<?php

namespace PauloAK\NfseLajeado\Helpers;

use SoapClient;

class Utils {

    public static function onlyNumbers($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }

    public static function getSoapClient(string $service, bool $isHml = false)
    {
        $hml = $isHml ? 'hml' : '';
        $url = "https://nfse{$hml}.lajeado.rs.gov.br/thema-nfse/services/{$service}?wsdl";

        return new SoapClient($url, [
            'trace' => true,
            'exceptions' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'soap_version' => SOAP_1_2,
            'encoding' => 'ISO-8859-1',
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]),
        ]);
    }
}