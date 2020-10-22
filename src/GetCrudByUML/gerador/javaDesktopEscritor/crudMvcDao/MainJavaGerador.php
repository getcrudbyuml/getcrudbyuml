<?php 

namespace GetCrudByUML\gerador\javaDesktopEscritor\crudMvcDao;

use GetCrudByUML\model\Software;

class MainJavaGerador{
    private $software;
    private $listaDeArquivos;
    private $diretorio;
    
    public static function main(Software $software){
        $gerador = new MainJavaGerador($software);
        return $gerador->gerarCodigo();
    }
    public function __construct(Software $software){
        $this->software = $software;
    }
    
    public function gerarCodigo(){
        $this->geraMain();
        return $this->listaDeArquivos;
    }
    private function criarArquivos(){
        
        $caminho = $this->diretorio.'/AppDesktopJava/'.$this->software->getNomeSimples().'/src/main/java/com/'.strtolower($this->software->getNomeSimples()).'/main';
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
    
    
    public function geraMain(){
        $codigo  = 'package com.'.strtolower($this->software->getNomeSimples()).'.main;
            
public class Main {
            
	public static void main(String[] args) {
		System.out.println("Ola mundo");
	}
            
}
            
';
        $caminho = 'Main.java';
        $this->listaDeArquivos[$caminho] = $codigo;
        return $codigo;
        
    }
}






?>