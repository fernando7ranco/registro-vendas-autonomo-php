$(function () {
	
	function getClientes(nome){
		
		$.ajax({
			url:'',
			method:'post',
			data:{getClientes: true, nome: nome}
		}).done(function(re){
			$('#lista-clientes table tbody').html(re);
		});
		
	}
	
	getClientes('');
	
	var varTimeOutGetClientes;
	
	function functionTimeOutGetClientes(nome){
		
		varTimeOutGetClientes = setTimeout(function(){
			getClientes(nome);
		},555);
	}
	
	$('input[name=buscar-por-nome]').keyup(function(){
		
		var nome = $(this).val();
		
		clearInterval(varTimeOutGetClientes);
		
		functionTimeOutGetClientes(nome)
		
	});
	
	$('#lista-clientes table').delegate('#nome','click',function(){
		var id = $(this).parent('tr').attr('value');
		
		window.open("cliente.php?cliente="+id);
		
	});
   
});