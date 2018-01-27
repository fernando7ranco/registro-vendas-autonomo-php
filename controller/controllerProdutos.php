<?php

include '../model/domain/produto.php';
include '../model/dao/produtoDAO.php';

include '../model/funcoes/todasFuncoes.php';

include '../model/dataBase/bancoDedados.php';

class ControllerProdutos{
	
	private $banco;
	private $dados;
	
	public function __construct($banco,$dados = [null]){
		
		$this->banco = $banco;
		
		foreach($dados as $name => $value)
			$this->dados[] = $value;
		
	}
	
	public function pegaProdutos(){
		
		$produtoDAO = new ProdutoDAO($this->banco);
		$return = $produtoDAO->selecionaProdutos($this->dados[0], $this->dados[1], $this->dados[2]);
	
		return $return;
	}
	
	public function excluirProduto(){
		
		$produtoDAO = new ProdutoDAO($this->banco);
		echo $produtoDAO->excluirProduto($this->dados[0]);

	}
	
} 


$banco = new BancoDedados();

if(isset($_POST['excluirProduto'])){
	
	$dados[] = $_POST['id'];
	
	$ControllerProdutos = new ControllerProdutos($banco, $dados);
	$produtos = $ControllerProdutos->excluirProduto();
	$banco->fechaConexao();
	exit;
}

$dados[] = isset($_GET['empresas']) ? $_GET['empresas'] : null;
$dados[] = isset($_GET['nome']) ? $_GET['nome'] : null;
$dados[] = isset($_GET['limite']) ? $_GET['limite'] : 0;

$ControllerProdutos = new ControllerProdutos($banco, $dados);
$produtos = $ControllerProdutos->pegaProdutos();

$produto = new Produto([]);
$banco->fechaConexao();

?>