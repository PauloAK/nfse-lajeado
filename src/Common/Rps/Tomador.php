<?php

namespace PauloAK\NfseLajeado\Common\Rps;

use PauloAK\NfseLajeado\Helpers\Utils;

class Tomador {
    private ?string $cpfCnpj = null;
    private ?string $inscricaoMunicipal = null;
    private ?string $razaoSocial = null;
    private ?Endereco $endereco = null;
    private ?string $email = null;
    private ?string $telefone = null;

    public function cpfCnpj($cpfCnpj)
    {
        $this->cpfCnpj = Utils::onlyNumbers($cpfCnpj);
        return $this;
    }

    public function inscricaoMunicipal($inscricaoMunicipal)
    {
        $this->inscricaoMunicipal = $inscricaoMunicipal;
        return $this;
    }

    public function razaoSocial($razaoSocial)
    {
        $this->razaoSocial = $razaoSocial;
        return $this;
    }

    public function endereco(Endereco $endereco)
    {
        $this->endereco = $endereco;
        return $this;
    }

    public function email($email)
    {
        $this->email = $email;
        return $this;
    }

    public function telefone($telefone)
    {
        $this->telefone = Utils::onlyNumbers($telefone);
        return $this;
    }

    public function toArray(): array
    {
        $cpfCnpjNode = strlen($this->cpfCnpj) > 11 ? ['Cnpj' => $this->cpfCnpj] : ['Cpf' => $this->cpfCnpj];

        return array_filter([
            'IdentificacaoTomador' => array_filter([
                'CpfCnpj' => $cpfCnpjNode,
                'InscricaoMunicipal' => $this->inscricaoMunicipal
            ]),
            'RazaoSocial' => $this->razaoSocial,
            'Endereco' => $this->endereco->toArray(),
            'Contato' => array_filter([
                'Telefone' => $this->telefone,
                'Email' => $this->email
            ])
        ]);
    }
}