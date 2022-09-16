<?php

namespace PauloAK\NfseLajeado\Envio\Rps;

use PauloAK\NfseLajeado\Helpers\Utils;

class Tomador {
    private string $cpfCnpj;
    private string $inscricaoMunicipal;
    private string $razaoSocial;
    private Endereco $endereco;
    private string $email;
    private string $telefone;

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
}