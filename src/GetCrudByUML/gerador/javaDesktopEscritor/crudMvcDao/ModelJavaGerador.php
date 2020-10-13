<?php

namespace GetCrudByUML\gerador\javaDesktopEscritor\crudMvcDao;

use GetCrudByUML\model\Objeto;
use GetCrudByUML\model\Software;


class ModelJavaGerador{
    private $software;
    
    private $listaDeArquivos;
    
    private $diretorio;
    
    public static function main(Software $software, $diretorio)
    {
        $gerador = new ModelJavaGerador($software, $diretorio);
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
            $this->geraModel($objeto, $this->software);
        }
        $this->criarArquivos();
        
        
    }
    private function criarArquivos(){
        
        $caminho = $this->diretorio.'/AppDesktopJava/'.$this->software->getNomeSimples().'/src/main/java/com/'.strtolower($this->software->getNomeSimples()).'/model';
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
   
    private function geraModel(Objeto $objeto, Software $software)
    {
        
        $codigo = 'package com.'.strtolower($software->getNome()).'.model;
';
        if($objeto->possuiArray()){
        $codigo .= '
import java.util.ArrayList;
';
        }
        $codigo .= '

/**
 * Classe feita para manipulação do objeto ' . ucfirst ($objeto->getNome()) . '
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
public class ' . ucfirst ($objeto->getNome()) . ' {';
        foreach ($objeto->getAtributos() as $atributo) {
            
            if($atributo->isObjeto()){
                $codigo .= '
	private '.ucfirst($atributo->getTipo()) . ' '. $atributo->getNome() . ';';
                
            }else{
                $codigo .= '
	private '.$atributo->getTipoJava() . ' '. $atributo->getNome() . ';';
                
            }
                
                
        }
        
        
        $codigo .= '
    public ' . ucfirst ($objeto->getNome()) . '(){
';
        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->isArray()){
                    $codigo .= '
        this.'.$atributo->getNome().' = new ArrayList<'.$atributo->getTipoDeArray().'>();';
                    
            }else if($atributo->tipoListado()){
                
                continue;
            }
            else
            {
                    $codigo .= '
        this.'.lcfirst($atributo->getNome()).' = new '.ucfirst($atributo->getTipo()).'();';
                    
            }
            
        }
            $codigo .= '
    }';
        foreach ($objeto->getAtributos() as $atributo) {
                
            
            if ($atributo->tipoListado())
            {
                
                    $codigo .= '
	public void set' . ucfirst ($atributo->getNome()) .'(' .$atributo->getTipoJava().' '. lcfirst($atributo->getNome()) . ') {';
                    $codigo .= '
		this.' . lcfirst($atributo->getNome()) . ' = ' . lcfirst($atributo->getNome()) . ';
	}
		    
	public '.$atributo->getTipoJava().' get' . ucfirst ($atributo->getNome()) . '() {
		return this.' . lcfirst($atributo->getNome()) . ';
	}';
                    
            }
            else if($atributo->isArray()) {
                    
                    
                    $codigo .= '
                            
    public void add'.ucfirst($atributo->getTipoDeArray()).'('.ucfirst($atributo->getTipoDeArray()).' '.lcfirst($atributo->getTipoDeArray()).'){
        this.'.lcfirst($atributo->getNome()).'.add('.lcfirst($atributo->getTipoDeArray()).');
            
    }
	public ArrayList<'.$atributo->getTipoDeArray().'> get' . ucfirst($atributo->getNome()) . '() {
		return this.' . lcfirst($atributo->getNome()) . ';
	}';
                        
                        
                    
                }else{
                        $codigo .= '
	public void set'. ucfirst ($atributo->getNome()) .'(' . $atributo->getTipo() . ' ' . lcfirst($atributo->getNome()) . ') {';
                        
                        $codigo .= '
		this.' . lcfirst($atributo->getNome()) . ' = ' . lcfirst($atributo->getNome()) . ';
	}
		    
	public '.$atributo->getTipo().' get' . ucfirst($atributo->getNome()) . '() {
		return this.' . lcfirst($atributo->getNome()) . ';
	}';
                    
                }
                    
                    
                
            }
            $codigo .= '
	@Override
	public String toString() {
		return ';
            $i = count($objeto->getAtributos());
            foreach ($objeto->getAtributos() as $atributo) {
                $i--;
                $codigo .= 'this.'.$atributo->getNome();
                if($i != 0){
                    $codigo .= '+" - "+';
                }
                
            }
            $codigo .= ';
	}
                
';
            
        
        $codigo .= '
}
';
        
        $caminho = $this->diretorio.'/AppDesktopJava/'.$this->software->getNomeSimples().'/src/main/java/com/'.strtolower($this->software->getNomeSimples()).'/model/'.ucfirst($objeto->getNome()).'.java';
        $this->listaDeArquivos[$caminho] = $codigo;
        return $codigo;
    }
    
}


?>