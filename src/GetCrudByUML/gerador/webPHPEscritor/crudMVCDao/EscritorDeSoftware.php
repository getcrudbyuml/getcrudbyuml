<?php


namespace GetCrudByUML\gerador\webPHPEscritor\crudMVCDao;

use GetCrudByUML\gerador\sqlGerador\DBGerador;
use GetCrudByUML\model\Software;


class EscritorDeSoftware
{

    

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
    
    private function criarArquivos($arquivos, $diretorio){
        
        if(!file_exists($diretorio)) {
            mkdir($diretorio, 0777, true);
        }
        
        foreach ($arquivos as $path => $codigo) {
            if (file_exists($diretorio.'/'.$path)) {
                unlink($path);
            }
            $file = fopen($diretorio.'/'.$path, "w+");
            fwrite($file, stripslashes($codigo));
            fclose($file);
        }
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
        
        
        $this->diretorio .= '/srcAPIPHP';
        $diretorio = $this->diretorio.'/'.$this->software->getNomeSimples();
        
        $this->criarArquivos(ModelGerador::main($this->software), $diretorio.'/'.'model');
        $this->criarArquivos(DAOGerador::main($this->software), $diretorio.'/dao');
        $this->criarArquivos(ControllerRestGerador::main($this->software), $diretorio.'/controller');
//         $this->criarArquivos(ViewGerador::main($this->software), $diretorio.'/'.'view');
//         $this->criarArquivos(ControllerGerador::main($this->software), $diretorio.'/'.'controller');
//         $this->criarArquivos(IndexGerador::main($this->software), $diretorio);
        $this->criarArquivos(IndexAPIGerador::main($this->software), $diretorio.'/..');
        
//         ControllerCustomGerador::main($this->software, $this->diretorio);
//         DAOCustomGerador::main($this->software, $this->diretorio);
//         ViewCustomGerador::main($this->software, $this->diretorio);
//         JSAjaxGerador::main($this->software, $this->diretorio);
//         IniAPIRest::main($this->software, $this->diretorio);
//         
        
//         DBGerador::main($this->software, $this->diretorio);
        

    }
}