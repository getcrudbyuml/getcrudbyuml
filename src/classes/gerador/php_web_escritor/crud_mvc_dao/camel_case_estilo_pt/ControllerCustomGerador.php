<?php


class ControllerCustomGerador{
    private $software;
    private $listaDeArquivos;
    private $diretorio;
    
    
    public static function main(Software $software, $diretorio){
        $gerador = new ControllerCustomGerador($software, $diretorio);
        $gerador->gerarCodigo();
    }
    public function __construct(Software $software, $diretorio){
        $this->software = $software;
        $this->diretorio = $diretorio;
    }

    public function gerarCodigo(){
        foreach ($this->software->getObjetos() as $objeto){
            $this->geraControllers($objeto);
        }
        $this->criarArquivos();
        
    }
    private function criarArquivos(){
        
        $caminho = $this->diretorio.'/AppWebPHP/src/classes/custom/controller';
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
   
    private function construct(Objeto $objeto){
        $codigo = '

	public function __construct(){
		$this->dao = new ' . ucfirst($objeto->getNome()) . 'CustomDAO();
		$this->view = new ' . ucfirst($objeto->getNome()). 'CustomView();
	}

';
        return $codigo;
    }
    private function geraControllers(Objeto $objeto)
    {
        $codigo = '<?php
            
/**
 * Customize o controller do objeto ' . $objeto->getNome() . ' aqui 
 * @author Jefferson Uchôa Ponte <jefponte@gmail.com>
 */



class ' . ucfirst($objeto->getNome()) . 'CustomController  extends ' . ucfirst($objeto->getNome()) . 'Controller {
    ';
        
        $codigo .= $this->construct($objeto);
        $codigo .= '
	        
}
?>';
        $caminho = $this->diretorio.'/AppWebPHP/src/classes/custom/controller/'.ucfirst($objeto->getNome()).'CustomController.php';
        $this->listaDeArquivos[$caminho] = $codigo;
    }
    
}


?>