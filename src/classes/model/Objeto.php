<?php

/**
 * Classe feita para manipulaÃ§Ã£o do objeto Objeto
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson UchÃ´a Ponte <j.pontee@gmail.com>
 */
class Objeto
{
	private $id;
	private $nome;
	private $atributos;

	public function __construct()
	{
	    $this->listaDeAtributos = array();
	    
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function getId(){
		return $this->id;
	}

	
	public function setNome($nome)
	{

		$nome = strtoupper(substr($nome, 0, 1)).substr($nome, 1, 100);
		$novo_nome = preg_replace("/[^a-zA-Z0-9]/", "", $nome);
		$this->nome = $novo_nome;
		
	}
	public function getNome(){
		return $this->nome;
	}
	public function addAtributo(Atributo $atributo){
		$this->listaDeAtributos[] = $atributo; 
	}
	public function getAtributos(){
		return $this->listaDeAtributos;	
	}

}


?>