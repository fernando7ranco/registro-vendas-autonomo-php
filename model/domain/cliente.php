<?php

class Cliente{

    private $id;
    private $nome;
    private $cpf;
    private $cep;
    private $logradouro;
    private $bairro;
    private $telefone;

    public function __construct($dados) {
        for ($i = 0; $i <= 6; $i++)
            $dados[$i] = isset($dados[$i]) ? $dados[$i] : null;

        $this->id = $dados[0];
        $this->nome = $dados[1];
        $this->cpf = $dados[2];
        $this->cep = $dados[3];
        $this->logradouro = $dados[4];
        $this->bairro = $dados[5];
        $this->telefone = $dados[6];
    
    }

    
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getCpf() {
        return $this->cpf;
    }

    function getCep() {
        return $this->cep;
    }

    function getLogradouro() {
        return $this->logradouro;
    }

    function getBairro() {
        return $this->bairro;
	}

    function getTelefone() {
        return $this->telefone;
    }

}
