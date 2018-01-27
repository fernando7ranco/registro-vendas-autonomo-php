<?php
class BancoDeDados
{
	const SERVIDOR = 'localhost';
	const USUARIO = 'root';
	const SENHA = null;
	const BANCO = 'sistema_vendas';
	private $conexao;
	
	public function __construct()
	{
		$this->conexao = new mysqli(self::SERVIDOR,self::USUARIO,self::SENHA,self::BANCO);
		
		if($this->conexao->connect_errno)
			die('<h2><font color="red" >erro de conexão com mysqli '. $this->conexao->error.'</font></h2>');
		
		if (!$this->conexao->set_charset("utf8")) 
			die("Error loading character set utf8: ". $conexao->error);
		
		date_default_timezone_set('America/Sao_Paulo');
	}
	
	public function executaQuery($sql)
	{
		$query = $this->conexao->query($sql);
		if(!$query)
			die('<h2><font color=red >erro de conexão com mysqli na sua query '. $this->conexao->error .'</font></h2>');
	
		return $query;	
	}
	
	public function preparaStatement($sql)
	{
        $statement = $this->conexao->prepare($sql);
        if(!$statement)
            die('Erro ao preparar statement query  '. $this->conexao->error);
        
        return $statement;
    }
	
	public function getConexao()
	{
		return $this->conexao;
	}
	
	public function getErro()
	{
		return $this->conexao->error;
	}
	
	public function fechaConexao(){
		$this->conexao->close();
	}
}
?>
