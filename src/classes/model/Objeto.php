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
	public function getNomeSnakeCase()
	{
	    $nome	= preg_replace('/([a-z])([A-Z])/',"$1_$2",$this->nome);
	    $nome	= strtolower($nome);
	    return $nome;
	}
	/**
	 * Raw: user login count
	 * Kebab Case: user-login-count
	 * @return string
	 */
	public function getNomeKebabCase(){
	    $nome	= preg_replace('/([a-z])([A-Z])/',"$1-$2",$this->nome);
	    $nome	= strtolower($nome);
	    return $nome;
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
	public function possuiArray(){
	    foreach($this->atributos as $atributo){
	        if($atributo->isArray()){
	            return true;
	        }
	    }
	    return false;
	}
}
?>