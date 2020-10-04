<?php
namespace GetCrudByUML\gerador\javaDesktopEscritor\crudMvcDao;

use GetCrudByUML\model\Software as Software;
use GetCrudByUML\model\Objeto as Objeto;

class ControllerJavaGerador{
    private $software;
    private $listaDeArquivos;
    private $diretorio;
    
    public static function main(Software $software, $diretorio){
        $gerador = new ControllerJavaGerador($software, $diretorio);
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
        
        $caminho = $this->diretorio.'/AppDesktopJava/'.$this->software->getNomeSimples().'/src/classes/controller';
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
    
    private function geraControllers(Objeto $objeto, Software $software)
    {
        $codigo = '';
        $codigo = '
package br.com.escritordesoftware.'.strtolower($this->software->getNome()).'.controller;
            
/**
 * Classe de visao para ' . ucfirst($objeto->getNome()) . '
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */
public class ' . ucfirst($objeto->getNome()) . 'Controller {


}';
        $caminho = $this->diretorio.'/AppDesktopJava/'.$this->software->getNomeSimples().'/src/classes/controller/'.ucfirst($objeto->getNome()).'Controller.php';
        $this->listaDeArquivos[$caminho] = $codigo;
    }
    
}


?>