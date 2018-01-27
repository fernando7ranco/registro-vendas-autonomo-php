<?php

class ClienteDAO {

    private $banco;

    public function __construct($bd) {
        $this->banco = $bd;
    }

	public function inserirCliente($cliente) {

        $nome = $cliente->getNome();
        $cpf = $cliente->getCpf();
        $cep = $cliente->getCep();
        $logradouro = $cliente->getLogradouro();
        $bairro = $cliente->getBairro();
        $telefone = $cliente->getTelefone();
		
		$sql = 'INSERT INTO `clientes`(nome, cpf, cep, logradouro, bairro, telefone) VALUES (?,?,?,?,?,?)';
		$stmt = $this->banco->preparaStatement($sql);
		$stmt->bind_param('ssssss', $nome, $cpf, $cep, $logradouro, $bairro, $telefone);
		$stmt->execute(); 
		
		
		return $stmt->affected_rows;

    }
	
	public function verificarCpf($cpf){
		
		$sql = 'SELECT id FROM clientes WHERE cpf = ?';
		$stmt = $this->banco->preparaStatement($sql);
		$stmt->bind_param('s', $cpf);
		$stmt->execute();
		$result = $stmt->get_result();
		
		return $result->num_rows;
	}
	

	public function selecionaClienteId($id){
		
		$sql = 'SELECT * FROM clientes WHERE id = ?';
		$stmt = $this->banco->preparaStatement($sql);
		$stmt->bind_param('i',$id);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$cliente = null;
		
		if($result->num_rows > 0)
			$cliente = new Cliente($result->fetch_array());
		else
			$cliente = new Cliente([]);
		
		return $cliente;
	}
	
	public function selecionaTodosClientes($nome = ''){
		
		$sql = "SELECT id,nome,cpf FROM clientes WHERE nome LIKE CONCAT('',?,'%')";
		$stmt = $this->banco->preparaStatement($sql);
		$stmt->bind_param('s',$nome);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$clientes = null;
		$pedidoDAO = new PedidoDAO($this->banco);
		
		while($linhas = $result->fetch_array()){
			
			$numerosPedido = $pedidoDAO->numeroDePedidosCliente($linhas['id']);
			
			$numPedidos = $numerosPedido[0];
			$numPagamentoAberto = $numerosPedido[1];
			$numPagamentoAtrasado = $numerosPedido[2];
			
			$dataPagamentoId = "";
			
			$amanha = date('Y-m-d',strtotime(date('Y-m-d'). '+ 1 days'));
			
			$query = $this->banco->executaQuery("SELECT data_pagamento FROM pedidos WHERE id_cliente = {$linhas['id']} AND status = 1 AND data_pagamento <= '{$amanha}' ");
			
			if($query->num_rows){
				
				$linhas2 = $query->fetch_array();
				
				if($linhas2['data_pagamento'] < date("Y-m-d"))
					$dataPagamentoId = "id='atrasado'";
				else if($linhas2['data_pagamento'] == date("Y-m-d"))
					$dataPagamentoId = "id='hoje'";
				else if($linhas2['data_pagamento'] == $amanha)
					$dataPagamentoId = "id='amanha'";	
				
			}
			
			$clientes.= "<tr {$dataPagamentoId} value='{$linhas['id']}' >
							<td id='nome'>{$linhas['nome']}</td>
							<td>{$linhas['cpf'] }</td>
							<td>{$numPedidos}</td>
							<td>{$numPagamentoAberto}</td>
							<td>{$numPagamentoAtrasado}</td>
						</tr>";
			
		}
		return $clientes;
	}
	
	 public function alterarCliente($cliente) {
		
        $idCliente = $cliente->getId();
        $nome = $cliente->getNome();
        $cpf = $cliente->getCpf();
        $cep = $cliente->getCep();
        $logradouro = $cliente->getLogradouro();
        $bairro = $cliente->getBairro();
        $telefone = $cliente->getTelefone();
		
		$stmt = $this->banco->preparaStatement('UPDATE clientes SET nome = ? WHERE id = ? AND nome != ?');
        $stmt->bind_param('sis', $nome, $idCliente, $nome);
        $stmt->execute(); 
		
		$stmt = $this->banco->preparaStatement('UPDATE clientes SET cpf = ? WHERE id = ? AND cpf != ?');
        $stmt->bind_param('sis', $cpf, $idCliente, $cpf);
        $stmt->execute();

        $stmt = $this->banco->preparaStatement('UPDATE clientes SET cep = ? WHERE id = ? AND cep != ?');
        $stmt->bind_param('sis', $cep, $idCliente, $cep);
        $stmt->execute();
		
		$stmt = $this->banco->preparaStatement('UPDATE clientes SET logradouro = ? WHERE id = ? AND logradouro != ?');
        $stmt->bind_param('sis', $logradouro, $idCliente, $logradouro);
        $stmt->execute();

        $stmt = $this->banco->preparaStatement('UPDATE clientes SET bairro = ? WHERE id = ? AND bairro != ?');
        $stmt->bind_param('sis', $bairro, $idCliente, $bairro);
        $stmt->execute();

		$stmt = $this->banco->preparaStatement('UPDATE clientes SET telefone = ? WHERE id = ? AND telefone != ?');
        $stmt->bind_param('sis', $telefone, $idCliente, $telefone);
        $stmt->execute();
    } 
	
	public function excluirCliente($id){
		
		$sql = "DELETE FROM `clientes` WHERE id = ?";
		$stmt = $this->banco->preparaStatement($sql);
		$stmt->bind_param('i', $id);
		$stmt->execute(); 
	}
}

?>