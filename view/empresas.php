<?php

include "../controller/controllerEmpresas.php";
?>
<!doctype html>
<html lang='pt-br'>
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" >
        <title>empresas</title>
        <link type="img/x-icon" rel="shortcut icon" href="img/icones/icon.png">
        <link type="text/css" rel='stylesheet' href='css/all.css'>
        <link type="text/css" rel='stylesheet' href='css/empresas.css'>
    </head>
    <body>
	
		<?php include 'includes/header.php' ?>
	
        <div class='caixa-centro' id='localEmpresas'>
			
			<p>Adicionar Empresas</p>
			<div id='localAddEmpresa'>
				<input type='text' placeholder='nome da empresa'>
				<button type='button' >inserir</button>
			</div>
			
			<p>Buscar Por Empresas</p>
			<div id='buscarPorEmpresas'>
				<input type='text' placeholder='nome da empresa'>
				<button type='button' >buscar</button>
			</div>
			
			
			<p>Empresas</p>
			
			<ul id='lista-de-empresas'>
			  <?= !($listaEmpresas) ? "<p>não possui nenhuma empresa cadastrada</p>" : $listaEmpresas;?>
			</ul>
			
		</div>
    </body>
    <script type="text/javascript" src="js/jquery_code.js"></script>
    <script type="text/javascript" src="js/empresas.js" ></script>
</html>
