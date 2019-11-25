<?php
	
/**
 * Classe feita para manipulação do objeto Objeto
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class Objeto {
	private $id;
	private $nome;
	private $idsoftware;
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
	public function setIdsoftware($idsoftware) {
		$this->idsoftware = $idsoftware;
	}
	public function getIdsoftware() {
		return $this->idsoftware;
	}
}
?>