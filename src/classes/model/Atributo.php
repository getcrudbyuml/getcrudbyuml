<?php

/**
 * Classe feita para manipulaÃ§Ã£o do objeto Atributo
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson UchÃ´a Ponte <j.pontee@gmail.com>
 */
class Atributo {
    private $id;
    private $nome;
    private $tipo;
    private $relacionamento;
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
    public function setRelacionamento($relacionamento) {
        $this->relacionamento = $relacionamento;
    }
    public function getRelacionamento() {
        return $this->relacionamento;
    }

}
?>