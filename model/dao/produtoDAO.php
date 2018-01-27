<?php

class ProdutoDAO {

    private $banco;
	
				
    public function __construct($bd) {
        $this->banco = $bd;
    }

    public function inserirProduto($produto) {

		$empresa = $produto->getEmpresa();
		$codigo = $produto->getCodigo();
        $FILE = $produto->getImagem();
        $nome = $produto->getNome();
        $descricao = $produto->getDescricao();
        $valor = $produto->getValor(false);
        $quantidade = $produto->getQuantidade();
		
		$imagem = $FILE[1];
		
		$tmp_name = $imagem['tmp_name'];
		$name = $imagem['name'];
		$newName = 'image'. date("Ymdhsi") . substr($name, -4);
		if (move_uploaded_file($tmp_name, 'img/produtos/' . $newName))
			$nameImg = $newName;
        
        $sql = 'INSERT INTO produtos VALUES (null, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->banco->preparaStatement($sql);
        $stmt->bind_param('issssdi', $empresa, $codigo, $nameImg, $nome, $descricao, $valor, $quantidade);
        $stmt->execute();

        return $stmt->insert_id;
	}
	
	public function alterarProduto($produto) {

        $idProduto = $produto->getId();
		$empresa = $produto->getEmpresa();
		$codigo = $produto->getCodigo();
        $image = $produto->getImagem();
        $nome = $produto->getNome();
        $descricao = $produto->getDescricao();
        $valor = $produto->getValor(false);
        $quantidade = $produto->getQuantidade();
	
		$stmt = $this->banco->preparaStatement('UPDATE produtos SET id_empresa = ? WHERE id = ? AND id_empresa != ?');
        $stmt->bind_param('iii', $empresa, $idProduto, $empresa);
        $stmt->execute();
		
		$stmt = $this->banco->preparaStatement('UPDATE produtos SET codigo = ? WHERE id = ? AND codigo != ?');
        $stmt->bind_param('sis', $codigo, $idProduto, $codigo);
        $stmt->execute();

		$nameImg = $this->banco->executaQuery("SELECT imagem FROM produtos WHERE id = {$idProduto}")->fetch_array()['imagem'];

		if ($image[0]){
			
			if(file_exists("img/produtos/{$nameImg}"))
				unlink("img/produtos/{$nameImg}");
			
			$imagem = $image[1];
			$tmp_name = $imagem['tmp_name'];
			$name = $imagem['name'];
			$newName = 'image'. date("Ymdhsi") . substr($name, -4);
			if (move_uploaded_file($tmp_name, 'img/produtos/' . $newName))
				$nameImg = $newName;
			
		}
		
        $stmt = $this->banco->preparaStatement('UPDATE produtos SET imagem = ? WHERE id = ? AND imagem != ?');
        $stmt->bind_param('sis', $nameImg, $idProduto, $nameImg);
        $stmt->execute();

        $stmt = $this->banco->preparaStatement('UPDATE produtos SET nome = ? WHERE id = ? AND nome != ?');
        $stmt->bind_param('sis', $nome, $idProduto, $nome);
        $stmt->execute(); 
		
		$stmt = $this->banco->preparaStatement('UPDATE produtos SET descricao = ? WHERE id = ? AND descricao != ?');
        $stmt->bind_param('sis', $descricao, $idProduto, $descricao);
        $stmt->execute();

        $stmt = $this->banco->preparaStatement('UPDATE produtos SET valor = ? WHERE id = ? AND valor != ?');
        $stmt->bind_param('did', $valor, $idProduto, $valor);
        $stmt->execute();

		$stmt = $this->banco->preparaStatement('UPDATE produtos SET quantidade = ? WHERE id = ? AND quantidade != ?');
        $stmt->bind_param('iii', $quantidade, $idProduto, $quantidade);
        $stmt->execute();

        return $idProduto;
	}
	
	public function selecionaProduto($id){
		
		$sql = 'SELECT * FROM produtos WHERE id = ?';
		
        $stmt = $this->banco->preparaStatement($sql);

        $stmt->bind_param('i', $id);
        $stmt->execute();
		
		$result = $stmt->get_result();
		
		$linhas = $result->num_rows ? $result->fetch_array() : [];
		
		$retorno = new Produto($linhas);
		

		return $retorno;
	}
	
	public function selecionaProdutos($empresa,$nome,$limit){
		
		$limit = is_numeric($limit) ? $limit * 20 : 0;
		
		$empresa = $empresa ? "E.id = {$empresa} AND" : '';
		
		$return = ['produtos' => null, 'quantidade' => 0];
		
		$sql = "SELECT P.id, E.nome AS empresa, P.imagem, P.nome, P.valor, P.quantidade FROM produtos P INNER JOIN empresas E ON P.id_empresa = E.id WHERE {$empresa} P.nome LIKE '{$nome}%' ORDER BY P.nome LIMIT {$limit},20";
		
		$query =  $this->banco->executaQuery($sql);
		
		while($linhas = $query->fetch_array()){
			
			$valor = numeroDecimal($linhas['valor']);
			
			$return['produtos'].= "<li value='{$linhas['id']}'>
						<img src='img/produtos/{$linhas['imagem']}'>
						{$linhas['empresa']} - {$linhas['nome']} |
						R$ {$valor} |
						quantidade: {$linhas['quantidade']}
						<span>
							<button>editar</button>
							<button>excluir</button>
						</span>
					</li>";
		}
		
		if(isset($condicao[1])){
			
			$sql = "SELECT P.id FROM produtos P INNER JOIN empresas E ON P.id_empresa = E.id WHERE {$empresa} P.nome LIKE '{$nome}%'";
			$return['quantidade'] =  $this->banco->executaQuery($sql)->num_rows;
			
		}
		
		return $return;
	}
	
	
	public function procurarProdutoAddPedidos($nome){
		
		$sql = "SELECT P.id, E.nome AS empresa, P.imagem, P.nome, P.valor, P.quantidade FROM produtos P INNER JOIN empresas E ON P.id_empresa = E.id WHERE P.nome LIKE CONCAT('',?,'%')";
		$stmt = $this->banco->preparaStatement($sql);
		$stmt->bind_param('s',$nome);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$lista = null;
		
		while($linhas = $result->fetch_array()){
			
			$valor = numeroDecimal($linhas['valor']);
			
			$id = $linhas['quantidade'] ? $linhas['id'] : '';
			
			$lista.= "<div value='{$id}'>
						<img src='img/produtos/{$linhas['imagem']}'>
						{$linhas['empresa']} - {$linhas['nome']} |
						R$: <a value='{$linhas['valor']}' >{$valor}</a> |
						<span>qtd: <a>{$linhas['quantidade']}</a></span>
					</div>";
			
		}

		return $lista;
	}
	
	public function excluirProduto($id){

		$sql = "SELECT imagem FROM produtos WHERE id = ?";
		$stmt = $this->banco->preparaStatement($sql);
		$stmt->bind_param('i',$id);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$imagem = $result->fetch_array()['imagem'];
		
		if($imagem)
			unlink("img/produtos/{$imagem}");
		
		$sql = "DELETE FROM produtos WHERE id = ?";
		$stmt = $this->banco->preparaStatement($sql);
		$stmt->bind_param('i',$id);
		$stmt->execute();
	}
}

?>