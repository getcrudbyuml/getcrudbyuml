<?php	

/**
 * Classe feita para manipulação do objeto Objeto
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson UchÃ´a Ponte <j.pontee@gmail.com>
 */
class ObjetoController {
	private $post;
	private $view;
	public function ObjetoController(){		
		$this->view = new ObjetoView();
		foreach($_POST as $chave => $valor){
			$this->post[$chave] = $valor;
		}
	}
	public function cadastrar() {
		$this->view->mostraFormInserir();
		if(!isset($this->post['enviar'])){
		    return;
		}
		if (! ( isset ( $this->post ['nome'] ) && isset ( $this->post ['idsoftware'] ))) {
			echo "Incompleto";
			return;
		}
	
		$objeto = new Objeto ();		
		$objeto->setNome ( $this->post ['nome'] );		
		$objeto->setIdsoftware ( $this->post ['idsoftware'] );	
		$objetoDao = new ObjetoDAO ();
		if ($objetoDao->inserir ( $objeto )) {
			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
	}
				
	public function listarJSON() {
		$objetoDao = new ObjetoDAO ();
		$lista = $objetoDao->retornaLista ();
		$listagem ['lista'] = array ();
		foreach ( $lista as $linha ) {
			$listagem ['lista'] [] = array (
					'id' => $linha->getId (), 
					'nome' => $linha->getNome (), 
					'idsoftware' => $linha->getIdsoftware ()
						
						
			);
		}
		echo json_encode ( $listagem );
	}			
	public function listar() {
		$objetoDao = new ObjetoDAO ();
		$lista = $objetoDao->retornaLista ();
		echo '<table border="1">';
			echo '<th>Id</th>';
			echo '<th>Nome</th>';

		foreach ( $lista as $objeto) {
			echo '<tr>';
			echo '<td>'.$objeto->getId ().'</td>';
			echo '<td>'.$objeto->getNome ().'</td>';
			echo '</tr>';
		}
		echo '</table>';
		
		
	}			
	
		
}
?>