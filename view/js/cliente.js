$(function () {
	
	function lightbox(conteudo) {
        var html = "<div id='lightbox'>" +
                "<div id='conteudoLightbox'>" +
					conteudo +
                "</div>" +
            "</div>";
        $('body').append(html);
    };
	
	$('body').delegate('#lightbox #conteudoLightbox #cancelarAcao', 'click', function (e) {
        $('#lightbox').remove();
    });
   
   $("#addNovoPedido").click(function(){
	
	  var html ="<div id='boxNovoPedido'>"+
				"<h3 align='center'>Inserir Novo Pedido</h3>"+
				"<div id='buscar-por-prdutos'><label>add produtos :</label>"+
					"<input type='text' name='nome-produto' placeholder='Procurar Produtos'>"+
					"<div id='lista-protudos'></div>"+
				"</div>"+
				"<div id='lista-pedidos'></div>"+
				"<p><label>valor total * R$:</label>"+
					"<input type='text' name='valor-total' placeholder='valor total'>"+
				"</p>"+
				"<p><label>Anotação:</label>"+
					"<textarea name='anotacao' maxlength='400' placeholder='anotação' ></textarea>"+
				"</p>"+
				"<p><label>data do pagamento *:</label>"+
					"<input type='date' name='data-pagamento' placeholder='data pagamento'>"+
				"</p>"+
				"<p><label>status do pagamento *:</label>"+
					" Aberto <input type='radio'  value='1' name='status' checked>"+
					" Concluido <input type='radio'  value='2' name='status' >"+
				"</p>"+
				"<p>"+
					"<button id='cancelarAcao'>cancelar</button><button id='salvarPedido'>salvar</button>"+
				"</p>"+
			"</div>";
		lightbox(html);
   });
   
	
	var varTimeOutGetprocurarProdutoAddPedidos;
	
	function functionTimeOutGetprocurarProdutoAddPedidos(nome){
		
		varTimeOutGetprocurarProdutoAddPedidos = setTimeout(function(){
			if(nome){
				$.ajax({
					url:'',
					method:'post',
					data:{procurarProdutoAddPedidos: true, nome: nome}
				}).done(function(re){
					re = re.trim() ? re : '<p align="center">nenhum produto encontrado</p>';
				
					$('#lista-protudos').html(re).show();
				});
				
			}else{
				$('#lista-protudos').html('').hide();
			}
		},555);
	}
	
	$('body').delegate('#boxNovoPedido input[name=nome-produto]','focus',function(){
		
		if($('#lista-protudos').html())
			$('#lista-protudos').show();
		
	}).delegate('#boxNovoPedido input[name=nome-produto]','keyup',function(){
		
		var nome = $(this).val().trim();
		
		clearInterval(varTimeOutGetprocurarProdutoAddPedidos);
		
		functionTimeOutGetprocurarProdutoAddPedidos(nome);
		
	}).delegate('#boxNovoPedido input[name=nome-produto], #boxNovoPedido #lista-protudos','click',function(e){	
		e.stopPropagation();
		
	}).delegate("#lightbox",'click',function(){
		$("#lista-protudos").hide();
		
	}).delegate('#boxNovoPedido #lista-protudos div','click',function(){
		
		var id = $(this).attr('value');
		
		if($("#boxNovoPedido #lista-pedidos div[value='"+id+"']").length){
			alert('produto já inserido no pedido');
			return false;
		}
			
		var produto = $(this).clone();
	
		var qtd = $(this).find('span a').text();
		
		produto.find('span').html("<input type='text' name='qtd-item-pedido' value='1' max='"+qtd+"'><button>remover</button>");
		
		$("#boxNovoPedido #lista-pedidos").prepend(produto);
		
		eachValorTotalPedido();
	
	}).delegate('#boxNovoPedido #lista-pedidos div input','keyup',function(){
		
		var valor = $(this).val().replace(/\D/g, "");
		valor = parseInt(valor);
		var max = $(this).attr('max');
		max = parseInt(max);
		
		if(valor >= 1 && valor <= max)
			$(this).val(valor);
		else
			$(this).val(1);
		
		eachValorTotalPedido();
	}).delegate('#boxNovoPedido #lista-pedidos div button','click',function(){
		
		$(this).parents('div[value]').remove();
		eachValorTotalPedido();
		
	}).delegate('#boxNovoPedido input[name="valor-total"]','keyup',function(){
		
		var valor = $(this).val();
        
        valor = numeroParaReal(valor);

        $(this).val(valor);
		
		
	}).delegate('#boxNovoPedido p #salvarPedido','click',function(){
		var dados = {};
		
		dados['inseriPedido'] = true;
		dados['itens'] = [];
		
		$('#boxNovoPedido #lista-pedidos div').each(function(){
			
			var id = $(this).attr('value');
			var valor = $(this).find('a').attr('value');
			var qtd = $(this).find('input').val();
			
			dados.itens.push([id,valor,qtd]);
		});
		
		
		dados['valorTotal'] = $('#boxNovoPedido input[name="valor-total"]').val();
		dados['anotacao'] = $('#boxNovoPedido textarea[name="anotacao"]').val();
		dados['dataPagamento'] = $('#boxNovoPedido input[name="data-pagamento"]').val();
		dados['status'] = $('#boxNovoPedido input[name="status"]:checked').val();
		
		if(dados.itens.length && dados.valorTotal && dados.dataPagamento){
			$.ajax({
				url:'',
				method:'post',
				data:dados
			}).done(function(re){
				
				if(re.trim() != 0){
					$('#lightbox').remove();
					$("#local-lista-pedidos").prepend(re);
				}else
					alert('desculpe ocorreu algum erro tente novamente');
			});
		}else{
			alert('adicone itens e preencha os campos que possuem *');
		}
	});
	
	function eachValorTotalPedido(){
		var soma = 0;
		$('#boxNovoPedido #lista-pedidos div').each(function(){
			
			var valor = $(this).find('a').attr('value').replace(/\D/g, "");
			valor = parseInt(valor);
			
			var qtd = $(this).find('input').val();
			qtd = parseInt(qtd);
			
			soma += (valor * qtd);
		});
	
		soma = numeroParaReal(soma.toString())
		$('#boxNovoPedido input[name="valor-total"]').val(soma);
	}
	
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
	
	
	$('body').delegate("#cliente p #excluirCliente","click",function(){
	
		var html ="<div id='boxExcluirCliente' align='center'>"+
				"<h3>Tem certeza que deseja excluir este cliente</h3>"+
				"<p>"+
					"<button id='cancelarAcao'>cancelar</button><button id='confirmaExcluirCliente'>excluir</button>"+
				"</p>"+
			"</div>";
			
		lightbox(html);
		
	}).delegate("#boxExcluirCliente #confirmaExcluirCliente","click",function(){
		$.ajax({
			url:'',
			method:'POST',
			data:{excluirCliente: true}
		});
	});
	
	$('body').delegate("#local-lista-pedidos #pedido span button","click",function(){
		
		var id = $(this).parents('div[value]').attr('value');
		
		var span = $(this).parent('span');
		
		span.html("status pago");
		
		span.siblings("#atrasado").removeAttr('id');
		
		$.ajax({
			url:'',
			method:'POST',
			data:{mudarStatusPedido: true, id: id}
		});
		
	}).delegate("#local-lista-pedidos #pedido #excluir","click",function(){
		
		var pai = $(this).parent('div[value]');
		var id = pai.attr('value');
	
		pai.remove();
		
		$.ajax({
			url:'',
			method:'POST',
			data:{excluirPedido: true, id: id}
		});
	});
   
});