<?php


class MainGerador{
    private $software; 
    private $listaDeArquivos; 
    
    public function __construct(Software $software){
        $this->listaDeArquivos = array();
        $this->software = $software;
    }
    
    public function gerarCodigo($linguagem = self::PHP_LINGUAGEM){
        switch ($linguagem){
            case EscritorDeSoftware::PHP_LINGUAGEM:
                $this->geraCodigoPHP();
                break;
            case EscritorDeSoftware::JAVA_LINGUAGEM:
                $this->geraCodigoJava();
                break;
            default:
                $this->geraCodigoPHP();
                break;
        }
    }
    public function geraCodigoPHP(){
        
    }
    public function geraCodigoJava(){
        
    }
    
}

?>