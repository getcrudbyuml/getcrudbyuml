<?php
	
/**
 * Classe feita para manipulação do objeto Objeto
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class Objeto {
	private $id;
	private $nome;
	private $atributos;
	
	public function __construct(){
	    $this->atributos = array();
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
	public function addAtributo(Atributo $atributo){
	    $this->atributos[] = $atributo;
	}
	public function getAtributos(){
	    return $this->atributos;
	}
}
?>