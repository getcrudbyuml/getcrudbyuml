<?php


namespace GetCrudByUML\gerador\webPHPEscritor\crudMVCDao;

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
        $escritor->gerarCodigoPHP();
    }
    
    public function gerarCodigoPHP()
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
        ModelGerador::main($this->software, $this->diretorio);
        DAOGerador::main($this->software, $this->diretorio);
        ViewGerador::main($this->software, $this->diretorio);
        ControllerGerador::main($this->software, $this->diretorio);
        IndexGerador::main($this->software, $this->diretorio);
        ControllerCustomGerador::main($this->software, $this->diretorio);
        DAOCustomGerador::main($this->software, $this->diretorio);
        ViewCustomGerador::main($this->software, $this->diretorio);
        JSAjaxGerador::main($this->software, $this->diretorio);
        IniAPIRest::main($this->software, $this->diretorio);

    }
}