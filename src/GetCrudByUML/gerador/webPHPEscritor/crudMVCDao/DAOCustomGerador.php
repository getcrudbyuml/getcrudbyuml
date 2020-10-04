<?php

namespace GetCrudByUML\gerador\webPHPEscritor\crudMVCDao;

use GetCrudByUML\model\Objeto;
use GetCrudByUML\model\Software;

class DAOCustomGerador
{

    private $software;

    private $listaDeArquivos;

    private $diretorio;

    public static function main(Software $software, $diretorio)
    {
        $gerador = new DAOCustomGerador($software, $diretorio);
        $gerador->geraCodigo();
    }

    public function __construct(Software $software, $diretorio)
    {
        $this->software = $software;
        $this->diretorio = $diretorio;
    }

    private function geraCodigo()
    {
        
        foreach($this->software->getObjetos() as $objeto){
            $this->geraDAOs($objeto);
        }
        
        $this->criarArquivos();
        
    }
    private function criarArquivos(){
        
        $caminho = $this->diretorio.'/AppWebPHP/src/classes/custom/dao';
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

    private function geraDAOs(Objeto $objeto)
    {
        $codigo = '';
        $codigo .= '<?php
                
/**
 * Customize sua classe
 *
 */



class  ' . ucfirst($objeto->getNome()) . 'CustomDAO extends ' . ucfirst($objeto->getNome()) . 'DAO {
    

';

        $codigo .= '
}';

        $caminho = $this->diretorio.'/AppWebPHP/src/classes/custom/dao/'.ucfirst($objeto->getNome()).'CustomDAO.php';
        $this->listaDeArquivos[$caminho] = $codigo;
    }
}

?>