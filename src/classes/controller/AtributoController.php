<?php	

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
            $controller->selecionar();
            return;
        }
        if(isset($_GET['deletar'])){
            $controller->deletar();
            return;
        }
        if(isset($_GET['editar'])){
            $controller->editar();
            return;
        }
        
        $controller->cadastrar();
        $controller->listar();

    }
	public function __construct(){
		$this->dao = new AtributoDAO();
		$this->view = new AtributoView();
		foreach($_POST as $chave => $valor){
			$this->post[$chave] = $valor;
		}
	}
	public function listar() {
		$atributoDao = new AtributoDAO ();
		$lista = $atributoDao->retornaLista ();
		$this->view->exibirLista($lista);
	}			
    public function selecionar(){
	    if(!isset($_GET['selecionar'])){
	        return;
	    }
        $selecionado = new Atributo();
	    $selecionado->setId($_GET['selecionar']);
	    $this->dao->pesquisaPorId($selecionado);
	    $this->view->mostrarSelecionado($selecionado);
    }
	public function cadastrar(Objeto $objeto = null) {		
        if(!isset($this->post['enviar_atributo'])){
            $listaObjetos = array();
            if($objeto == null){
                $objetoDao = new ObjetoDAO($this->dao->getConexao());
                $listaObjetos = $objetoDao->retornaLista();
            }
            $this->view->mostraFormInserir($listaObjetos);   
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
			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=objeto&selecionar='.$objeto->getId().'">';
	}
    public function editar(){
	    if(!isset($_GET['editar'])){
	        return;
	    }
        $selecionado = new Atributo();
	    $selecionado->setId($_GET['editar']);
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
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; URL=index.php?pagina=atributo">';

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
        if($this->dao->excluir($selecionado)){
            echo "excluido com sucesso";
        }else{
            echo "Errou";
        }
    	echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=atributo">';    
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