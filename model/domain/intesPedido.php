<?php

class IntesPedido{

    private $id;
    private $idPedido;
    private $idProduto;
    private $valor;
    private $quantidade;

    public function __construct($dados) {
        for ($i = 0; $i <= 4; $i++)
            $dados[$i] = isset($dados[$i]) ? $dados[$i] : null;

        $this->id = $dados[0];
        $this->idPedido = $dados[1];
        $this->idProduto = $dados[2];
        $this->valor = $dados[3];
        $this->quantidade = $dados[4];
    }
    
    public function getId() {
        return $this->id;
    }

    public function getIdPedido() {
        return $this->idPedido;
    }

    public function getIdProduto() {
        return $this->idProduto;
    }

    public function getValor() {
        return $this->valor;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }
}
