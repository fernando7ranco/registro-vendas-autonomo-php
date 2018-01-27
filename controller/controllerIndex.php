<?php

include '../model/domain/categoriaSubcategoria.php';
include '../model/dao/categoriaSubcategoriaDAO.php';	

include '../model/domain/produto.php';
include '../model/dao/produtoDAO.php';

include '../model/domain/cliente.php';
include '../model/dao/clienteDAO.php';

include '../model/dataBase/bancoDedados.php';

include '../model/funcoes/todasFuncoes.php';

class ControllerIndex{
	
	private $banco;
	private $dados;
	
	public function __construct($banco,$dados = [null]){
		
		$this->banco = $banco;
		
		foreach($dados as $name => $value)
			$this->dados[] = $value;
		
	}
	
	public function pegaProdutos(){
	

		$condicao[] = " ORDER BY RAND() LIMIT 20";
	
		$produtoDAO = new ProdutoDAO($this->banco);
		
		$return = $produtoDAO->selecionaProdutos($condicao);
		
		return $return;
	}
	
	public function cadastroPreCliente(){
		
		$cliente = new Cliente($this->dados);
		$validacao = $cliente->validaDados();
		
		if($validacao){	

			$clienteDAO = new ClienteDAO($this->banco);
		
			if($clienteDAO->verificarCpf($cliente->getCpf()) > 0 or $clienteDAO->verificarPreCpf($cliente->getCpf()) > 0)
				return 'cpf';
			if($clienteDAO->verificarRg($cliente->getRg()) > 0 or $clienteDAO->verificarPreRg($cliente->getRg()) > 0)
				return 'rg';
			if($clienteDAO->verificarEmail($cliente->getEmail()) > 0 or $clienteDAO->verificarPreEmail($cliente->getEmail()) > 0)
				return 'email';
			
			return $clienteDAO->inserirPreCliente($cliente);
				
		}	
		
		return 'validacao';
	}
	
	public function cadastroCliente($codigo){
		
		$sql = 'SELECT id, nome, cpf, rg, cep, endereco, bairro, estado, telefone, email, senha FROM pre_clientes WHERE codigo = ?';
		$stmt = $this->banco->preparaStatement($sql);
		$stmt->bind_param('s',$codigo);
		$stmt->execute(); 
		$result = $stmt->get_result();
		
		if($result->num_rows == 1){

			$cliente = new Cliente($result->fetch_array());
			$clienteDAO = new ClienteDAO($this->banco);
			$id = $clienteDAO->inserirCliente($cliente);
			
			if($id > 0)
				$this->enviarEmailParaCadastro($id);
			
			return $id;
		}
		
		return 'erro';
		
	}
	
	public function enviarEmailParaCadastro($id){
		
		$sql = 'SELECT codigo,email FROM pre_clientes WHERE id = ?';
		$stmt = $this->banco->preparaStatement($sql);
		$stmt->bind_param('i',$id);
		$stmt->execute(); 
		$result = $stmt->get_result();
		
		if($result->num_rows == 1){
			$dados = $result->fetch_array();
			$codigo = $dados['codigo'];
			$email = $dados['email'];
		
			$para = $email;
			$assunto = 'confrimar cadastro';
			$mensagem = "<div style='border:1px solid #555;padding:10px';>
						<div align='center' style='border-bottom:1px solid #555;padding:10px 0px';>
							<img src='http://www.residencialexcellence.com.br/wp-content/uploads/2014/05/password_icon.png' > 
						</div>
						<a>{$codigo}</a>
						<button>confirmar cadastro</button>
					</div>";
					
			return enviarEmails($para, $assunto, $mensagem);
		}
		return 'erro';
	}
	
	public function limparPreClientes(){
		
		$datatime = date("Y-m-d H:i:s");
		
		$sql = "DELETE FROM `pre_clientes` WHERE tempo <='{$datatime}' ";
		$this->banco->executaQuery($sql);
		
	}
	
	public function loginDeCliente(){
		
		$dados[9] = $this->dados[0]; // = dados['email']
		$dados[10] = $this->dados[1]; // = dados['senha']
		
		$cliente = new Cliente($dados);
		$cliente->validaDados();
	
		if( $cliente->getEmail() and $cliente->getSenha() ){
			$clienteDAO = new ClienteDAO($this->banco);
			return $clienteDAO->autentificarCliente($cliente);
		}
	}
	
	public function manterLogado($id){
        $id = codificacao($id,10,1);
		setcookie("undefined",$id, time()+60*60*24*100, "/");
    }
} 

@$POST = $_POST;

if(isset($POST['efetuarCadastro'])){

	$dados[] = null;
	
	foreach($_POST['dados'] as $valor)
		$dados[] = $valor;
	
	$banco = new BancoDeDados;
	$controllerIndex = new ControllerIndex($banco);
	echo $controllerIndex->cadastroPreCliente($codigo);
	$banco->fechaConexao();
	exit;
}

if(isset($POST['reenviarEmail'])){
	
	$id = $POST['reenvio'];
	$banco = new BancoDeDados;
	$controllerIndex = new ControllerIndex($banco);
	echo $controllerIndex->enviarEmailParaCadastro($id);
	$banco->fechaConexao();
	exit;
}

if(isset($_GET['confirmarcontar'])){
	
	$codigo = $_GET['confirmarcontar'];
	$banco = new BancoDeDados;
	$controllerIndex = new ControllerIndex($banco);
	echo $controllerIndex->cadastroCliente($codigo);
	$banco->fechaConexao();
	exit;
}

if (isset($POST['login']) ) {

    $dados[] = $POST['email'];
    $dados[] = $POST['senha'];

    $banco = new BancoDedados;
	$controllerIndex = new ControllerIndex($banco,$dados);
	$retorno = $controllerIndex->loginDeCliente();
    $banco->fechaConexao();

    if (!$retorno)
        exit($retorno);
    else {
      
        $_SESSION['info-cliente'] = $retorno;
        
        if(isset($dados['manterLogado']))
            $controllerIndex->manterLogado($retorno);
        
        exit('logado');
    }
}

$banco = new BancoDeDados;
$controllerIndex = new ControllerIndex($banco);
$produtos = $controllerIndex->pegaProdutos();
$controllerIndex->limparPreClientes();
$banco->fechaConexao();

?>