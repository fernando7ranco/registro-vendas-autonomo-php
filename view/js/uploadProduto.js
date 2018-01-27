$(function() {

    var reloadPagina = true;

    $(window).bind('beforeunload', function () {
        if (reloadPagina)
            return true;
    });

    var imagemProduto = 'img';

    $('#btAnexarImg').click(function () {
        $('[name=inputFile]').click();
    });

    $('form [name=inputFile]').change(function (e) {

        if ($(this).val().trim() !== '') {

            var files = e.target.files;

            var types = ['image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png'];

            if (types.indexOf(files[0].type) > -1) {

                var file = URL.createObjectURL(files[0]);

                var veSeAUrlEImagem = "<img src='" + file + "' >";

                $(veSeAUrlEImagem).bind('load',function () {

                    var w = $(this).get(0).naturalWidth;
                    var h = $(this).get(0).naturalHeight;

                    if (w >= 300 && h >= 300) {
                  
                        $('#localFileImg #localImg').html("<div id='caixaImg'>" + veSeAUrlEImagem + "</div>");
                        imagemProduto = files[0] ;

                    } else
                        alert('sua imagem não é grande o suficiente possui as seguintes dimenções ' + w + ' largura e ' + h + ' altura e as minimas necessarias são de 300px de largura e 300px de altura');
                });
            } else
                alert('arquivo invalido');
        }
  
    });

  
    function numeroParaReal(valor){
		
        valor = valor.replace(/\D/g, "");

        if(valor.length === 1)
            valor = '00'+valor;

        if(valor.length > 3 )
            valor = valor.replace(/^(0{1})(\d)/g,"$2");

        valor = valor.replace(/(\d+)(\d{2})/, "$1,$2");
        valor = valor.replace(/(\d+)(\d{3})(\,\d{2})/, "$1.$2$3");
        valor = valor.replace(/(\d+)(\d{3})(\.\d{3}\,\d{2})/, "$1.$2$3");
        valor = valor.replace(/(\d+)(\d{3})(\.\d{3}\.\d{3}\,\d{2})/, "$1.$2$3");

        return valor;

    };
	

    $('form input[name=valor]').keyup(function () {
        
        var valor = $(this).val();
        
        valor = numeroParaReal(valor);

        $(this).val(valor);
	
    });   
	
	$('form input[name=quantidade]').keyup(function () {
        
        var valor = $(this).val().replace(/\D/g, "");

        $(this).val(valor);
		
	
    });
	
	
    $('form').keydown(function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            return false;
        } 
    });
	
	function uploadProduto(dados) {

        var form = new FormData();
       
        for (var key in dados) {
            form.append(key, dados[key]);
        }
		
		form.append('uploadProduto', true);
		
		$('#caixaUploadProduto').show();
		
        $.ajax({
            url: '',
            data: form,
            method: 'POST',
			contentType: false,
			cache: false,
			processData: false,
            xhr: function () {
                var xhr = $.ajaxSettings.xhr();
                xhr.upload.onprogress = function (e) {
                    var porcent = (Math.floor(e.loaded / e.total * 100) + '%');
                    $('#caixaUploadProduto #centro #progress div').css('width', porcent);
                };
                return xhr;
            }, success: function (re) {
				reloadPagina = false;

				var html = "<center><h2>Produto foi enviado com sucesso!</h2>" +
						"<h3><a href='uploadproduto.php'>inserir produto</a></h3>" +
						"<h3><a href='uploadproduto.php?produto=" + re + "'>editar produto</a></h3></center>";
			
                $('#caixaUploadProduto #centro').html(html);
				
            }, beforeSend: function (jqXHR) {
                $('#btCancelarUploadProduto').click(function () {
                    jqXHR.abort();
                });
            }
        });
    };
	
	var regex = {
        valor: function (v) {
            var r = /^(\d\.?\,?){1,}$/;
            return r.test(v);
        }
    };

    $('#btUploadProduto').click(function () {
		
		var dados = {};
		
		dados['empresa'] = $('form select[name=empresas] option:selected').val();
		dados['codigo'] = $('form input[name=codigo]').val().trim();
		dados['imagem'] = imagemProduto;
		dados['nome'] = $('form input[name=nome]').val().trim();
		dados['descricao'] = $('form textarea[name=descricao]').val().trim();
        dados['valor'] = $('form input[name=valor]').val().trim();
        dados['quantidade'] = $('form input[name=quantidade]').val().trim();

		if(!dados.empresa)
			alert('selecione uma empresa');
		else if(!dados.codigo)
			alert('digite o codigo do produto');
		else if(!dados.imagem)
			alert('anexe uma imagem do produto');
		else if(!dados.nome)
			alert('digite o nome do produto');
		else if(!dados.valor)
			alert('digite o valor do produto');
		else if(!dados.valor)
			alert('digite a quantidade do produto');
		else
			uploadProduto(dados);
		
    });

});