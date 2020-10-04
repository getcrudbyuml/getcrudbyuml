<?php

namespace GetCrudByUML\gerador\webPHPEscritor\crudMVCDao;

use GetCrudByUML\model\Objeto;
use GetCrudByUML\model\Software;

class ModelGerador
{

    private $software;

    private $listaDeArquivos;

    private $diretorio;

    public static function main(Software $software, $diretorio)
    {
        $gerador = new ModelGerador($software, $diretorio);
        $gerador->geraCodigo();
    }

    public function __construct(Software $software, $diretorio)
    {
        $this->software = $software;
        $this->diretorio = $diretorio;
    }

    /**
     * Selecione uma linguagem
     *
     * @param int $linguagem
     */
    public function geraCodigo()
    {
        foreach($this->software->getObjetos() as $objeto){
            $this->geraModel($objeto);
        }
        $this->criarArquivos();
        
        
    }
    private function criarArquivos(){
        
        $caminho = $this->diretorio.'/AppWebPHP/src/classes/model/';
        if(!file_exists($caminho)) {
            mkdir($caminho, 0777, true);
        }
        
        foreach ($this->listaDeArquivos as $path => $codigo) {
            if (file_exists($path)) {
                unlink($path);
            }
            $file = fopen($path, "w+");
            fwrite($file, stripslashes($codigo));
            fclose($file);
        }
    }
    private function geraModel(Objeto $objeto)
    {
        $codigo = '<?php
            
/**
 * Classe feita para manipulação do objeto ' . $objeto->getNome() . '
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */



class ' . ucfirst($objeto->getNome()) . ' {';
        if (count($objeto->getAtributos()) == 0) {
            $codigo .= '}';
            return $codigo;
        }

        foreach ($objeto->getAtributos() as $atributo) {
            $codigo .= '
	private $' . lcfirst($atributo->getNome()) . ';';
        
        }
        $codigo .= '
    public function __construct(){
';
        foreach ($objeto->getAtributos() as $atributo) {
            if ($atributo->tipoListado()) {
                continue;
            } else if ($atributo->isArray()) 
            {
                $codigo .= '
        $this->' . $atributo->getNome() . ' = array();';
            } else if ($atributo->isObjeto()) {
                $codigo .= '
        $this->' . lcfirst($atributo->getNome()) . ' = new ' . ucfirst($atributo->getTipo()) . '();';
            }
        }
        $codigo .= '
    }';
        foreach ($objeto->getAtributos() as $atributo) {
            if ($atributo->tipoListado()) {

                $codigo .= '
	public function set' . ucfirst($atributo->getNome()) . '($' . lcfirst($atributo->getNome()) . ') {';
                $codigo .= '
		$this->' . lcfirst($atributo->getNome()) . ' = $' . lcfirst($atributo->getNome()) . ';
	}
		    
	public function get' . ucfirst($atributo->getNome()) . '() {
		return $this->' . lcfirst($atributo->getNome()) . ';
	}';
            } else if ($atributo->isArray()) {
                $codigo .= '
                            
    public function add' . ucfirst($atributo->getTipoDeArray()) . '(' . ucfirst($atributo->getTipoDeArray()) . ' $' . lcfirst($atributo->getTipoDeArray()) . '){
        $this->' . lcfirst($atributo->getNome()) . '[] = $' . lcfirst($atributo->getTipoDeArray()) . ';
            
    }
	public function get' . ucfirst($atributo->getNome()) . '() {
		return $this->' . lcfirst($atributo->getNome()) . ';
	}';
            } else if ($atributo->isObjeto()) {

                $codigo .= '
	public function set' . ucfirst($atributo->getNome()) . '(' . ucfirst($atributo->getTipo()) . ' $' . lcfirst($atributo->getTipo()) . ') {';

                $codigo .= '
		$this->' . lcfirst($atributo->getNome()) . ' = $' . lcfirst($atributo->getTipo()) . ';
	}
		    
	public function get' . ucfirst($atributo->getNome()) . '() {
		return $this->' . lcfirst($atributo->getNome()) . ';
	}';
            }
        }
        $codigo .= '
	public function __toString(){
	    return ';
        
        $pedacos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            
            if($atributo->tipoListado() || $atributo->isObjeto()){
                $pedacos[] = '$this->' . lcfirst($atributo->getNome());
                
            }else if($atributo->isArray()){
                $pedacos[] = '\'Lista: \'.implode(", ", $this->' . lcfirst($atributo->getNome()).')';
                
            }
        }
        $codigo .= implode('.\' - \'.', $pedacos);
        $codigo .= ';
	}
                
';

        $codigo .= '
}
?>';
        
        
        $caminho = $this->diretorio.'/AppWebPHP/src/classes/model/'.ucfirst($objeto->getNome()).'.php';
        $this->listaDeArquivos[$caminho] = $codigo;
        
    }

}

?>