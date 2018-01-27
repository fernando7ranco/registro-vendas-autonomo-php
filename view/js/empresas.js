$(function(){
	
	function lightbox(conteudo) {
        var html = "<div id='lightbox'>" +
                "<div id='conteudoLightbox'>" +
					conteudo +
                "</div>" +
            "</div>";
        $('body').append(html);
    };
	
	function htmlLoading(caminho){
		$(caminho).html("<p id='loading'>carregando <img src='img/icones/load.gif'></p>");
	};
	
	$('body').delegate('#lightbox #conteudoLightbox #cancelarAcao', 'click', function (e) {
        $('#lightbox').remove();
    });
	
	var empresaObj = function(acao, id, nome){
		return {
			acao: acao,
			id: id,
			nome: nome,
		}
	};
	
	$("#localAddEmpresa button").click(function(){
		
		var input = $(this).parent('div').find('input');
		
		var nome = input.val().trim();
		
		if(nome){
			
			var dados = empresaObj('adicionarEmpresa', null, nome);
			var atualiza = buscarPorEmpresas;
			
			$.ajax({
				url:'',
				method:'POST',
				data: dados
			}).done(function(){
				input.val('');
				atualiza();
			});
		
		}else{
			alert('preencha o nome');
			input.focus();
		}
	});
	
	
	
	function buscarPorEmpresas(){
	
		var dados = empresaObj('burarPorEmpresas', null, $('#buscarPorEmpresas input').val().trim() );
		
		htmlLoading('#localEmpresas ul');
		
		$.ajax({
			url:'',
			method:'POST',
			data: dados
		}).done(function(re){
	
			var html = re.trim();
			
			if(!html) html = "<p> não foi encontrado nenhuma correspondecia para: "+dados.nome+"</p>";
			
			$('#localEmpresas ul').html(html);
		});
	};
	
	$("#buscarPorEmpresas button").click(buscarPorEmpresas);
	
	$('#lista-de-empresas').delegate('li #btEditar','click',function(){
		
		var id = $(this).parent('li').attr('value');
		var nome = $(this).parent('li').find('div a').text().trim();

			
		var html = "<div align='center' id='boxEditarEmpresa'>"+
				"<h3>Editar Empresa</h3>"+
				"<p>"+
					"<input type='text'  maxlength='50' value='"+nome+"' >"+
				"</p>"+
				"<p>"+
					"<button id='cancelarAcao'>cancelar</button><button id='editarEmpresa' value='"+id+"' >salvar</button>"+
				"</p>"+
			"</div>";
		lightbox(html);
		
	}).delegate('li #btExcluir','click',function(){
		
		var id = $(this).parent('li').attr('value');
		var nome = $(this).parent('li').find('div a').text().trim();

		var html = "<div align='center' id='boxExcluirEmpresa'>"+
				"<h3>Excluir empresa "+nome+"</h3>"+
				"<h4><font color='red'>Ao excluir esta empresa seus produtos também serão excluidos.</font></h4>"+
				"<button id='cancelarAcao'>cancelar</button><button id='excluirEmpresa' value='"+id+"' >excluir</button>"+
			"</p>";
		lightbox(html);
	});
	
	
	
	$('body').delegate('#boxEditarEmpresa #editarEmpresa','click',function(){
		
		var dados = empresaObj('editarEmpresa', $(this).attr('value'), $('#boxEditarEmpresa input').val().trim()) ;
		
		$.ajax({
			url:'',
			method:'POST',
			data:dados
		}).done(function(){
			$('#lightbox').remove();
			buscarPorEmpresas();
		});
		
	}).delegate('#boxExcluirEmpresa #excluirEmpresa','click',function(){

		var dados = empresaObj('excluirEmpresa', $(this).attr('value'), null );
		
		$.ajax({
			url:'',
			method:'POST',
			data:dados
		}).done(function(re){
			$('#lightbox').remove();
			buscarPorEmpresas();
		});
	});
	
	
});