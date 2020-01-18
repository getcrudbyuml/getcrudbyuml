<?php
	
/**
 * Classe feita para manipulação do objeto Atributo
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class Atributo {
	private $id;
	private $nome;
	private $tipo;
	private $indice;

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
	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}
	public function getTipo() {
		return $this->tipo;
	}
	public function setIndice($indice) {
		$this->indice = $indice;
	}
	public function getIndice() {
		return $this->indice;
	}
	public function tipoListado(){
	    if($this->tipo == self::TIPO_INT || $this->tipo == self::TIPO_STRING || $this->tipo == self::TIPO_FLOAT){
	        return true;
	    }
	    return false;
	}
	public function isArray(){
	    if(substr(trim($this->getTipo()), 0, 6) == 'Array ')
	    {
	        return true;
	    }
	}
	public function getTipoDeArray(){
	    if(substr(trim($this->getTipo()), 0, 6) == 'Array '){
	        return explode(' ', $this->getTipo())[2];
	    }
	}
	public function getTipoJava(){
	    $tipo = $this->getTipo();
	    if($this->tipoListado()){
	        if($this->getTipo() == self::TIPO_INT){
	            $tipo = 'int';
	        }else if($this->getTipo() == self::TIPO_STRING){
	            $tipo = 'String';
	        }else if($this->getTipo() == self::TIPO_FLOAT){
	            $tipo = 'Float';
	        }
	    }
	    if(substr(trim($this->getTipo()), 0, 6) == 'Array '){
	        $tipo = "ArrayList<".explode(' ', $this->getTipo())[2].'>';
	    }
	    return $tipo;
	}
	const INDICE_PRIMARY = "PRIMARY";
	const TIPO_INT = "Int";
	const TIPO_STRING = "string";
	const TIPO_FLOAT = "float";
	
	
}
?>