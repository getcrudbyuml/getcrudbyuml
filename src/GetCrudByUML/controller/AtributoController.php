<?php	
namespace GetCrudByUML\controller;
use GetCrudByUML\model\Atributo as Atributo;
use GetCrudByUML\view\AtributoView as AtributoView;
use GetCrudByUML\dao\AtributoDAO as AtributoDAO;
use GetCrudByUML\model\Usuario as Usuario;
use GetCrudByUML\model\Objeto as Objeto;
use GetCrudByUML\dao\UsuarioDAO as UsuarioDAO;
use GetCrudByUML\dao\ObjetoDAO as ObjetoDAO;
use GetCrudByUML\util\Sessao as Sessao;


/**
 * Classe feita para manipulação do objeto Atributo
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class AtributoController {
	private $post;
	private $view;
    private $dao;

    public static function main(){
        $controller = new AtributoController();
        if(isset($_GET['selecionar'])){
            $controller->editar();
            $controller->selecionar();
            return;
        }else if(isset($_GET['deletar'])){
            $controller->deletar();
            return;
        }
        

    }
	public function __construct(){
		$this->dao = new AtributoDAO();
		$this->view = new AtributoView();
		foreach($_POST as $chave => $valor){
			$this->post[$chave] = $valor;
		}
	}
			
    public function selecionar(){
	    if(!isset($_GET['selecionar'])){
	        return;
	    }
	    $usuarioDao = new UsuarioDAO($this->dao->getConexao());
	    
	    $sessao = new Sessao();
	    $usuario = new Usuario();
	    $usuario->setId($sessao->getIdUsuario());
	    
        $selecionado = new Atributo();
	    $selecionado->setId($_GET['selecionar']);
	    if(!$usuarioDao->verificarPosseAtributo($usuario, $selecionado)){
	        echo "Este atributo pertence a outro usuário";
	        return;
	    }
	    
	    $this->dao->pesquisaPorId($selecionado);
	    $software = $this->dao->softwareDoAtributo($selecionado);
	    echo '<div class="row">';
	    echo '<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">';
	    echo '<h3>Software: '.$software->getNome().' - Atributo '.$selecionado->getNome().'</h3>';
	    echo '<button type="button" class="btn btn-success m-2" data-toggle="modal" data-target="#modalEscrever">';
	    
	    if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
	        echo 'Pegar Código';
	    }else{
	        echo 'Get Code';
	    }
	    echo '</button>';
	    
	    
	    echo '<a href="?pagina=software&deletar='.$software->getId().'" class="btn btn-danger m-2">Delete Software</a>';
	    echo '<a href="?pagina=software&selecionar='.$software->getId().'" class="btn btn-success m-2">Voltar para '.$software->getNome().'</a>';
	    echo '</div>';
	    echo '<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">';
	   
	    $this->view->mostraFormEditar($selecionado);
	    
	    echo '</div>';
	    echo '</div>';
	    echo '<hr>';
	    
	    
	    $this->view->mostrarSelecionado($selecionado);
	    echo '<div class="row justify-content-center">
                            
                            
                            <a href="?pagina=atributo&deletar='.$selecionado->getId().'" class="btn btn-danger">Deletar</a>
                                
                </div>';
    }
	public function cadastrar(Objeto $objeto) {	
	    
        if(!isset($this->post['enviar_atributo'])){
            
            $objetoDao = new ObjetoDAO($this->dao->getConexao());
            $listaObjetos = array();
            $listaTipos = array();
            $software = $objetoDao->softwareDoObjeto($objeto);
            $listaTipos = $objetoDao->pesquisaPorIdSoftware($software);
            $this->view->mostraFormInserir($listaObjetos, $listaTipos); 
            
		    return;
		}
		if (! ( isset ( $this->post ['nome'] ) && isset ( $this->post ['tipo'] ) && isset ( $this->post ['indice'] ))) {
			echo "Incompleto";
			return;
		}
	
		$atributo = new Atributo ();		
		$atributo->setNome ( $this->post ['nome'] );		
		$atributo->setTipo ( $this->post ['tipo'] );		
		$atributo->setIndice ( $this->post ['indice'] );		
		if($objeto == null){
		    if(isset ( $this->post ['idobjeto'] )){
		        $objeto = new Objeto();
		        $objeto->setId($this->post['idobjeto']);
		    }else{
		        echo "Incompleto";
		        return;
		    }
		}
		
		
		if ($this->dao->inserir ( $atributo, $objeto )) 
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
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=objeto&selecionar='.$objeto->getId().'">';
	}
    public function editar(){
	    if(!isset($_POST['editar_atributo'])){
	        return;
	    }
	    $usuarioDao = new UsuarioDAO($this->dao->getConexao());
	    
	    $sessao = new Sessao();
	    $usuario = new Usuario();
	    $usuario->setId($sessao->getIdUsuario());
	    
	    $selecionado = new Atributo();
	    $selecionado->setId($_GET['selecionar']);
	    
	    if(!$usuarioDao->verificarPosseAtributo($usuario, $selecionado)){
	        echo "Este atributo pertence a outro usuário";
	        return;
	    }
	    
        
	    $this->dao->pesquisaPorId($selecionado);
	    
        if(!isset($_POST['editar_atributo'])){
            $this->view->mostraFormEditar($selecionado);
            return;
        }

		if (! ( isset ( $this->post ['nome'] ) && isset ( $this->post ['tipo'] ) && isset ( $this->post ['indice'] ))) {
			echo "Incompleto";
			return;
		}
		
		$selecionado->setNome ( $this->post ['nome'] );		
		$selecionado->setTipo ( $this->post ['tipo'] );		
		$selecionado->setIndice ( $this->post ['indice'] );		
		
		if ($this->dao->atualizar ($selecionado )) 
        {

			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; URL=index.php?pagina=atributo&selecionar='.$_GET['selecionar'].'">';

    }
    public function deletar(){
	    if(!isset($_GET['deletar'])){
	        return;
	    }
        $selecionado = new Atributo();
	    $selecionado->setId($_GET['deletar']);
	    $this->dao->pesquisaPorId($selecionado);
        if(!isset($_POST['deletar_atributo'])){
            $this->view->confirmarDeletar($selecionado);
            return;
        }
        $software = $this->dao->softwareDoAtributo($selecionado);
        if($this->dao->excluir($selecionado)){
            echo "Success";
        }else{
            echo "Errou";
        }
    	echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=software&selecionar='.$software->getId().'">';    
    }
	public function listarJSON() 
    {
		$atributoDao = new AtributoDAO ();
		$lista = $atributoDao->retornaLista ();
		$listagem = array ();
		foreach ( $lista as $linha ) {
			$listagem ['lista'] [] = array (
					'id' => $linha->getId (), 
					'nome' => $linha->getNome (), 
					'tipo' => $linha->getTipo (), 
					'indice' => $linha->getIndice (), 
					'idobjeto' => $linha->getIdobjeto ()
						
						
			);
		}
		echo json_encode ( $listagem );
	}			

	
		
}
?>