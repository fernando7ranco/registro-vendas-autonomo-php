<?php

class Produto {

    private $id;
	private $empresa;
    private $codigo;
    private $imagem;
    private $nome;
    private $descricao;
    private $valor;
    private $quantidade;

    public function __construct($dado) {

        for ($i = 0; $i <= 7; $i++)
            $dados[$i] = isset($dado[$i]) ? $dado[$i] : null;

        $this->id = $dados[0];
        $this->empresa = $dados[1];
        $this->codigo = $dados[2];
        $this->imagem = $dados[3];
        $this->nome = $dados[4];
        $this->descricao = $dados[5];
        $this->valor = $dados[6];
        $this->quantidade = $dados[7];

	
    }
	
    public function getId() {
        return $this->id;
    }
	
	public function getEmpresa($condicao = null) {
		
		if($condicao == 'select'){
			
			$banco = new BancoDeDados;
			$query = $banco->executaQuery("SELECT * FROM empresas");
			$options = "<option value='' >selecione uma empresa</option>";
			while($linhas = $query->fetch_array()){
				
				$selected = $this->empresa == $linhas['id'] ? 'selected' : '';
				
				$options.= "<option {$selected} value='{$linhas['id']}'>{$linhas['nome']}</option>";
			}
			$banco->fechaConexao();
			return "<select name='empresas'>{$options}</select>";
		}
		
        return $this->empresa;
    }
	
	public function getCodigo() {
        return $this->codigo;
    }
	
    public function getNome() {
        return $this->nome;
    }

    public function getImagem() {
        return $this->imagem;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getValor($condicao = true) {
        return numeroDecimal($this->valor,$condicao);
    }

    public function getQuantidade() {
        return $this->quantidade;
    }

	
	public function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }
	
	public function setValor($valor) {
        $this->valor = $valor;
    }
	

}
