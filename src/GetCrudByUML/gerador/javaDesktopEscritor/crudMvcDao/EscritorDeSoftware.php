<?php


namespace GetCrudByUML\gerador\javaDesktopEscritor\crudMvcDao;

use GetCrudByUML\gerador\sqlGerador\DBGerador;
use GetCrudByUML\model\Software;


class EscritorDeSoftware
{

    private $listaDeArquivos;

    private $software;
    
    private $diretorio;
    
    public function __construct(Software $software, $diretorio)
    {
        $this->diretorio = $diretorio;
        $this->software = $software;
    }
    
    public static function main(Software $software, $diretorio)
    {
        $escritor = new EscritorDeSoftware($software, $diretorio);
        $escritor->geraCodigoJAVA();
        
    }
    private function criarArquivos($arquivos, $diretorio, $sobrescrever = true){
        
        if(!file_exists($diretorio)) {
            mkdir($diretorio, 0777, true);
        }
        foreach ($arquivos as $path => $codigo) {
            if(file_exists($diretorio.'/'.$path)){
                if($sobrescrever == false){
                    break;
                }
            }
            $file = fopen($diretorio.'/'.$path, "w+");
            fwrite($file, stripslashes($codigo));
            fclose($file);
        }
    }
    public function geraCodigoJAVA()
    {
        
        if(count($this->software->getObjetos()) == 0){
            echo "Não existem Objetos. Adicione pelo menos um objeto.";
            return;
        }
        foreach($this->software->getObjetos() as $objeto){
            if(count($objeto->getAtributos()) == 0){
                echo "Existe pelo menos um objeto sem atributos. Adicione atributos.";
                return;
            }
        }
        
        
        $this->diretorio .= '/crudJAVA';
        $diretorio = $this->diretorio.'/src';
        $this->criarArquivos(DBGerador::main($this->software), $diretorio.'/../..');
        $this->criarArquivos(POMGerador::main($this->software), $diretorio.'/..');
        $this->criarArquivos(MainJavaGerador::main($this->software), $diretorio.'/main/java/com/'.strtolower($this->software->getNomeSimples()).'/main');
        $this->criarArquivos(ModelJavaGerador::main($this->software), $diretorio.'/main/java/com/'.strtolower($this->software->getNomeSimples()).'/model');
        $this->criarArquivos(DAOJavaGerador::main($this->software), $diretorio.'/main/java/com/'.strtolower($this->software->getNomeSimples()).'/dao');
        $this->criarArquivos(ViewJavaGerador::main($this->software), $diretorio.'/main/java/com/'.strtolower($this->software->getNomeSimples()).'/dao');
        

        
    }
    
}