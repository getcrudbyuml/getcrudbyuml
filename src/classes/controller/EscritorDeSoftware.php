<?php


class EscritorDeSoftware{
    private $listaDeArquivos;
    private $software;
    
    public static function main(Software $software){
        
        $escritor = new EscritorDeSoftware($software);
        $escritor->gerarCodigo();
        $escritor->criarArquivos();
        
    }
    public function __construct(Software $software){
        $this->software = $software;
    }
    public function gerarCodigo(){
        $gerador = new ModelGerador($this->software);
        $gerador->gerarCodigo(self::PHP_LINGUAGEM);
        $listas[0] = $gerador->getListaDeArquivos();
        
        $gerador = new DBGerador($this->software);
        $gerador->gerarCodigo(self::PHP_LINGUAGEM);
        $listas[1] = $gerador->getListaDeArquivos();
        
        $this->listaDeArquivos = $listas[0];
        $this->criarArquivos();
        $this->listaDeArquivos = $listas[1];
        $this->criarArquivos();
    }

    public function criarArquivos(){

        $pathPhp = 'sistemas/'.$this->software->getNome().'/php/src';
        $pathJava = 'sistemas/'.$this->software->getNome().'/java/src/br/com/escritordesoftware/'.strtolower($this->software->getNome());
        
        
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
        
        foreach($pastas as $pasta){
            if(!file_exists ($pasta)){
                mkdir ($pasta, 0777, true );
            }
        }
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