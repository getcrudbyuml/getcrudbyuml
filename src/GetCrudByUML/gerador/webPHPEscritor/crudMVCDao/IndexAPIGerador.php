<?php

namespace GetCrudByUML\gerador\webPHPEscritor\crudMVCDao;

use GetCrudByUML\model\Software;

class IndexAPIGerador
{

    private $software;
    
    private $listaDeArquivos;
    
    public static function main(Software $software){
        $gerador = new IndexAPIGerador($software);
        return $gerador->gerarCodigo();
    }
    
    
    public function __construct(Software $software)
    {
        $this->software = $software;
        $this->listaDeArquivos = array();
    }
    public function gerarCodigo(){
        $this->geraHTACCESS();
        $this->geraIndex();
        return $this->listaDeArquivos;
        
    }
    
    public function geraHTACCESS(){
        if (! count($this->software->getObjetos())) {
            return;
        }
        $codigo  = 'RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?api=$1 [QSA,L]
';

        $caminho = '.htaccess';
        $this->listaDeArquivos[$caminho] = $codigo;
        
    }
    
    public function geraIndex(){
        
        if (! count($this->software->getObjetos())) {
            return;
        }
        $codigo = '<?php
            
define("DB_INI", "../../' . $this->software->getNomeSnakeCase() . '_db.ini");
define("API_INI", "../../' . $this->software->getNomeSnakeCase() . '_api_rest.ini");
             
function autoload($classe) {

    $prefix = \''.$this->software->getNomeSimples().'\';
    $base_dir = __DIR__ . \''.$this->software->getNomeSimples().'\';
    $len = strlen($prefix);
    if (strncmp($prefix, $classe, $len) !== 0) {
        return;
    }
    $relative_class = substr($classe, $len);
    $file = $base_dir . str_replace(\'\\\\\\\\\', \'/\', $relative_class) . \'.php\';
    if (file_exists($file)) {
        require $file;
    }

}
spl_autoload_register(\'autoload\');
';
        foreach ($this->software->getObjetos() as $objeto) {
            $codigo .= '
use '.$this->software->getNomeSimples().'\\\\controller\\\\'.ucfirst($objeto->getNome()).'ApiRestController;';
            
            
        }
        
        $codigo .= '

if(isset($_REQUEST[\'api\'])){

    
    $url = explode("/", $_REQUEST[\'api\']);
    
    if(isset($url[1])){
        if($url[1] == \'\'){
            echo "Bem vindo a nossa API";
            exit;
        }
    }else{
        echo "Bem vindo a nossa API";
        exit;
    }
    switch($url[1]){';
        
        foreach ($this->software->getObjetos() as $objeto) {
            $codigo .= '
		case \'' .$objeto->getNomeSnakeCase() . '\':
            $controller = new '.ucfirst ($objeto->getNome()).'ApiRestController();
            $controller->main(API_INI);
            break;';
        }
        $codigo .= '

        default:
            echo \'URL inválida.\';
            break;
    
    }

';
        $codigo .= '
    exit;
}
              
       
?>
      
            
';

        $caminho = 'index.php';
        $this->listaDeArquivos[$caminho] = $codigo;
        
    }
    
    
    
}

?>