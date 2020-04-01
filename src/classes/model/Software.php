<?php
	
/**
 * Classe feita para manipulação do objeto Software
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class Software {
	private $id;
	private $nome;
	private $objetos;
	
	public function __construct(){
	    $this->objetos = array();
	}
	public function setId($id) {
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
	}
	public function setNome($nome) {
		$this->nome = $nome;
	}
	public function getNome() {
		return $this->nome;
	}
	public function getNomeSimples() {
	    $texto = preg_replace("/[^a-zA-Z0-9]/", "", $this->nome);
	    return $texto;
	}
	public function addObjeto(Objeto $objeto)
	{
	    $this->objetos[] = $objeto;
	}
	public function setObjetos($objetos)
	{
	    $this->objetos = $objetos;
	}
	public function getObjetos()
	{
	    return $this->objetos;
	}
}
?>