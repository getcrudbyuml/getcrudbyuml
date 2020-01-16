<?php


class EscritorDeSoftware{
    private $listaDeArquivos;
    private $software;
    
    public static function main(Software $software){
        
        $escritor = new EscritorDeSoftware($software);
        $escritor->gerarCodigo();
        
    }
    public function __construct(Software $software){
        $this->software = $software;
    }
    public function gerarCodigo(){
        $this->criarDiretorios();
        
        $gerador = new ModelGerador($this->software);
        $gerador->gerarCodigo();
        $listas[0] = $gerador->getListaDeArquivos();
        
        $gerador = new DBGerador($this->software);
        $gerador->gerarCodigo();
        $listas[1] = $gerador->getListaDeArquivos();
        
        $gerador = new MainGerador($this->software);
        $gerador->gerarCodigo();
        $listas[2] = $gerador->getListaDeArquivos();
        
        
        $this->listaDeArquivos = $listas[0];
        $this->criarArquivos();
        
        $this->listaDeArquivos = $listas[1];
        $this->criarArquivos();
        
        $this->listaDeArquivos = $listas[2];
        $this->criarArquivos();
    }

    public function criarDiretorios(){
        $pathPhp = 'sistemas/'.$this->software->getNome().'/php/src';
        $pathJava = 'sistemas/'.$this->software->getNome().'/java/'.$this->software->getNome().'/src/main/java/br/com/escritordesoftware/'.strtolower($this->software->getNome());
        $pastas[] = 'sistemas/'.$this->software->getNome().'/java/'.$this->software->getNome().'/src/main/resources/images';
        
        
        $pastas[] =  $pathPhp.'/classes/model';
        $pastas[] =  $pathPhp.'/classes/view';
        $pastas[] =  $pathPhp.'/classes/controller';
        $pastas[] =  $pathPhp.'/classes/dao';
        $pastas[] =  $pathPhp.'/img';
        $pastas[] =  $pathPhp.'/css';
        $pastas[] =  $pathPhp.'/js';
        
        $pastas[] = $pathJava.'/model';
        $pastas[] = $pathJava.'/view';
        $pastas[] = $pathJava.'/controller';
        $pastas[] = $pathJava.'/dao';
        $pastas[] = $pathJava.'/main';
        
        foreach($pastas as $pasta){
            if(!file_exists ($pasta)){
                mkdir ($pasta, 0777, true );
            }
        }
    }
    public function criarArquivos(){

        foreach($this->listaDeArquivos as $path => $codigo){
            if(file_exists($path)){
                unlink($path);
            }
            $file = fopen($path, "w+");
            fwrite($file, stripslashes($codigo));
            fclose($file);
        }
    }
    
    const PHP_LINGUAGEM = 0;
    const JAVA_LINGUAGEM = 1;
    
}