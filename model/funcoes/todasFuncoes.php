<?php

function numeroDecimal($valor, $condicao = true) {
        
	if (!preg_match("/^(\d\.?\,?){1,}$/", $valor))
		return null;
			
	$valor = preg_replace('/\D/i', '', $valor);

	if (strlen($valor) > 3)
		$valor = preg_replace('/^(0+)(\d)/i', "$2", $valor);

	$num = strlen($valor);
	if ($num < 3) {
		for ($i = 0; $i < (3 - $num); $i++) {
			$valor = '0' . $valor;
		}
	}

	if ($condicao) {
		$valor = preg_replace('/(\d+)(\d{2})/i', "$1,$2", $valor);
		$valor = preg_replace('/(\d+)(\d{3})(\,\d{2})/i', "$1.$2$3", $valor);
		$valor = preg_replace('/(\d+)(\d{3})(\.\d{3}\,\d{2})/i', "$1.$2$3", $valor);
		$valor = preg_replace('/(\d+)(\d{3})(\.\d{3}\.\d{3}\,\d{2})/i', "$1.$2$3", $valor);
	} else {
		$valor = preg_replace('/(\d)(\d{2})$/i', "$1.$2", $valor);
	}

	return $valor;
}

function texto($texto, $limite = 0) {
    $texto = htmlspecialchars($texto);

    if ($limite > 0)
        $texto = strlen($texto) > $limite ? substr($texto, 0, $limite) . '...' : $texto;

    return $texto;
}

function dataExtensa($date) {
	
    setlocale(LC_ALL, 'pt-br');
	
    if ($date == date("Y-m-d"))
        $date = 'hoje';
    else if ($date == date("Y-m-d", strtotime('- 1 day')))
        $date = 'Ontem';
    else
        $date = strftime('%d de %B de %Y ', strtotime($date));

    return $date;
}

function paginalizacao($numeroDeAnuncios,$pagina) 
{
    $rows = round($numeroDeAnuncios / 15);
    $total = $rows < 1 ? 1 : $rows;
    $atual = $pagina > $total ? 0 : $pagina;
    $inicio = $atual - 8;
    $inicio = $inicio < 1 ? 1 : $atual - 7;
    $contador = 1;

    $paginas = null;
    for ($i = $inicio; ($contador <= 14 AND $i <= $total); $i++) {
        $id = $i == $atual ? 'id=foco' : null;
        $paginas.= "<a {$id} >{$i}</a>";
        $contador++;
    }

    if ($atual > 8 and $total > 10)
        $paginas = "<a>1</a>..." . $paginas;

    if (($total - $atual) > 6 and $total > 10)
        $paginas.= "...<a>{$total}</a>";

    return "<div><div id='paginalizacao' >{$paginas}</div></div>";
}
