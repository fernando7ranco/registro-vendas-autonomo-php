$(document).ready(function () {
	
  $('#paginalizacao a').click(function () {
		var num = $(this).text();

		$('form input[name=limite]').val(num);
		$('form').submit();
	});
	
	$('form button[type=reset]').click(function(){
		location.href = 'produtos.php';
	});
	
	$('#lista-de-produtos li button').click(function(){
		
		var id = $(this).parents('li').attr('value');
		
		if($(this).index() == 0)
			location.href = 'uploadproduto.php?produto='+id;
		else{
			
			var html ="<div id='boxExcluirProduto' align='center'>"+
				"<h3>Tem certeza que deseja excluir este produto</h3>"+
				"<p>"+
					"<button id='cancelarAcao'>cancelar</button><button id='confirmaExcluirProduto' value='"+id+"'>excluir</button>"+
				"</p>"+
			"</div>";
			
			lightbox(html);
		}
	});
	
	$('body').delegate("#boxExcluirProduto #confirmaExcluirProduto","click",function(){
		
		var id = $(this).attr('value');
		
		$.ajax({
			url:'',
			method:'POST',
			data:{excluirProduto: true, id: id}
		}).done(function(re){
			location.href = 'produtos.php';
		});
		
	});
	
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
});