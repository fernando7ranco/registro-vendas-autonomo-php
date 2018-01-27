<?php

include '../controller/controllerClientes.php';

?>
<!doctype html>
<html lang='pt-br'>
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8"/>
		<title>clientes</title>
        <link rel="shortcut icon" type="img/x-icon" href="img/icones/icon.png">
        <link rel='stylesheet' type="text/css" href='css/all.css'>
        <link rel='stylesheet' type="text/css" href='css/clientes.css'>
    </head>
    <body>
	
	   <?php include 'includes/header.php' ?>
	   
        <div class='caixa-centro' id='caixaCentro'>
		
			<p>Adicionar Cliente</p>
			<a href='cadastrocliente.php'><button type='button' >inserir novo cliente</button></a>
			
			<p>Buscar por Cliente</p>
			<input type='text' name='buscar-por-nome' placeholder='nome do cliente'>
			
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
			
			<h3>Clientes</h3>
            <div id='lista-clientes' >
				<table>
					<thead>
					<tr>
						<th>nome</th>
						<th>cpf</th>
						<th>n° de pedidos</th>
						<th>n° de pedidos abertos</th>
						<th>n° de pedidos com o pagamento atrasado</th>
					</tr>
					</thead>
					<tbody align='center'></tbody>
				</table>
			</div>
	
        </div>
    </body>
    <script type="text/javascript" src="js/jquery_code.js"></script>
    <script type="text/javascript" src="js/clientes.js"></script>
</html>