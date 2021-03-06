<?php	

namespace GetCrudByUML\controller;
use GetCrudByUML\dao\AtributoDAO;
use GetCrudByUML\dao\ObjetoDAO;
use GetCrudByUML\dao\SoftwareDAO;
use GetCrudByUML\dao\UsuarioDAO;
use GetCrudByUML\model\Objeto;
use GetCrudByUML\model\Software;
use GetCrudByUML\model\Usuario;
use GetCrudByUML\util\Sessao;
use GetCrudByUML\view\ObjetoView;
/**
 * Classe feita para manipulação do objeto Objeto
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class ObjetoController {
	private $post;
	private $view;
    private $dao;
    private $selecionado;

    public static function main(){
        $controller = new ObjetoController();
        if(isset($_GET['selecionar'])){
            $controller->selecionar();
            return;
        }else  if(isset($_GET['editar'])){
            $controller->editar();
        }else if($_GET['deletar']){
            $controller->deletar();
        }

    }
	public function __construct(){
	    $this->selecionado = new Objeto();
		$this->dao = new ObjetoDAO();
		$this->view = new ObjetoView();
		foreach($_POST as $chave => $valor){
			$this->post[$chave] = $valor;
		}
	}
			
    public function selecionar(){
	    if(!isset($_GET['selecionar'])){
	        return;
	    }
        
	    $this->selecionado->setId($_GET['selecionar']);
	    $sessao = new Sessao();
	    $usuario = new Usuario();
	    $usuario->setId($sessao->getIdUsuario());
	    
	    $usuarioDao = new UsuarioDAO($this->dao->getConexao());
	    
	    if(!$usuarioDao->verificarPosseObjeto($usuario, $this->selecionado)){
	        echo 'Selecione um objeto que pertence a um software seu';
	        return;
	    }
	    
	    
	    
	    $this->dao->pesquisaPorId($this->selecionado);
	    
	    $atributoDao = new AtributoDAO($this->dao->getConexao());
	    $atributoDao->pesquisaPorIdObjeto($this->selecionado);
	    $atributoController = new AtributoController();
	    $software = $this->dao->softwareDoObjeto($this->selecionado);
	    
	    
	    echo '<div class="row">';
	    echo '<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">';
	    echo '<h3>Software: '.$software->getNome().' - Objeto: '.$this->selecionado->getNome().'</h3>';
	    echo '<button type="button" class="btn btn-success m-2" data-toggle="modal" data-target="#modalEscrever">';
	    
	    if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
	        echo 'Pegar Código';
	    }else{
	        echo 'Get Code';
	    }
	    echo '</button>';
	    echo '<a href="?pagina=software&deletar='.$software->getId().'" class="btn btn-danger m-2">Delete Software</a>';
	    echo '<a href="?pagina=software&selecionar='.$software->getId().'" class="btn btn-success m-2">'.$software->getNome().'</a>';
	    echo '</div>';
	    echo '<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">';
	    $atributoController->cadastrar($this->selecionado);

	    echo '</div>';

	    echo '</div><hr>';
	    
	    $this->view->mostrarSelecionado($this->selecionado);
	    $softwareController = new SoftwareController();
	    $softwareController->modalEscrever($software);
	    
    }
	public function cadastrar(Software $software = null) 
	{
	    
        if(!isset($this->post['enviar_objeto'])){
            $listaSoftware = array();
            if($software == null){
                $softwareDao = new SoftwareDAO($this->dao->getConexao());
                $listaSoftware = $softwareDao->retornaLista();
            }
            
            
            $this->view->mostraFormInserir($listaSoftware);

            return;
		}
		if (! ( isset ( $this->post ['nome'] ))) {
			echo "Incompleto";
			return;
		}
		if($this->post['nome'] == ''){
		    echo 'Incompleto';
		    return;
		}

		$objeto = new Objeto ();		
		$objeto->setNome ( ucfirst($this->post ['nome']) );
		if($software == null){
		    if(isset($this->post['idsoftware'])){
		        $software = new Software();
		        $software->setId($this->post['idsoftware']);
		    }else{
		        echo "incompleto";
		        return;
		    }
		}
		if ($this->dao->inserir ( $objeto, $software )) 
		{
		    echo '
<div class="alert alert-success" role="alert">
  ';
		    if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
		        echo 'Sucesso';
		    }else{
		        echo 'Success';
		    }
		    echo '
</div>
';
		} else {
		    echo '
<div class="alert alert-danger" role="alert">
  ';
		    if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
		        echo 'Erro';
		    }else{
		        echo 'Error';
		    }
		    echo '
</div>
';
		}
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=software&selecionar='.$software->getId().'">';
	}
    public function editar(){
	    if(!isset($_GET['editar'])){
	        return;
	    }
	    
	    $this->selecionado->setId($_GET['editar']);
	    $sessao = new Sessao();
	    $usuario = new Usuario();
	    $usuario->setId($sessao->getIdUsuario());
	    
	    $usuarioDao = new UsuarioDAO($this->dao->getConexao());
	    
	    if(!$usuarioDao->verificarPosseObjeto($usuario, $this->selecionado)){
	        echo 'Selecione um objeto que pertence a um software seu';
	        return;
	    }
	    
	    $this->dao->pesquisaPorId($this->selecionado);
	    
        if(!isset($_POST['editar_objeto'])){
            $this->view->mostraFormEditar($this->selecionado);
            return;
        }

		if (!isset ( $this->post ['nome'] )) {
			echo "Incompleto";
			return;
		}
		
		$this->selecionado->setNome ( $this->post ['nome'] );		
			
		$software = $this->dao->softwareDoObjeto($this->selecionado);
		if ($this->dao->atualizar ($this->selecionado )) 
        {

			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; URL=index.php?pagina=software&selecionar='.$software->getId().'">';

    }
    public function deletar(){
	    if(!isset($_GET['deletar'])){
	        return;
	    }
	    $this->selecionado->setId($_GET['deletar']);
	    $sessao = new Sessao();
	    $usuario = new Usuario();
	    $usuario->setId($sessao->getIdUsuario());
	    
	    $usuarioDao = new UsuarioDAO($this->dao->getConexao());
	    
	    if(!$usuarioDao->verificarPosseObjeto($usuario, $this->selecionado)){
	        echo 'Selecione um objeto que pertence a um software seu';
	        return;
	    }
	    
	    $this->dao->pesquisaPorId($this->selecionado);
        if(!isset($_POST['deletar_objeto'])){
            $this->view->confirmarDeletar($this->selecionado);
            return;
        }
        $software = $this->dao->softwareDoObjeto($this->selecionado);
        if($this->dao->excluir($this->selecionado)){
            echo "excluido com sucesso";
        }else{
            echo "Errou";
        }
    	echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=software&selecionar='.$software->getId().'">';    
    }
	public function listarJSON() 
    {
		$objetoDao = new ObjetoDAO ();
		$lista = $objetoDao->retornaLista ();
		$listagem = array ();
		foreach ( $lista as $linha ) {
			$listagem ['lista'] [] = array (
					'id' => $linha->getId (), 
					'nome' => $linha->getNome (), 
					'idsoftware' => $linha->getIdsoftware ()
						
						
			);
		}
		echo json_encode ( $listagem );
	}			

	
		
}
?>