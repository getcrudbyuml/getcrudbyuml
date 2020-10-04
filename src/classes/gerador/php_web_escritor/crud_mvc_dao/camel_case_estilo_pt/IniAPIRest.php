<?php

class IniAPIRest
{

    private $software;
    
    private $listaDeArquivos;
    
    private $diretorio;
    
    public static function main(Software $software, $diretorio = 'sistemas'){
        $gerador = new IniAPIRest($software, $diretorio);
        $gerador->gerarCodigo();
    }
    
    
    public function __construct(Software $software, $diretorio)
    {
        $this->software = $software;
        $this->diretorio = $diretorio;
        $this->listaDeArquivos = array();
    }
    public function gerarCodigo(){
        $this->geraIniAPI();
        
        $this->criarArquivos();
        
    }
    
    public function geraIniAPI(){
        
        if (! count($this->software->getObjetos())) {
            return;
        }
        $codigo  = ';Aruqivo de configuração para a api rest. 

user = usuario
password = senha@12
';
        
        $caminho = $this->diretorio.'/';
        $caminho = $caminho.$this->software->getNomeSnakeCase() .'_api_rest.ini';
        $this->listaDeArquivos[$caminho] = $codigo;
    }
    

    
    public function criarArquivos(){
        
        $caminho = $this->diretorio.'/AppWebPHP/src';
        if(!file_exists($caminho.'/img')) {
            mkdir($caminho.'/img', 0777, true);
        }
        if(!file_exists($caminho.'/css')) {
            mkdir($caminho.'/css', 0777, true);
        }
        if(!file_exists($caminho.'/js')) {
            mkdir($caminho.'/js', 0777, true);
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
}

?>