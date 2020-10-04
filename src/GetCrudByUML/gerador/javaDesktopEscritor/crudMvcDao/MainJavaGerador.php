<?php 

namespace GetCrudByUML\gerador\javaDesktopEscritor\crudMvcDao;

use GetCrudByUML\model\Software;

class MainJavaGerador{
    private $software;
    private $listaDeArquivos;
    private $diretorio;
    
    public static function main(Software $software, $diretorio){
        $gerador = new MainJavaGerador($software, $diretorio);
        $gerador->gerarCodigo();
    }
    public function __construct(Software $software, $diretorio){
        $this->software = $software;
        $this->diretorio = $diretorio;
    }
    
    public function gerarCodigo(){
        $this->geraMain();
        $this->criarArquivos();
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
        $caminho = $this->diretorio.'/AppDesktopJava/'.$this->software->getNomeSimples().'/src/main/java/com/'.strtolower($this->software->getNomeSimples()).'/main/Main.java';
        $this->listaDeArquivos[$caminho] = $codigo;
        return $codigo;
        
    }
}






?>