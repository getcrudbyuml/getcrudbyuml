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
    private $indice;
    public function setId($id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }
    public function setNome($nome) {
        $this->nome = preg_replace('/[^a-z0-9\s]/i', null, $nome);
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
        $this->indice= $indice;
    }
    public function getIndice() {
        return $this->indice;
    }

}
?>