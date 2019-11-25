<?php	

/**
 * Classe feita para manipulação do objeto Software
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class SoftwareController {
	private $post;
	private $view;
    private $dao;

    public static function main(){
        $controller = new SoftwareController();
        if (!(isset($_GET['cadastrar']) || isset($_GET['selecionar']) || isset($_GET['editar']) || isset($_GET['deletar']) )){
            $controller->listar();
        }
        $controller->cadastrar();
        $controller->selecionar();
        $controller->editar();
        $controller->deletar();
    }
	public function __construct(){
		$this->dao = new SoftwareDAO();
		$this->view = new SoftwareView();
		foreach($_POST as $chave => $valor){
			$this->post[$chave] = $valor;
		}
	}
	public function listar() {
		$softwareDao = new SoftwareDAO ();
		$lista = $softwareDao->retornaLista ();
		$this->view->exibirLista($lista);
	}			
    public function selecionar(){
	    if(!isset($_GET['selecionar'])){
	        return;
	    }
        $selecionado = new Software();
	    $selecionado->setId($_GET['selecionar']);
	    $this->dao->pesquisaPorId($selecionado);
	    $this->view->mostrarSelecionado($selecionado);
    }
	public function cadastrar() {
        if(!isset($_GET['cadastrar'])){
            return;
        }
		
        if(!isset($this->post['enviar_software'])){
            $this->view->mostraFormInserir();   
		    return;
		}
		if (! ( isset ( $this->post ['nome'] ))) {
			echo "Incompleto";
			return;
		}
	
		$software = new Software ();		
		$software->setNome ( $this->post ['nome'] );	
		
		if ($this->dao->inserir ( $software )) 
        {
			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=software">';
	}
    public function editar(){
	    if(!isset($_GET['editar'])){
	        return;
	    }
        $selecionado = new Software();
	    $selecionado->setId($_GET['editar']);
	    $this->dao->pesquisaPorId($selecionado);
	    
        if(!isset($_POST['editar_software'])){
            $this->view->mostraFormEditar($selecionado);
            return;
        }

		if (! ( isset ( $this->post ['nome'] ))) {
			echo "Incompleto";
			return;
		}
		
		$selecionado->setNome ( $this->post ['nome'] );	
		
		if ($this->dao->atualizar ($selecionado )) 
        {

			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; URL=index.php?pagina=software">';

    }
    public function deletar(){
	    if(!isset($_GET['deletar'])){
	        return;
	    }
        $selecionado = new Software();
	    $selecionado->setId($_GET['deletar']);
	    $this->dao->pesquisaPorId($selecionado);
        if(!isset($_POST['deletar_software'])){
            $this->view->confirmarDeletar($selecionado);
            return;
        }
        if($this->dao->excluir($selecionado)){
            echo "excluido com sucesso";
        }else{
            echo "Errou";
        }
    	echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=software">';    
    }
	public function listarJSON() 
    {
		$softwareDao = new SoftwareDAO ();
		$lista = $softwareDao->retornaLista ();
		$listagem = array ();
		foreach ( $lista as $linha ) {
			$listagem ['lista'] [] = array (
					'id' => $linha->getId (), 
					'nome' => $linha->getNome ()
						
						
			);
		}
		echo json_encode ( $listagem );
	}			

	
		
}
?>