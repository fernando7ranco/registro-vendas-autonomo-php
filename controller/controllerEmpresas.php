<?php

include '../model/dataBase/bancoDedados.php';
include '../model/funcoes/todasFuncoes.php';

class ControllerEmpresas{
	
	private $banco;
	private $dados;
	
	public function __construct($banco,$dados = [null]){
		
		$this->banco = $banco;
		
		foreach($dados as $value)
			$this->dados[] = $value;
		
	}
	
	public function mostraEmpresas(){
		
		$nome = $this->dados[0];
		
		$query = $this->banco->executaQuery("SELECT * FROM empresas WHERE nome LIKE '{$nome}%' ");
		$lista = null;
		while ($linhas = $query->fetch_array()){
			
			$lista.="<li value='{$linhas['id']}'>
						<div>
							<a>{$linhas['nome']}</a>
						</div><button id='btEditar'>editar</button> <button id='btExcluir'>excluir</button>
					</li>";
		}
		return $lista;
	}	
	
	
	public function adicionarEmpresa(){
		
		$nome = $this->dados[0];
		
		$this->banco->executaQuery("INSERT INTO `empresas` VALUES (null,'{$nome}')");
	}
	
	public function editarEmpresa(){
		
		$id = $this->dados[0];
		$nome = $this->dados[1];
		
		$this->banco->executaQuery("UPDATE empresas SET nome = '{$nome}' WHERE id = {$id}");
	}

	
	public function excluirEmpresa(){
		
		$id = $this->dados[0];
		
		$this->banco->executaQuery("DELETE FROM empresas WHERE  id = {$id}");
	}	

	
} 

@$POST = $_POST;

$acao = isset($POST['acao']) ? $POST['acao'] : null;


if($acao == 'burarPorEmpresas'){
	

	$nome = $POST['nome'];
	
	$banco = new bancoDedados;
	$controllerEmpresas = new ControllerEmpresas($banco, [$nome]);
	echo $controllerEmpresas->mostraEmpresas();
	$banco->fechaConexao();
	exit;
	
}

if($acao == 'adicionarEmpresa'){
	
	$nome = $POST['nome'];
	
	$banco = new bancoDedados;
	$controllerEmpresas = new ControllerEmpresas($banco, [$nome]);
	echo $controllerEmpresas->adicionarEmpresa();
	$banco->fechaConexao();
	exit;
	
}

if($acao == 'editarEmpresa'){
	
	$id = $POST['id'];
	$nome = $POST['nome'];
	
	$banco = new bancoDedados;
	$controllerEmpresas = new ControllerEmpresas($banco, [$id, $nome]);
	echo $controllerEmpresas->editarEmpresa();
	$banco->fechaConexao();
	exit;
	
}

if($acao == 'excluirEmpresa'){
	
	$id = $POST['id'];
	
	$banco = new bancoDedados;
	$controllerEmpresas = new ControllerEmpresas($banco, [$id]);
	echo $controllerEmpresas->excluirEmpresa();
	$banco->fechaConexao();
	exit;
	
}

$banco = new bancoDedados;
$controllerEmpresas = new ControllerEmpresas($banco, ['']);
$listaEmpresas = $controllerEmpresas->mostraEmpresas();
$banco->fechaConexao();


?>