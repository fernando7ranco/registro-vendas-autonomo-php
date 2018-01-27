<?php

include '../controller/controllerUploadProduto.php';

?>
<!doctype html>
<html lang='pt-br'>
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" >
        <title>upload produto</title>
        <link type="img/x-icon" rel="shortcut icon" href="img/icones/icon.png">
        <link type="text/css" rel='stylesheet' href='css/all.css'>
        <link type="text/css" rel='stylesheet' href='css/uploadProduto.css'>
    </head>
    <body>
     
		<?php include 'includes/header.php' ?>
	   
        <div class='caixa-centro' id='localUploadProduto'>
		
            <h2>Informações do produto</h2>
            <h5>As informações marcadas com asterisco (*) são obrigatórias</h5>

            <form>
                
				<p>
                    <label>Selecione a Empresa do Produto *</label><br>
					<?=$produto->getEmpresa('select');?>
                </p>
				
				<p>
                    <label>Adicione o Codigo *</label><br>
                    <input type='text' name='codigo' value='<?=$produto->getCodigo()?>' placeholder='codigo do produto' maxlength='50'>
                </p>
				
                <p>
                    <label>Anexe uma imagem *</label><img src='img/icones/anexo.png' id='btAnexarImg'>

					<div id='localFileImg'>
						<input type='file' name='inputFile' accept="image/jpg,image/jpeg,image/pjpeg,image/png" />
						<div id='localImg'>
							<?php
								if($produto->getImagem())
									echo "<div id='caixaImg'><img src='img/produtos/{$produto->getImagem()}' ></div>";
							?>
						</div>
					</div>

                </p>
               
                <p>
                    <label>Adicione um nome *</label><br>
                    <font color='#e01847'>campo nome, no minimo 1 caracter e no maximo 100 caracteres</font><br>
                    <input type='text' name='nome' value='<?=$produto->getNome()?>' placeholder='nome do produto' maxlength='100'>
                </p>

                <p>
                    <label>Adicione uma descrição</label><br>
                    <font color='#e01847'>campo descrição, no maximo 300 caracteres</font><br>
                    <textarea name='descricao' placeholder='descrição do produto' maxlength='300'><?=$produto->getDescricao()?></textarea>
                </p>

                <p>
                    <label>Valor (R$) *</label><br>
                    <font color='#e01847'>campo valor, somente numeros de 0-9 e pontos e virgular</font><br>
                    <input type='text' name='valor' value='<?=$produto->getValor()?>' placeholder='R$ 0.00'/>
                </p>  
		
				<p>
                    <label>Quantidade em estoque *</label><br>
                    <font color='#e01847'>campo quantidade, somente numeros de 0-9</font><br>
                    <input type='text' name='quantidade' value='<?=$produto->getQuantidade()?>' placeholder='0'/>
                </p>  
		
		
				<button type='button' id='btUploadProduto' >enviar</button>

            </form>

            <div id='caixaUploadProduto'>
                <div id='centro'>
                    <button type='button' id='btCancelarUploadProduto' >cancelar envio</button>
                    <div id='progress'>
                        <div></div>
                        <span></span>
                    </div>
                </div>
            </div>

        </div>

    </body>
    <script type="text/javascript" src="js/jquery_code.js"></script>
    <script type="text/javascript" src="js/uploadProduto.js"></script>
</html>
