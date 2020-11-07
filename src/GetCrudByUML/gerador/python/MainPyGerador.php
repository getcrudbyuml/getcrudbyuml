<?php 

namespace GetCrudByUML\gerador\python;

use GetCrudByUML\model\Software;

class MainPyGerador{
    
    private $software;
    private $listaDeArquivos;
    private $diretorio;
    
    public static function main(Software $software){
        $gerador = new MainPyGerador($software);
        return $gerador->gerarCodigo();
    }
    public function __construct(Software $software){
        $this->software = $software;
    }
    
    public function gerarCodigo(){
        $this->geraMain();
        return $this->listaDeArquivos;
    }
    
    
    
    public function geraMain(){
        $objeto1 = $this->software->getObjetos()[0];
        $codigo  = '

from '.$objeto1->getNomeSnakeCase().' import '.ucfirst($objeto1->getNome()).'

def main():
    while(True):
        comando = input("1 - Listar   2 - Cadastrar\n")
        if comando == "1":
            '.ucfirst($objeto1->getNome()).'.listar()
        elif comando == "2":
            '.ucfirst($objeto1->getNome()).'.cadastrar()

if __name__ == "__main__":
    main()  
';
        $caminho = 'main.py';
        $this->listaDeArquivos[$caminho] = $codigo;
        return $codigo;
        
    }
}






?>