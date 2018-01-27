<?php

include '../model/domain/produto.php';
include '../model/dao/produtoDAO.php';
include '../model/dataBase/bancoDedados.php';
include '../model/funcoes/todasFuncoes.php';

class ControllerUploadProduto{
	
	private $banco;
	private $dados;
	
	public function __construct($banco,$dados = [null]){
		
		$this->banco = $banco;
		
		foreach($dados as $name => $value)
			$this->dados[$name] = $value;
		
	}
	
	public function inserirProduto(){
		
		$produto = new Produto($this->dados);
		$produtoDAO = new ProdutoDAO($this->banco);
		return $produtoDAO->inserirProduto($produto);
		
	}	
	
	public function editarProduto(){
		
		$produto = new Produto($this->dados);
	
		$produtoDAO = new ProdutoDAO($this->banco);
		return $produtoDAO->alterarProduto($produto);
		
	}
	
	public function listarCategorias(){
		
		$categoriaSubcategoria = new CategoriaSubcategoria([ 2 => $this->dados[0], 3=> $this->dados[1] ]);
		$categoriaSubcategoriaDAO = new CategoriaSubcategoriaDAO($this->banco);
		
		$result = $categoriaSubcategoriaDAO->selecionaCategorias($categoriaSubcategoria);
		$lista = null;
		while ($linhas = $result->fetch_array())
			$lista[] = [$linhas['id'], $linhas['nome']];
		
		return $lista;
	}	
	
	
	public function pegarProduto(){
	
		$produtoDAO = new ProdutoDAO($this->banco);
		return $produtoDAO->selecionaProduto($this->dados[0]);
	}
	
} 

@$POST = $_POST;

if(isset($_GET['produto']) and is_numeric($_GET['produto']))
	$idProduto = $_GET['produto'];
else
	$idProduto = 0;

if(isset($POST['uploadProduto'])){

	
	$dados[] = $idProduto;
	$dados[] = $POST['empresa'];
	$dados[] = $POST['codigo'];
	$dados[] = isset($_FILES['imagem']) ? [true , $_FILES['imagem']] : [false];
	$dados[] = $POST['nome'];
	$dados[] = $POST['descricao'] ? $POST['descricao'] : '';
	$dados[] = $POST['valor'];
	$dados[] = $POST['quantidade'];
	
	$banco = new BancoDedados;
	
	if($idProduto){
		
		$query = $banco->executaQuery("SELECT id FROM produtos WHERE id = {$idProduto}");
    
		if ($query->num_rows == 1) {
		
			$controllerUploadProduto = new ControllerUploadProduto($banco,$dados);
			echo $controllerUploadProduto->editarProduto();
		}
	}else{
		$controllerUploadProduto = new ControllerUploadProduto($banco,$dados);
		echo $controllerUploadProduto->inserirProduto();
	}
	
	$banco->fechaConexao();
	exit;
	
}
	
$banco = new BancoDedados;
$controllerUploadProduto = new ControllerUploadProduto($banco,[$idProduto]);
$produto = $controllerUploadProduto->pegarProduto();
$banco->fechaConexao();
		


?>