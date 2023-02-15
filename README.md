## Brief
This package currently supports creating, retrieving and canceling NFS-e (RPS) in the WebService of the city of Lajeado/RS - Brazil.

## Package
You can install it through composer
```bash
composer require pauloak/nfse-lajeado
```

## Observation
To be able to use the package, you'll need to have a valid .pfx certificate and the password to access it, it's used to sign the XML before sending it to the city WebService.

## Usage
You can check some usage examples in the [examples](examples) folder, but here's a quick overview.

### Create NFS-e
_Needs a .pfx certificate_

```php
use PauloAK\NfseLajeado\EnviarLoteRpsEnvio;
use PauloAK\NfseLajeado\Common\Rps;
use PauloAK\NfseLajeado\Common\Rps\Endereco;
use PauloAK\NfseLajeado\Common\Rps\Prestador;
use PauloAK\NfseLajeado\Common\Rps\Servico;
use PauloAK\NfseLajeado\Common\Rps\Tomador;
use PauloAK\NfseLajeado\Helpers\Constants;

const CERTIFICATE_PATH = '/path/to/certificate.pfx';
const CERTIFICATE_PASS = 'rand_pass';

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
            ->codigoMunicipio('4311403') // IBGE Code
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

$lote = (new EnviarLoteRpsEnvio(CERTIFICATE_PATH, CERTIFICATE_PASS))
    ->numeroLote(2)
    ->cnpj('00.000.000/0001-00')
    ->im('00000')
    ->rps($rps);

// Sends to Homologation
$data = $lote->sendHml();

// Sends to Production
// $data = $lote->send();
```

If your call is successful, you'll receive an array with the next lote number, next rps number and the protocol number to retrieve the NFS-e.
```php
$returnedData = [
    'numeroLote' => 50, // Number - Next lote number, you can store and use in the next NFS-e creation
    'numeroRps' => 101, // Number - Next rps number, you can store and use in the next NFS-e creation
    'protocolo' => '000000000' // String - Protocol number to retrieve the NFS-e
];
```

### Retrieve NFS-e
_Does NOT need a .pfx certificate_
After you create an NFS-e, you'll receive a protocol number which you can use to retrieve the NFS-e.
Currently, this package only returns the NFS-e number and the verification code.
```php
use PauloAK\NfseLajeado\Common\Rps\Prestador;
use PauloAK\NfseLajeado\ConsultarLoteRpsEnvio;

$consulta = (new ConsultarLoteRpsEnvio)
    ->prestador(
        (new Prestador)
            ->cnpj('00.000.000/0001-00')
            ->im('00000')
    )
    ->protocolo('00000'); // The protocol number returned in the creation call

// Sends to homologation
$data = $consulta->sendHml();
```

If your call is successful, you'll receive an array with the NFS-e number and the verification code.
```php
$returnedData = [
    'numero' => 20231, // Number - NFS-e number
    'codigoVerificacao' = 'ABC12346' // String - Verification code
];
```

### Cancel NFS-e
_Needs a .pfx certificate_
To cancel an NFS-e, you'll need the NFS-e number and the certificate.
```php
use PauloAK\NfseLajeado\CancelarRps;

const CERTIFICATE_PATH = '/path/to/certificate.pfx';
const CERTIFICATE_PASS = 'rand_pass';

$cancelamento = (new CancelarRps(CERTIFICATE_PATH, CERTIFICATE_PASS))
    ->cnpj('00.000.000/0001-00')
    ->im('00000')
    ->numero('00000');

// Sends to homologation
$data = $cancelamento->sendHml();
```

If your call is successful it should return `true`.