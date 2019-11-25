<?php	

/**
 * Classe feita para manipulação do objeto Objeto
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class ObjetoController {
	private $post;
	private $view;
    private $dao;

    public static function main(){
        $controller = new ObjetoController();
        if (!(isset($_GET['cadastrar']) || isset($_GET['selecionar']) || isset($_GET['editar']) || isset($_GET['deletar']) )){
            $controller->listar();
        }
        $controller->cadastrar();
        $controller->selecionar();
        $controller->editar();
        $controller->deletar();
    }
	public function __construct(){
		$this->dao = new ObjetoDAO();
		$this->view = new ObjetoView();
		foreach($_POST as $chave => $valor){
			$this->post[$chave] = $valor;
		}
	}
	public function listar() {
		$objetoDao = new ObjetoDAO ();
		$lista = $objetoDao->retornaLista ();
		$this->view->exibirLista($lista);
	}			
    public function selecionar(){
	    if(!isset($_GET['selecionar'])){
	        return;
	    }
        $selecionado = new Objeto();
	    $selecionado->setId($_GET['selecionar']);
	    $this->dao->pesquisaPorId($selecionado);
	    $this->view->mostrarSelecionado($selecionado);
    }
	public function cadastrar() {
        if(!isset($_GET['cadastrar'])){
            return;
        }
		
        if(!isset($this->post['enviar_objeto'])){
            $this->view->mostraFormInserir();   
		    return;
		}
		if (! ( isset ( $this->post ['nome'] ) && isset ( $this->post ['idsoftware'] ))) {
			echo "Incompleto";
			return;
		}
	
		$objeto = new Objeto ();		
		$objeto->setNome ( $this->post ['nome'] );		
		$objeto->setIdsoftware ( $this->post ['idsoftware'] );	
		
		if ($this->dao->inserir ( $objeto )) 
        {
			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=objeto">';
	}
    public function editar(){
	    if(!isset($_GET['editar'])){
	        return;
	    }
        $selecionado = new Objeto();
	    $selecionado->setId($_GET['editar']);
	    $this->dao->pesquisaPorId($selecionado);
	    
        if(!isset($_POST['editar_objeto'])){
            $this->view->mostraFormEditar($selecionado);
            return;
        }

		if (! ( isset ( $this->post ['nome'] ) && isset ( $this->post ['idsoftware'] ))) {
			echo "Incompleto";
			return;
		}
		
		$selecionado->setNome ( $this->post ['nome'] );		
		$selecionado->setIdsoftware ( $this->post ['idsoftware'] );	
		
		if ($this->dao->atualizar ($selecionado )) 
        {

			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; URL=index.php?pagina=objeto">';

    }
    public function deletar(){
	    if(!isset($_GET['deletar'])){
	        return;
	    }
        $selecionado = new Objeto();
	    $selecionado->setId($_GET['deletar']);
	    $this->dao->pesquisaPorId($selecionado);
        if(!isset($_POST['deletar_objeto'])){
            $this->view->confirmarDeletar($selecionado);
            return;
        }
        if($this->dao->excluir($selecionado)){
            echo "excluido com sucesso";
        }else{
            echo "Errou";
        }
    	echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=objeto">';    
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