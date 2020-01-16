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
        
        $this->listaDeArquivos = array_merge($listas[0], $listas[1]);
        
        $this->criarArquivos();
        
    }

    public function criarArquivos(){

        
        $pastas[] = 'sistemas/sistemasphp/'.$this->software->getNome().'/src';
        $pastas[] = 'sistemas/sistemasphp/'.$this->software->getNome() .'/src/classes';
        $pastas[] = 'sistemas/sistemasphp/'.$this->software->getNome().'/src/classes/model';
        $pastas[] = 'sistemas/sistemasphp/'.$this->software->getNome().'/src/classes/view';
        $pastas[] = 'sistemas/sistemasphp/'.$this->software->getNome().'/src/classes/controller';
        $pastas[] = 'sistemas/sistemasphp/'.$this->software->getNome().'/src/classes/view';
        $pastas[] = 'sistemas/sistemasphp/'.$this->software->getNome().'/src/classes/dao';
        $pastas[] = 'sistemas/sistemasphp/'.$this->software->getNome().'/src/img';
        $pastas[] = 'sistemas/sistemasphp/'.$this->software->getNome().'/src/css';
        $pastas[] = 'sistemas/sistemasphp/'.$this->software->getNome().'/src/js';
        
        
        $pastas[] = 'sistemas/sistemasjava/'.$this->software->getNome() .'/src/br/com/escritordesoftware/'.strtolower($this->software->getNome()).'/model';
        $pastas[] = 'sistemas/sistemasjava/'.$this->software->getNome() .'/src/br/com/escritordesoftware/'.strtolower($this->software->getNome()).'/view';
        $pastas[] = 'sistemas/sistemasjava/'.$this->software->getNome() .'/src/br/com/escritordesoftware/'.strtolower($this->software->getNome()).'/controller';
        $pastas[] = 'sistemas/sistemasjava/'.$this->software->getNome() .'/src/br/com/escritordesoftware/'.strtolower($this->software->getNome()).'/dao';
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