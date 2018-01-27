<?php

include '../model/domain/cliente.php';
include '../model/dao/clienteDAO.php';
include '../model/dao/produtoDAO.php';
include '../model/domain/pedido.php';
include '../model/dao/pedidoDAO.php';
include '../model/funcoes/todasFuncoes.php';
include '../model/dataBase/bancoDedados.php';


class ControllerCliente{
	
	private $banco;
	private $dados;
	
	public function __construct($banco, $dados = [null]){
		
		$this->banco = $banco;
		
		foreach($dados as $name => $value)
			$this->dados[] = $value;
		
	}
	
	public function pegaCliente(){
		
		$clienteDAO = new ClienteDAO($this->banco);
		return $clienteDAO->selecionaClienteId($this->dados[0]);
	}
	
	public function pedidosDoCliente($id){
		
		$pedidoDAO = new PedidoDAO($this->banco);
		return $pedidoDAO->pedidosDoCliente($this->dados[0], $id);
	}
	
	public function numeroDePedidosCliente(){
		
		$pedidoDAO = new PedidoDAO($this->banco);
		return $pedidoDAO->numeroDePedidosCliente($this->dados[0]);
	}
	
	public function procurarProdutoAddPedidos(){
		
		$produtoDAO = new ProdutoDAO($this->banco);
		return $produtoDAO->procurarProdutoAddPedidos($this->dados[0]);
	}
	
	public function inseriPedido(){
		
		$pedido = new Pedido($this->dados);
		$pedidoDAO = new PedidoDAO($this->banco);
		$retorno = $pedidoDAO->inseriPedido($pedido);
		
		$this->dados[0] = $this->dados[1];
		
		return $retorno ? $this->pedidosDoCliente($retorno) : $retorno;
	}	
	
	public function excluirCliente(){
		
		$clienteDAO = new ClienteDAO($this->banco);
		$clienteDAO->excluirCliente($this->dados[0]);
	}
	
	
	public function mudarStatusPedido(){
	
		$pedidoDAO = new PedidoDAO($this->banco);
		$retorno = $pedidoDAO->mudarStatus($this->dados[0]);
	}	
	
	public function excluirPedido(){
	
		$pedidoDAO = new PedidoDAO($this->banco);
		$retorno = $pedidoDAO->excluirPedido($this->dados[0]);
	}
	
} 

@$GET = $_GET;

@$POST = $_POST;

$idCliente = isset($GET['cliente']) ? $GET['cliente'] : 0;

$banco = new BancoDeDados;

if(isset($POST['procurarProdutoAddPedidos'])){
	
	$ControllerCliente = new ControllerCliente($banco,[$POST['nome']]);
	echo $ControllerCliente->procurarProdutoAddPedidos();
	
	$banco->fechaConexao();
	exit;
}

if(isset($POST['inseriPedido'])){
	
	$dados[] = null;
	$dados[] = $idCliente;
	$dados[] = $POST['valorTotal'];
	$dados[] = $POST['anotacao'];
	$dados[] = null;
	$dados[] = $POST['dataPagamento'];
	$dados[] = $POST['status'];
	$dados[] = $POST['itens'];
	
	$ControllerCliente = new ControllerCliente($banco,$dados);
	echo $ControllerCliente->inseriPedido();
	
	$banco->fechaConexao();
	exit;
}

if(isset($POST['excluirCliente'])){
	
	$dados[] = $idCliente;
	
	$ControllerCliente = new ControllerCliente($banco,$dados);
	$ControllerCliente->excluirCliente();
	
	$banco->fechaConexao();
	exit;
}

if(isset($POST['mudarStatusPedido'])){
	
	$dados[] = $POST['id'];
	
	$ControllerCliente = new ControllerCliente($banco,$dados);
	$ControllerCliente->mudarStatusPedido();
	
	$banco->fechaConexao();
	exit;
}

if(isset($POST['excluirPedido'])){
	
	$dados[] = $POST['id'];
	
	$ControllerCliente = new ControllerCliente($banco,$dados);
	$ControllerCliente->excluirPedido();
	
	$banco->fechaConexao();
	exit;
}

$ControllerCliente = new ControllerCliente($banco,[$idCliente]);
$cliente = $ControllerCliente->pegaCliente();
$numeroDePedidosCliente = $ControllerCliente->numeroDePedidosCliente();
$pedidosDoCliente = $ControllerCliente->pedidosDoCliente(null);

$banco->fechaConexao();

?>