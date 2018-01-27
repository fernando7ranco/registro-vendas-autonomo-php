<?php

class Pedido{

    private $id;
    private $cliente;
    private $valorTotal;
    private $anotacao;
    private $dataInicio;
    private $dataPagamento;
    private $status;
    private $itens;

    public function __construct($dados) {
        for ($i = 0; $i <= 7; $i++)
            $dados[$i] = isset($dados[$i]) ? $dados[$i] : null;

        $this->id = $dados[0];
        $this->cliente = $dados[1];
        $this->valorTotal = $dados[2];
        $this->anotacao = $dados[3];
        $this->data_inicio = $dados[4];
        $this->dataPagamento = $dados[5];
        $this->status = $dados[6];
        $this->itens = $dados[7];
        
    }
    
    public function getId() {
        return $this->id;
    }

    public function getCliente() {
        return $this->cliente;
    }

    public function getValorTotal($condicao = true) {
        return numeroDecimal($this->valorTotal,$condicao);
    }

    public function getAnotacao() {
        return $this->anotacao;
    }

    public function getDataInicio() {
        return $this->dataInicio;
    }

    public function getDataPagamento() {
        return $this->dataPagamento;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getItens() {
        return $this->itens;
    }


   
}
