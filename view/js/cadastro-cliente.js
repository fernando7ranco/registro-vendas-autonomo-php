$(function () {
	
    function textoInput(input, texto) {
        $(input).parent('div').append("<a id='textoInput' >" + texto + "</a>");
    };

    $('form input[placeholder]').focusout(function () {
        $(this).parent('div').find("#textoInput").remove();
    });

    function erro(thiss) {
        $(thiss).siblings('img').remove();
        $(thiss).parent('div').append("<img id='cf' src='img/icones/error.png'>");
    } 
	
	function load(thiss) {
        $(thiss).siblings('img').remove();
        $(thiss).parent('div').append("<img id='cf' src='img/icones/load.gif'>");
    }
	
    function valido(thiss) {
        $(thiss).siblings('img').remove();
    }
	
	
   var regex = {
		nome: function(n){
			var r = /^([a-z A-Z\u00C0-\u00FF]){3,50}$/;
			return r.test(n);
		},
		cpf: function(t){
			var r = /^((\d{3})\.(\d{3})\.(\d{3})\-(\d{2})){1,14}$/;
			return r.test(t);
		},
		cep: function(c){
			var r = /^(\d){8}$/;
			return r.test(c);
		},
		telefone: function(t){
			var r = /^(\(\d{2}\)\s\d{4,5}-\d{4}){0,15}$/;
			return r.test(t);
		}
	};
	
	$('form input[name=nome]').keyup(function(){

		var thiss = $(this);
		thiss.val(thiss.val().replace(/ /g,' '));
		var nome = thiss.val().trim();
		
		if(regex.nome(nome))
			valido(this);
        else
            erro(this);
		
	}).focus(function () {
        textoInput(this, 'campo nome, somente letra de A-Z e espaços, de 3 à 50 caracteres');
    });
	
	$('form input[name=cpf]').keyup(function(){
	
		var cpf = $(this).val();
		cpf = cpf.replace(/[^\d]{1,}/g,'').replace(/(\d{3})(\d{3})(\d{3})(\d{2})$/,"$1.$2.$3-$4");
		$(this).val(cpf);
		
		if(regex.cpf(cpf))
			valido(this);
        else
            erro(this);
	}).focus(function () {
        textoInput(this, 'campo cpf, somente numeros 0-9, 11 caracteres');
    });
	
	
	
	$('form input[name=cep]').keyup(function(){
		var thiss = $(this);
		var cep = $(this).val();
		
		if(regex.cep(cep) || cep.length == 0)
			valido(this);
        else
			erro(this);
	}).focus(function () {
        textoInput(this, 'campo cep, somente numeros de 0-9, 8 caracteres');
    }).keydown(function(event){
		
		if(event.keyCode == 13){
			var thiss = $(this);
			var cep = thiss.val();
			
			if(regex.cep(cep)){
				
				load(thiss)
				//Consulta o webservice viacep.com.br/
				$.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?",function(dados){
					
					if(!("erro" in dados)){
						$("form input[name=logradouro]").val(dados.logradouro);
						$("form input[name=bairro]").val(dados.bairro);
	
						$('input[name=cep]').removeClass();
					}else{
						alert("CEP não encontrado.");
						erro(thiss)
						$("form input[name=logradouro],form input[name=bairro]").val('').siblings('img').remove();
					}
					
				}).fail(function() {
					alert("erro no sistema, CEP não encontrado.");
					erro(thiss)
					$("form input[name=logradouro],form input[name=bairro]").val('').siblings('img').remove();
				});
			}else
				erro(thiss);
		}
	});
	
	
	$('input[name=telefone]').keyup(function(){
		
		var thiss = $(this);
		var tel = $(this).val();
		tel = tel.replace(/[^\d]{1,}/g,'').replace(/(\d{2})(\d{4,5})(\d{4})/g,"($1) $2-$3");
		thiss.val(tel);

		if(regex.telefone(tel))
			valido(this);
		else
			erro(this);
		
	}).focus(function () {
        textoInput(this, 'campo telefone , somente numeros de 0-9, 11 caracteres');
	});

    function getDadosCadastro() {

        return {
            nome: $('form input[name=nome]').val(),
            cpf: $('form input[name=cpf]').val(),
            cep: $('form input[name=cep]').val(),
            logradouro: $('form input[name=logradouro]').val(),
            bairro: $('form input[name=bairro]').val(),
            telefone: $('form input[name=telefone]').val()
        };
    };

    function valErro() {

        var dados = getDadosCadastro();

        if (!regex.nome(dados.nome)) {
            erro('form input[name=nome]');
        }
        if (!regex.cpf(dados.cpf)) {
            erro('form input[name=cpf]');
        }

    };

    $('form #bt-cadastro').click(function () {
		
        var dados = getDadosCadastro();
		
        if (
			regex.nome(dados.nome) && 
			regex.cpf(dados.cpf) && 
			(regex.cep(dados.cep) || dados.cep.length == 0) &&
			regex.telefone(dados.telefone)
		) {
            $.ajax({
                url: '',
                method: 'POST',
                data: {
                    acaoCadastro: true,
                    dados:dados
                }
            }).done(function (re) {
	
               var retorno = re.trim();
				
				if( retorno == 'cpf'){
					alert('seu cpf ja possui cadastro em nosso sistema');
					erro('form input[name=cpf]');
				}else
					location.reload();
            });
        } else
            valErro();
    });
	
});