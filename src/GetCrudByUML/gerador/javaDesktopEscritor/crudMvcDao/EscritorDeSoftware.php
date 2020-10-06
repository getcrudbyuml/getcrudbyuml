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
        
        DBGerador::main($this->software, $this->diretorio);
        POMGerador::main($this->software, $this->diretorio);
        MainJavaGerador::main($this->software, $this->diretorio);
        ModelJavaGerador::main($this->software, $this->diretorio);
        DAOJavaGerador::main($this->software, $this->diretorio);
        ViewJavaGerador::main($this->software, $this->diretorio);
        
    }
    
}