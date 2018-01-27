<?php

include "../controller/controllerProdutos.php";

?>
<!doctype html>
<html lang='pt-br'>
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" >
        <title>produtos</title>
        <link type="img/x-icon" rel="shortcut icon" href="img/icones/icon.png">
        <link type="text/css" rel='stylesheet' href='css/all.css'>
        <link type="text/css" rel='stylesheet' href='css/produtos.css'>
    </head>
    <body>
	
		 <?php include 'includes/header.php' ?>
		 
        <div class='caixa-centro' id='localProdutos'>
			
			<p>Adicionar Produto</p>
			<a href='uploadproduto.php'><button type='button' >inserir novo produto</button></a>
			
			<p>Buscar Por Produtos</p>
			
			<form id='buscarPorProdutos' method='GET'>
				<?php
					$produto->setEmpresa($dados[0]);
					echo $produto->getEmpresa('select');
				?>
				<input type='text' value='<?=$dados[1];?>' name='nome' placeholder='nome do produto'>
				<input type='hidden' value='<?=$dados[2];?>' name='limite'>
				<button>buscar</button>
				<button type='reset' >limpar</button>
			</form>
			
			
			<p>Produtos</p>
			
			<ul id='lista-de-produtos'>
			  <?= !($produtos['produtos']) ? "<p>não possui nenhuma empresa cadastrada</p>" : $produtos['produtos'];?>
			  <?=paginalizacao($produtos['produtos'],$dados[2]);?>
			</ul>
			
		</div>
    </body>
    <script type="text/javascript" src="js/jquery_code.js"></script>
    <script type="text/javascript" src="js/produtos.js" ></script>
</html>
