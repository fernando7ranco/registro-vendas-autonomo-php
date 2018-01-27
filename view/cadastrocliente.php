<?php

include '../controller/controllerCadastroCliente.php';

?>
<!doctype html>
<html lang='pt-br'>
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8"/>
		<title>cadastro de cliente</title>
        <link rel="shortcut icon" type="img/x-icon" href="img/icones/icon.png">
        <link rel='stylesheet' type="text/css" href='css/all.css'>
        <link rel='stylesheet' type="text/css" href='css/cadastro-cliente.css'>
    </head>
    <body>
	   
        <?php include 'includes/header.php' ?>
	   
        <div class='caixa-centro' id='caixaCentro'>
			
            <div id='editarcadastro' >
				<?php
					if($cliente->getNome())
						echo "<h2>Editar informações de cadastro</h2>";
					else
						echo "<h2>Cadastrar novo cliente</h2>";
				?>
				<h4><font color='red'>* Dados Obrigatórios</font></h4>
				
				<form>
					<h3>Identificação</h3>
					<div>
						<label>NOME: *</label>
						<input type='text' name='nome' value='<?=$cliente->getNome();?>' placeholder='DIGITE NOME' maxlength='30' autocomplete='off' >
					</div>
					<div>
						<label>CPF: *</label>
						<input type='text' name='cpf' value='<?=$cliente->getCpf();?>' placeholder='DIGITE CPF' maxlength='14' autocomplete='off' >
					</div>
					
					<h3>Endereço</h3>
					<div>
						<label>CEP: pressione ENTER para completar o enderço</label>
						<input type='text' name='cep' value='<?=$cliente->getCep();?>' placeholder='DIGITE CEP' maxlength='16' autocomplete='off' >
					</div>
					<div>
						<label>LOGRADOURO:</label>
						<input type='text' name='logradouro' value='<?=$cliente->getLogradouro();?>' placeholder='DIGITE (rua, número, complemento, apartamento)' maxlength='80' autocomplete='off' >
					</div>
					<div>
						<label>BAIRRO:</label>
						<input type='text' name='bairro' value='<?=$cliente->getBairro();?>' placeholder='DIGITE BAIRRO' maxlength='50' autocomplete='off' >
					</div>
					<h3>Contato</h3>
					<div>
						<label>TELEFONE: DDD + digitos do telefone</label>
						
						<input type='text' name='telefone' value='<?=$cliente->getTelefone();?>' placeholder='DIGITE TELEFONE (DDD) 0000-0000'  maxlength='15' autocomplete='off' >
						<br><font color='red'></font>
					</div>
					
					<p>
						<button type='button' id='bt-cadastro'>enviar</button>
					</p>
				</form>
			</div>
        </div>
    </body>
    <script type="text/javascript" src="js/jquery_code.js"></script>
    <script type="text/javascript" src="js/cadastro-cliente.js"></script>
</html>