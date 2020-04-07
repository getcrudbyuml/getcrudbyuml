<?php 


class ViewJavaGerador{
    private $software;
    private $listaDeArquivos;
    private $diretorio;
    
    public static function main(Software $software, $diretorio){
        $gerador = new ViewJavaGerador($software, $diretorio);
        $gerador->gerarCodigo();
    }
    public function __construct(Software $software, $diretorio){
        $this->software = $software;
        $this->diretorio = $diretorio;
    }
    
    public function gerarCodigo(){
        foreach ($this->software->getObjetos() as $objeto){
            $this->geraViewsJava($objeto, $this->software);
        }
        $this->criarArquivos();
        
    }
    private function criarArquivos(){
        
        $caminho = $this->diretorio.'/AppDesktopJava/'.$this->software->getNomeSimples().'/src/main/java/com/'.strtolower($this->software->getNomeSimples()).'/view';
        
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
    
    private function geraViewsJava(Objeto $objeto, Software $software)
    {
        $codigo = '';
        $codigo = '
package br.com.escritordesoftware.'.strtolower($this->software->getNome()).'.view;
import javax.swing.JFrame;
/**
 * Classe de visao para ' . ucfirst($objeto->getNome()) . '
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */
@SuppressWarnings("serial")
public class ' . ucfirst($objeto->getNome()) . 'View extends JFrame {}';
        
        $caminho = $this->diretorio.'/AppDesktopJava/'.$this->software->getNomeSimples().'/src/main/java/com/'.strtolower($this->software->getNomeSimples()).'/view/'.ucfirst($objeto->getNome()).'View.java';
        
        $this->listaDeArquivos[$caminho] = $codigo;
    }
}





?>