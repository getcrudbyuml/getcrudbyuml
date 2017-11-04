<?php
class Conexao
{
	/**
	 * Metodo Construtor
	 * Está private, pois o objeto nao podera ser instanciado
	 * POrtanto, todos os metodos sao do tipo static
	 */
	private function __construct(){
	}
	public static function retornaConexaoComBanco()
	{
		$conexao = new PDO('mysql:host=localhost;port=3306;dbname=escritor7','root','');
		return $conexao;
	}
		
}
?>