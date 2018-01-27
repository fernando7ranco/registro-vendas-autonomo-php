<?php

class PedidoDAO {

    private $banco;

    public function __construct($bd) {
        $this->banco = $bd;
    }


	public function inseriPedido($pedido) {

		$idCliente = $pedido->getCliente();
		$valorTotal = $pedido->getValorTotal(false);
		$anotacao = $pedido->getAnotacao();
		$dataPagamento = $pedido->getDataPagamento();
		$status = $pedido->getStatus();
		$itens = $pedido->getItens();
		
		$sql = 'INSERT INTO `pedidos`(id_cliente, valor_total, anotacao, data_inicio, data_pagamento, status) VALUES (?, ?, ?, NOW(), ?, ? )';
		$stmt = $this->banco->preparaStatement($sql);
		$stmt->bind_param('idssi', $idCliente, $valorTotal, $anotacao, $dataPagamento, $status);
		$stmt->execute(); 
		
		if($stmt->affected_rows){
			
			$id = $stmt->insert_id;
		
			foreach($itens as $item){
				$sql = 'INSERT INTO `itens_pedido`(id_pedido, id_produto, valor, quantidade) VALUES (?, ?, ?, ? )';
				$stmt = $this->banco->preparaStatement($sql);
				$stmt->bind_param('iidi', $id, $item[0], $item[1], $item[2]);
				$stmt->execute(); 
				
				$sql = "UPDATE produtos SET quantidade = quantidade - ? WHERE id = ?";
				$stmt = $this->banco->preparaStatement($sql);
				$stmt->bind_param('ii', $item[2], $item[0]);
				$stmt->execute(); 
			}
			
			return $id;
		}
		
		return 0;
	
    }
	
	public function numeroDePedidosCliente($idCliente){
		
		$numPedidos = $this->banco->executaQuery("SELECT id FROM pedidos WHERE id_cliente = {$idCliente}")->num_rows;
			
		$numPagamentoAberto = $numPedidos ? $this->banco->executaQuery("SELECT id FROM pedidos WHERE id_cliente = {$idCliente} AND status = 1")->num_rows : 0;
		
		$numPagamentoAtrasado = $numPagamentoAberto ? $this->banco->executaQuery("SELECT id FROM pedidos WHERE id_cliente = {$idCliente} AND status = 1 AND data_pagamento < ". date('Y-m-d'))->num_rows : 0;
		
		return [$numPedidos, $numPagamentoAberto, $numPagamentoAtrasado];
	}
	
	public function pedidosDoCliente($idCliente, $idPedido = null){
		
		if($idPedido){
			$sql = "SELECT * FROM pedidos WHERE id = ?";
			$id = $idPedido;
		}else{
			$sql = "SELECT * FROM pedidos WHERE id_cliente = ? ORDER BY id DESC";
			$id = $idCliente;
		}
		
		$stmt = $this->banco->preparaStatement($sql);
		$stmt->bind_param('i', $id);
		$stmt->execute(); 
		$result = $stmt->get_result();
		
		$return = null;
		
		while($linhas = $result->fetch_array()){
			
			$valorTotal = numeroDecimal($linhas['valor_total']);
			$dataInicio = dataExtensa($linhas['data_inicio']);
			$dataPagamento = dataExtensa($linhas['data_pagamento']);
			$anotacao = texto($linhas['anotacao']);
			
			$dataPagamentoId = "";	
			
			if($linhas['status'] == 1 ){
				
				if($linhas['data_pagamento'] < date("Y-m-d"))
					$dataPagamentoId = "id='atrasado'";
				else if($linhas['data_pagamento'] == date("Y-m-d"))
					$dataPagamentoId = "id='hoje'";
				else if(date('Y-m-d',strtotime($linhas['data_pagamento']. '- 1 days')) == date("Y-m-d"))
					$dataPagamentoId = "id='amanha'";	
			}
			
			$status = $linhas['status'] == 1 ?  'aberto' : 'pago';
			
			$pagar = $linhas['status'] == 1 ? '<button>Concluir Pagamento</button>' : '';
			
			$return.= "<div id='pedido' value='{$linhas['id']}'>
						<a id='excluir'>X</a>
						<span>valor total R$ {$valorTotal}</span>
						<span>data do pedido {$dataInicio}</span>
						<span>status {$status}<br>{$pagar}</span>
						<span {$dataPagamentoId} >data do pagamento {$dataPagamento}</span>
						<p>{$anotacao}</p>";
						
			$sql = "SELECT I.*, P.imagem, P.nome, E.nome AS empresa FROM itens_pedido I INNER JOIN produtos P  INNER JOIN empresas E ON I.id_produto = P.id AND P.id_empresa = E.id
					WHERE id_pedido = {$linhas['id']} ORDER BY id DESC";
			$query = $this->banco->executaQuery($sql);
			
			while($linhas2 = $query->fetch_array()){
				$valor = numeroDecimal($linhas2['valor']);
				$return.= "<div id='itens'>
							<img src='img/produtos/{$linhas2['imagem']}'>
							{$linhas2['empresa']} - {$linhas2['nome']}
							R$:{$valor}
							quantidade pedida:{$linhas2['quantidade']}
						</div>";
			}
			
			$return .= "</div>";
		}
		
		return $return;
	}
	
	public function mudarStatus($id){
		
		$sql = "UPDATE pedidos SET status = 2 WHERE id = ?";
		$stmt = $this->banco->preparaStatement($sql);
		$stmt->bind_param('i', $id);
		$stmt->execute(); 
	}	
	
	public function excluirPedido($id){
		
		$sql = "DELETE FROM `pedidos` WHERE id = ?";
		$stmt = $this->banco->preparaStatement($sql);
		$stmt->bind_param('i', $id);
		$stmt->execute(); 
	}
}

?>