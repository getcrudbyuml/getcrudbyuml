<?php


namespace GetCrudByUML\gerador\webPHPEscritor\crudMVCDao;

use GetCrudByUML\model\Atributo;
use GetCrudByUML\model\Objeto;
use GetCrudByUML\model\Software;

class ControllerRestGerador{
    private $software;
    private $listaDeArquivos;
    private $diretorio;
    
    public static function main(Software $software){
        $gerador = new ControllerRestGerador($software);
        return $gerador->gerarCodigo();
    }
    public function __construct(Software $software){
        $this->software = $software;

    }

    public function gerarCodigo(){
        foreach ($this->software->getObjetos() as $objeto){
            $this->geraControllers($objeto);
        }
        return $this->listaDeArquivos;
        
    }
    
   
    private function construct(Objeto $objeto){
        $codigo = '

	public function __construct(){
		$this->dao = new ' . ucfirst($objeto->getNome()) . 'DAO();

	}

';
        return $codigo;
    }
 
    public function delete(Objeto $objeto):string{
        $codigo = '

    public function delete()
    {
        if ($_SERVER[\'REQUEST_METHOD\'] != \'DELETE\') {
            return;
        }
        $path = explode(\'/\', $_GET[\'api\']);
        $parametro = "";
        if (count($path) < 2) {
            return;
        }
        $parametro = $path[1];
        if ($parametro == "") {
            return;
        }
    
        $id = intval($parametro);
        $selected = new '.ucfirst($objeto->getNome()).'();
        $selected->setId($id);
        $selected = $this->dao->pesquisaPorId($selected);
        if ($selected == null) {
            echo "{}";
            return;
        }

        if($this->dao->excluir($selected))
        {
            echo "{}";
            return;
        }
        
        echo "Erro.";
        
    }

';
        return $codigo;
        
    }

    public function get(Objeto $objeto):string{
        
        $atributosComuns = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->tipoListado()){
                $atributosComuns[] = $atributo;
            }
        }
        $codigo = '';
        $codigo .= '

    public function get()
    {

        if ($_SERVER[\'REQUEST_METHOD\'] != \'GET\') {
            return;
        }

        if(!isset($_REQUEST[\'api\'])){
            return;
        }
        $url = explode("/", $_REQUEST[\'api\']);
        if (count($url) == 0 || $url[0] == "") {
            return;
        }
        if ($url[1] != \''.$objeto->getNomeSnakeCase().'\') {
            return;
        }

        if(isset($url[2])){
            $parametro = $url[2];
            $id = intval($parametro);
            $selected = new '.ucfirst($objeto->getNome()).'();
            $selected->setId($id);
            $selected = $this->dao->preenchePorId($selected);
            if ($selected == null) {
                echo "{}";
                return;
            }

            $selected = array(';
        $i = 0;
        foreach ($atributosComuns as $atributo) {
            $i ++;
            $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
            $codigo .= '
					\'' . $atributo->getNome() . '\' => $selected->get' . $nomeDoAtributoMA . ' ()';
            if ($i != count($objeto->getAtributos())) {
                $codigo .= ', ';
            }
        }
        
        $codigo .= '
            
            
			);
            echo json_encode($selected);
            return;
        }        
        $list = $this->dao->fetch();
        $listagem = array();
        foreach ( $list as $linha ) {
			$listagem [\'list\'] [] = array (';
        $i = 0;
        foreach ($atributosComuns as $atributo) {
            $i ++;
            $nomeDoAtributoMA = ucfirst($atributo->getNome());
            $codigo .= '
					\'' . $atributo->getNome() . '\' => $linha->get' . $nomeDoAtributoMA . ' ()';
            if ($i != count($objeto->getAtributos())) {
                $codigo .= ', ';
            }
        }
        
        $codigo .= '
            
            
			);
		}
		echo json_encode ( $listagem );
    
		
		
		
		
	}';
        return $codigo;
        
    }
    public function geraMain() : string {
        $codigo = '
            
    public function main($iniApiFile)
    {
            
        $config = parse_ini_file ( $iniApiFile );
        $user = $config [\'user\'];
        $password = $config [\'password\'];
            
        if(!isset($_SERVER[\'PHP_AUTH_USER\'])){
            header("WWW-Authenticate: Basic realm=\\\\"Private Area\\\\" ");
            header("HTTP/1.0 401 Unauthorized");
            echo \'{"erro":[{"status":"error","message":"Authentication failed"}]}\';
            return;
        }
        if($_SERVER[\'PHP_AUTH_USER\'] == $user && ($_SERVER[\'PHP_AUTH_PW\'] == $password)){
            header(\'Content-type: application/json\');
            
            $this->get();
            $this->post();
            $this->put();
            $this->delete();
        }else{
            header("WWW-Authenticate: Basic realm=\\\\"Private Area\\\\" ");
            header("HTTP/1.0 401 Unauthorized");
            echo \'{"erro":[{"status":"error","message":"Authentication failed"}]}\';
        }
            
    }';
        return $codigo;
    }
    public function put(Objeto $objeto):string{
        $codigo = '


    public function put()
    {
        if ($_SERVER[\'REQUEST_METHOD\'] != \'PUT\') {
            return;
        }

        if (! array_key_exists(\'api\', $_GET)) {
            return;
        }
        $path = explode(\'/\', $_GET[\'api\']);
        if (count($path) == 0 || $path[0] == "") {
            echo \'Error. Path missing.\';
            return;
        }
        
        $param1 = "";
        if (count($path) > 1) {
            $parametro = $path[1];
        }

        if ($path[0] != \'info\') {
            return;
        }

        if ($param1 == "") {
            echo \'error\';
            return;
        }

        $id = intval($parametro);
        $selected = new '.ucfirst($objeto->getNome()).'();
        $selected->setId($id);
        $selected = $this->dao->pesquisaPorId($selected);

        if ($selected == null) {
            return;
        }

        $body = file_get_contents(\'php://input\');
        $jsonBody = json_decode($body, true);
        
        ';
        foreach($objeto->getAtributos() as $atributo){
            if($atributo->tipoListado()){
                $codigo .= '
        if (isset($jsonBody[\''.$atributo->getNomeSnakeCase().'\'])) {
            $selected->set'.ucfirst($atributo->getNome()).'($jsonBody[\''.$atributo->getNomeSnakeCase().'\']);
        }
                    
';
            }
        }
  
        $codigo .= '
        if ($this->dao->update($selected)) 
                {
			echo \'Sucesso\';
		} else {
			echo \'Falha\';
		}
    }

';
        return $codigo;
    }
    public function post(Objeto $objeto):string
    {  
        
        $atributosComuns = array();
        $atributosObjetos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->tipoListado()){
                $atributosComuns[] = $atributo;
            }
            else if($atributo->isObjeto()){
                $atributosObjetos[] = $atributo;
                
            }
        }
        $codigo = '


    public function restPOST()
    {
        if ($_SERVER[\'REQUEST_METHOD\'] != \'POST\') {
            return;
        }
        if (! array_key_exists(\'path\', $_GET)) {
            echo \'Error. Path missing.\';
            return;
        }

        $path = explode(\'/\', $_GET[\'path\']);

        if (count($path) == 0 || $path[0] == "") {
            echo \'Error. Path missing.\';
            return;
        }

        $body = file_get_contents(\'php://input\');
        $jsonBody = json_decode($body, true);

        if (! ( ';
        $i = 0;
        $numDeComunsSemPK = 0;
        $listIsset = array();
        foreach ($atributosComuns as $atributo) {
            $i ++;
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            $numDeComunsSemPK++;
            
            $listIsset[] = 'isset ( $jsonBody [\'' . $atributo->getNome() . '\'] )';

        }
        $codigo .= implode(" && ", $listIsset);
        $i = 0;
        foreach($atributosObjetos as $atributoObjeto){
            foreach($this->software->getObjetos() as $objeto3){
                if($atributoObjeto->getTipo() == $objeto3->getNome())
                {
                    foreach($objeto3->getAtributos() as $atributo2){
                        if($atributo2->getIndice() == Atributo::INDICE_PRIMARY){
                            
                            if($numDeComunsSemPK > 0 && $i == 0){
                                $codigo .= ' && ';
                            }else if($i > 0){
                                $codigo .= ' && ';
                            }
                            $i++;
                            $codigo .= ' isset($_POST [\'' . $atributoObjeto->getNomeSnakeCase() . '\'])';
                            break;
                        }
                    }
                    break;
                }
            }
        }
        
        $codigo .= ')) {
			echo "Incompleto";
			return;
		}

        $adicionado = new '.ucfirst($objeto->getNome()).'();';
        foreach($objeto->getAtributos() as $atributo){
            if($atributo->tipoListado()){
                
                $codigo .= '
        $adicionado->set'.ucfirst($atributo->getNome()).'($jsonBody[\''.$atributo->getNomeSnakeCase().'\']);
';
            }
        }
  
        $codigo .= '
        if ($this->dao->inserir($adicionado)) 
                {
			echo \' Sucesso\';
		} else {
			echo \'Falha \';
		}
    }       

';
        return $codigo;
    }
    private function geraControllers(Objeto $objeto)
    {
        $codigo = '';        
        $codigo = '<?php
            
/**
 * Classe feita para manipulação do objeto ' . $objeto->getNome() . 'ApiRestController
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */

namespace '.$this->software->getNome().'\\\\controller;
use '.$this->software->getNome().'\\\\model\\\\'.ucfirst($objeto->getNome()).';
use '.$this->software->getNome().'\\\\dao\\\\'.ucfirst($objeto->getNome()).'DAO;

class ' . ucfirst($objeto->getNome()) . 'ApiRestController {


    protected $dao;';
        $codigo .= $this->construct($objeto);
        
        $codigo .= $this->geraMain();
        $codigo .= $this->get($objeto);
        $codigo .= $this->delete($objeto);
        $codigo .= $this->put($objeto);
        $codigo .= $this->post($objeto);

        
        $codigo .= '
}
?>';
        $caminho = ucfirst($objeto->getNome()).'ApiRestController.php';
        $this->listaDeArquivos[$caminho] = $codigo;
    }
    
}


?>