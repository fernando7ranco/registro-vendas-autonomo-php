<?php


include '../model/domain/cliente.php';
include '../model/dao/clienteDAO.php';
include '../model/dao/pedidoDAO.php';

include '../model/dataBase/bancoDedados.php';


class ControllerClientes{
	
	private $banco;
	private $dados;
	
	public function __construct($banco, $dados = [null]){
		
		$this->banco = $banco;
		
		foreach($dados as $name => $value)
			$this->dados[] = $value;
		
	}
	
	public function pegaClientes(){
		
		$clienteDAO = new ClienteDAO($this->banco);
		return $clienteDAO->selecionaTodosClientes($this->dados[0]);
	}

} 

@$POST = $_POST;


if(isset($POST['getClientes'])){
	
	$banco = new BancoDeDados;
	
	$nome = $POST['nome'];
	
	$ControllerClientes = new ControllerClientes($banco,[$nome]);
	echo $ControllerClientes->pegaClientes();
	$banco->fechaConexao();
	exit;
}

?>