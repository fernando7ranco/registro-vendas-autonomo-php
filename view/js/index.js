$(document).ready(function () {
	
	if(window.location.pathname.split('/').pop() == 'inicio=entrar'){
		$(window).scrollTop(500);
		$('#login input[name=email]').focus();
	}
	
	$('.slides-show #left').click(function(){
		var n = $('#scroll-box-slides-show #produto:visible').length -3;
		
		if(n > 0)
			$('#scroll-box-slides-show #produto:visible:eq(0)').animate({width:'hide'},500);
		else
			$('#scroll-box-slides-show #produto').show();
	});
	
	$('.slides-show #right').click(function(){
		var n = $('#scroll-box-slides-show #produto:hidden').length ;
		
		if(n > 0)
			$('#scroll-box-slides-show #produto:hidden:eq(-1)').animate({width:'show'},500);
		else
			$('#scroll-box-slides-show #produto:visible').slice(0, -3).hide();
		
	});
	
	var varSetIntervalSlides
	
	function setIntervalSlides(){
		
		varSetIntervalSlides = setInterval(function(){
			$('.slides-show #left').click();
		},3000);
		
	}
	setIntervalSlides();
	
	$('.slides-show').hover(function(){
		clearInterval(varSetIntervalSlides);
	},function(){
		setIntervalSlides();
	});

	$.fn.tooltip = function () {

        this.hover(function () {
            var texto = $(this).attr('alt');

            $('body').append("<span id='titleTooltip'>" + texto + "</span>");
            $('#titleTooltip').fadeIn(555);
        }, function () {
            $('#titleTooltip').remove();
        }).mousemove(function (e) {
            var mousex = e.pageX + 20;
            var mousey = e.pageY + 20;

            $('#titleTooltip').css({top: mousey, left: mousex});
        });

    };

	
    $('#forms .iconeWebSite').tooltip();

    $('body').delegate('#autenficacoes span:not(#foco)', 'click', function () {

        $(this).siblings('span').removeAttr('id');
        $(this).attr('id', 'foco');
        var indexBt = $(this).index();

        switch (indexBt)
        {
            case 1:
                $('#login').hide().siblings('#cadastro').fadeIn();
                break;
            case 0:
                $('#cadastro').hide().siblings('#login').fadeIn();
                break;
        }
    })

    var regex = {
        email: function (e) {
            var r = /^(([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)){1,80}$/;
            return r.test(e);
        },
        senha: function (s) {
            var r = /^([a-zA-Z0-9]){6,16}$/;
            return r.test(s);
        }
    };

    $('#fazerLogin').click(function () {
  
        var email = $('#login input[name=email]').val();
        var senha = $('#login input[name=senha]').val();
        var mLogado = $('#Mlogado:checked').length;
        
        if (regex.email(email) && regex.senha(senha)) {

            $.ajax({
                url: '',
                method: 'POST',
                data: {
                    login: true,
                    email: email,
                    senha: senha,
                    manterLogado: mLogado
                }
            }).done(function (re) {
				$('header').html(re)
                re = re.trim();
				
                if (re == 'email') {
                    textoInput('#login input[name=email]','este email de usuario não foi encontrado em nosso sistema');
                } else if (re == 'senha') {
                    textoInput('#login input[name=senha]','esta senha não corresponde ao email logado em nosso sistema');
                }else if(re == 'logado'){
                    location.href = 'inicio';
                }
            });

        } else {
            if (!regex.email(email)){
                textoInput('#login input[name=email]','insira seu email de usuario');
            } else if (!regex.senha(senha)){
                textoInput('#login input[name=senha]','insira sua senha de usuario');
            }
        }
    });
    
    $('#login input[placeholder]').focusout(function(){
        $(this).parent('p').find("#textoInput").remove();
    });
    
    function textoInput(input,texto){
        $(input).parent('p').append("<a id='textoInput' >"+texto+"</a>");
        $(input).focus();
    };
    
});