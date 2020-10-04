<?php

namespace GetCrudByUML\controller;
use GetCrudByUML\model\Auditoria;
use GetCrudByUML\dao\AuditoriaDAO;
use GetCrudByUML\view\AuditoriaView;

/**
 * Classe feita para manipulação do objeto Auditoria
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class AuditoriaController {
	private $post;
	private $view;
    private $dao;
    
    public static function main(){
        $controller = new AuditoriaController();
        if (isset($_GET['selecionar'])){
            echo '<div class="row justify-content-center">';
                $controller->selecionar();
            echo '</div>';
            return;
        }
        echo '
		<div class="row justify-content-center">';
        echo '<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">';
        $controller->listar();
        echo '</div>';
        echo '<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">';
        if(isset($_GET['editar'])){
            $controller->editar();
        }else if(isset($_GET['deletar'])){
            $controller->deletar();
	    }else{
            $controller->cadastrar();
        }
        echo '</div>';
        echo '</div>';
            
    }
    public static function mainREST()
    {
        if(!isset($_SERVER['PHP_AUTH_USER'])){
            header("WWW-Authenticate: Basic realm=\"Private Area\" ");
            header("HTTP/1.0 401 Unauthorized");
            echo '{"erro":[{"status":"error","message":"Authentication failed"}]}';
            return;
        }
        if($_SERVER['PHP_AUTH_USER'] == 'usuario' && ($_SERVER['PHP_AUTH_PW'] == 'senha@12')){
            header('Content-type: application/json');
            $controller = new AuditoriaController();
            $controller->restGET();
            //$controller->restPOST();
            //$controller->restPUT();
            $controller->resDELETE();
        }else{
            header("WWW-Authenticate: Basic realm=\"Private Area\" ");
            header("HTTP/1.0 401 Unauthorized");
            echo '{"erro":[{"status":"error","message":"Authentication failed"}]}';
        }

    }

	public function __construct(){
		$this->dao = new AuditoriaDAO();
		$this->view = new AuditoriaView();
		foreach($_POST as $chave => $valor){
			$this->post[$chave] = $valor;
		}
	}
	public function listar() {
		$auditoriaDao = new AuditoriaDAO ();
		$lista = $auditoriaDao->retornaLista ();
		$this->view->exibirLista($lista);
	}
    public function selecionar(){
	    if(!isset($_GET['selecionar'])){
	        return;
	    }
        $selecionado = new Auditoria();
	    $selecionado->setId($_GET['selecionar']);
	        
        $this->dao->preenchePorId($selecionado);

        echo '<div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">';
	    $this->view->mostrarSelecionado($selecionado);
        echo '</div>';
            

            
    }
            
	public function cadastrar() {
            
        if(!isset($this->post['enviar_auditoria'])){
            $this->view->mostraFormInserir();
		    return;
		}
		if (! ( isset ( $this->post ['pagina'] ) && isset ( $this->post ['ipVisitante'] ) && isset ( $this->post ['infoSessao'] ) && isset ( $this->post ['data'] ))) {
			echo "Incompleto";
			return;
		}
            
		$auditoria = new Auditoria ();
		$auditoria->setPagina ( $this->post ['pagina'] );
		$auditoria->setIpVisitante ( $this->post ['ipVisitante'] );
		$auditoria->setInfoSessao ( $this->post ['infoSessao'] );
		$auditoria->setData ( $this->post ['data'] );
            
		if ($this->dao->inserir ( $auditoria ))
        {
			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=auditoria">';
	}
    public function editar(){
	    if(!isset($_GET['editar'])){
	        return;
	    }
        $selecionado = new Auditoria();
	    $selecionado->setId($_GET['editar']);
	    $this->dao->pesquisaPorId($selecionado);
	        
        if(!isset($_POST['editar_auditoria'])){
            $this->view->mostraFormEditar($selecionado);
            return;
        }
            
		if (! ( isset ( $this->post ['pagina'] ) && isset ( $this->post ['ipVisitante'] ) && isset ( $this->post ['infoSessao'] ) && isset ( $this->post ['data'] ))) {
			echo "Incompleto";
			return;
		}

		$selecionado->setPagina ( $this->post ['pagina'] );
		$selecionado->setIpVisitante ( $this->post ['ipVisitante'] );
		$selecionado->setInfoSessao ( $this->post ['infoSessao'] );
		$selecionado->setData ( $this->post ['data'] );
            
		if ($this->dao->atualizar ($selecionado ))
        {
            
			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; URL=index.php?pagina=auditoria">';
            
    }
    public function deletar(){
	    if(!isset($_GET['deletar'])){
	        return;
	    }
        $selecionado = new Auditoria();
	    $selecionado->setId($_GET['deletar']);
	    $this->dao->pesquisaPorId($selecionado);
        if(!isset($_POST['deletar_auditoria'])){
            $this->view->confirmarDeletar($selecionado);
            return;
        }
        if($this->dao->excluir($selecionado)){
            echo "excluido com sucesso";
        }else{
            echo "Errou";
        }
    	echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=auditoria">';
    }
	public function restGET()
    {

        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            return;
        }

        if(!isset($_REQUEST['api'])){
            return;
        }
        $url = explode("/", $_REQUEST['api']);
        if (count($url) == 0 || $url[0] == "") {
            return;
        }
        if ($url[1] != 'auditoria') {
            return;
        }

        if(isset($url[1])){
            $parametro = $url[1];
            $id = intval($parametro);
            $pesquisado = new Auditoria();
            $pesquisado->setId($id);
            $pesquisado = $this->dao->pesquisaPorId($pesquisado);
            if ($pesquisado == null) {
                echo "{}";
                return;
            }

            $pesquisado = array(
					'id' => $pesquisado->getId (), 
					'pagina' => $pesquisado->getPagina (), 
					'ipVisitante' => $pesquisado->getIpVisitante (), 
					'infoSessao' => $pesquisado->getInfoSessao (), 
					'data' => $pesquisado->getData ()
            
            
			);
            echo json_encode($pesquisado);
            return;
        }        
        $lista = $this->dao->retornaLista();
        $listagem = array();
        foreach ( $lista as $linha ) {
			$listagem ['lista'] [] = array (
					'id' => $linha->getId (), 
					'pagina' => $linha->getPagina (), 
					'ipVisitante' => $linha->getIpVisitante (), 
					'infoSessao' => $linha->getInfoSessao (), 
					'data' => $linha->getData ()
            
            
			);
		}
		echo json_encode ( $listagem );
    
		
		
		
		
	}

    public function resDELETE()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
            return;
        }
        $path = explode('/', $_GET['api']);
        $parametro = "";
        if (count($path) < 2) {
            return;
        }
        $parametro = $path[1];
        if ($parametro == "") {
            return;
        }
    
        $id = intval($parametro);
        $pesquisado = new Auditoria();
        $pesquisado->setId($id);
        $pesquisado = $this->dao->pesquisaPorId($pesquisado);
        if ($pesquisado == null) {
            echo "{}";
            return;
        }

        if($this->dao->excluir($pesquisado))
        {
            echo "{}";
            return;
        }
        
        echo "Erro.";
        
    }
    public function restPUT()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'PUT') {
            return;
        }

        if (! array_key_exists('api', $_GET)) {
            return;
        }
        $path = explode('/', $_GET['api']);
        if (count($path) == 0 || $path[0] == "") {
            echo 'Error. Path missing.';
            return;
        }
        
        $param1 = "";
        if (count($path) > 1) {
            $parametro = $path[1];
        }

        if ($path[0] != 'info') {
            return;
        }

        if ($param1 == "") {
            echo 'error';
            return;
        }

        $id = intval($parametro);
        $pesquisado = new Auditoria();
        $pesquisado->setId($id);
        $pesquisado = $this->dao->pesquisaPorId($pesquisado);

        if ($pesquisado == null) {
            return;
        }

        $body = file_get_contents('php://input');
        $jsonBody = json_decode($body, true);
        
        
        if (isset($jsonBody['id'])) {
            $pesquisado->setIdsuperficie($jsonBody['id']);
        }
                    

        if (isset($jsonBody['pagina'])) {
            $pesquisado->setPaginasuperficie($jsonBody['pagina']);
        }
                    

        if (isset($jsonBody['ip_visitante'])) {
            $pesquisado->setIpVisitantesuperficie($jsonBody['ip_visitante']);
        }
                    

        if (isset($jsonBody['info_sessao'])) {
            $pesquisado->setInfoSessaosuperficie($jsonBody['info_sessao']);
        }
                    

        if (isset($jsonBody['data'])) {
            $pesquisado->setDatasuperficie($jsonBody['data']);
        }
                    

        if ($this->dao->atualizar($pesquisado)) {
            echo "Sucesso";
        } else {
            echo "Erro";
        }
    }

    public function restPOST()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }
        if (! array_key_exists('path', $_GET)) {
            echo 'Error. Path missing.';
            return;
        }

        $path = explode('/', $_GET['path']);

        if (count($path) == 0 || $path[0] == "") {
            echo 'Error. Path missing.';
            return;
        }

        $body = file_get_contents('php://input');
        $jsonBody = json_decode($body, true);

        if (! ( isset ( $jsonBody ['pagina'] ) && isset ( $jsonBody ['ipVisitante'] ) && isset ( $jsonBody ['infoSessao'] ) && isset ( $jsonBody ['data'] ))) {
			echo "Incompleto";
			return;
		}

        $adicionado = new Auditoria();
        $adicionado->setId($jsonBody['id']);

        $adicionado->setPagina($jsonBody['pagina']);

        $adicionado->setIpVisitante($jsonBody['ip_visitante']);

        $adicionado->setInfoSessao($jsonBody['info_sessao']);

        $adicionado->setData($jsonBody['data']);

        if ($this->dao->inserir($adicionado)) {
            echo "Sucesso";
        } else {
            echo "Fracasso";
        }
    }            
            
		
}
?>