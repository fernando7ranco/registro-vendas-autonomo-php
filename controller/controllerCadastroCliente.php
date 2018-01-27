<?php


include '../model/domain/cliente.php';
include '../model/dao/clienteDAO.php';

include '../model/dataBase/bancoDedados.php';


class ControllerCadastroCliente{
	
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
	
	public function acaoCadastro(){
		
		$cliente = new Cliente($this->dados[1]);
		
		$euCliente = $this->pegaCliente();
		
		$clienteDAO = new ClienteDAO($this->banco);
	
		if($euCliente->getCpf() != $cliente->getCpf() and $clienteDAO->verificarCpf($cliente->getCpf()) > 0 )
			return 'cpf';
		
		if($euCliente->getCpf())
			return $clienteDAO->alterarCliente($cliente);
		else
			return $clienteDAO->inserirCliente($cliente);
	
	}
	

} 

@$POST = $_POST;
@$GET = $_GET;

$idCliente = isset($GET['cliente']) ? $GET['cliente'] : 0;

$banco = new BancoDeDados;

if(isset($POST['acaoCadastro'])){

	$dados[] = $idCliente;
	
	foreach($_POST['dados'] as $valor)
		$dados[] = $valor;
		
	$controllerCadastroCliente = new controllerCadastroCliente($banco,[$idCliente, $dados]);
	echo $controllerCadastroCliente->acaoCadastro();
	$banco->fechaConexao();
	exit;
}

$controllerCadastroCliente = new ControllerCadastroCliente($banco,[$idCliente]);
$cliente = $controllerCadastroCliente->pegaCliente();

$banco->fechaConexao();

?>