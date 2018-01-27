<?php

include '../controller/controllerCliente.php';

?>
<!doctype html>
<html lang='pt-br'>
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8"/>
		<title>cliente <?=$cliente->getNome();?></title>
        <link rel="shortcut icon" type="img/x-icon" href="img/icones/icon.png">
        <link rel='stylesheet' type="text/css" href='css/all.css'>
        <link rel='stylesheet' type="text/css" href='css/cliente.css'>
    </head>
    <body>
	
	    <?php include 'includes/header.php' ?>
	   
        <div class='caixa-centro'id='caixaCentro'>
	
            <div id='cliente' >
					<h3>Identificação</h3>
					<div>
						<label>NOME:</label>
						<?=$cliente->getNome();?>
					</div>
					<div>
						<label>CPF:</label>
						<?=$cliente->getCpf();?>
					</div>
					
					<h3>Endereço</h3>
					<div>
						<label>CEP:</label>
						<?=$cliente->getCep();?>
					</div>
					<div>
						<label>LOGRADOURO:</label>
						<?=$cliente->getLogradouro();?>
					</div>
					<div>
						<label>BAIRRO:</label>
						<?=$cliente->getBairro();?>
					</div>
					
					<h3>Contato</h3>
					<div>
						<label>TELEFONE:</label>
						<?=$cliente->getTelefone();?>
					</div>
					
					<p>
						<button id='addNovoPedido'>novo pedido</button> 
						<a href='cadastrocliente.php?cliente=<?=$cliente->getId();?>' target='_blank'><button>editar cliente</button></a>
						<button id='excluirCliente'>excluir cliente</button>
					</p>
			</div>
			
			<table>
				<tr>
					<th>n° todal de pedidos</th>
					<th>n° de pedidos abertos</th>
					<th>n° de pedidos com o pagamento atrasado</th>
				</tr>
				<?php
				
				$numPedidos = $numeroDePedidosCliente[0];
				$numPagamentoAberto = $numeroDePedidosCliente[1];
				$numPagamentoAtrasado = $numeroDePedidosCliente[2];
				
				echo "<tr>
						<th>{$numPedidos}</th>
						<th>{$numPagamentoAberto}</th>
						<th>{$numPagamentoAtrasado}</th>
					</tr>";
				
				?>
			</table>
			
			<h3>Informações das Datas</h3>
			<table id='infoCoresDatas'>
				<tr>
					<th>atrasado</th>
					<th>hoje</th>
					<th>amanhã</th>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
			
			<h3>Lista de Pedidos</h3>
			<div id='local-lista-pedidos'>
				<?=$pedidosDoCliente?>
			</div>
			
        </div>
    </body>
	<script type="text/javascript" src="js/jquery_code.js"></script>
    <script type="text/javascript" src="js/cliente.js"></script>
</html>