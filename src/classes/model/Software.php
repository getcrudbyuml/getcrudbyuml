<?php

/**
 * Classe feita para manipulaÃ§Ã£o do objeto Software
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson UchÃ´a Ponte <j.pontee@gmail.com>
 */
class Software
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nome;
    /**
     * @var array 
     */
    private $objetos;

    public function __construct()
    {
        $this->objetos = array();
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setNome($nome)
    {
        $this->nome = $nome;
    }
    public function getNome()
    {
        return $this->nome;
    }
    public function addObjeto(Objeto $objeto)
    {
        $this->listaDeObjetos[] = $objeto;
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