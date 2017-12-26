<?php	

/**
 * Classe feita para manipulação do objeto Atributo
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson UchÃ´a Ponte <j.pontee@gmail.com>
 */
class AtributoController {
	private $post;
	private $view;
	public function AtributoController(){		
		$this->view = new AtributoView();
		foreach($_POST as $chave => $valor){
			$this->post[$chave] = $valor;
		}
	}
	public function cadastrar() {
		$this->view->mostraFormInserir();
		if (! ( isset ( $this->post ['nome'] ) && isset ( $this->post ['tipo'] ) && isset ( $this->post ['relacionamento'] ) && isset ( $this->post ['idobjeto'] ))) {
			echo "Incompleto";
			return;
		}
	
		$atributo = new Atributo ();		
		$atributo->setNome ( $this->post ['nome'] );		
		$atributo->setTipo ( $this->post ['tipo'] );		
		$atributo->setRelacionamento ( $this->post ['relacionamento'] );		
		$atributo->setIdobjeto ( $this->post ['idobjeto'] );	
		$atributoDao = new AtributoDAO ();
		if ($atributoDao->inserir ( $atributo )) {
			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
	}
				
	public function listarJSON() {
		$atributoDao = new AtributoDAO ();
		$lista = $atributoDao->retornaLista ();
		$listagem ['lista'] = array ();
		foreach ( $lista as $linha ) {
			$listagem ['lista'] [] = array (
					'id' => $linha->getId (), 
					'nome' => $linha->getNome (), 
					'tipo' => $linha->getTipo (), 
					'relacionamento' => $linha->getRelacionamento (), 
					'idobjeto' => $linha->getIdobjeto ()
						
						
			);
		}
		echo json_encode ( $listagem );
	}			
	public function listar() {
		$atributoDao = new AtributoDAO ();
		$lista = $atributoDao->retornaLista ();
		echo '<table border="1">';
			echo '<th>Id</th>';
			echo '<th>Nome</th>';
			echo '<th>Tipo</th>';
			echo '<th>Relacionamento</th>';
			echo '<th>Idobjeto</th>';
		foreach ( $lista as $atributo) {
			echo '<tr>';		
		
			echo '<td>'.$atributo->getId ().'</td>';
			echo '<td>'.$atributo->getNome ().'</td>';
			echo '<td>'.$atributo->getTipo ().'</td>';
			echo '<td>'.$atributo->getRelacionamento ().'</td>';
			echo '<td>'.$atributo->getIdobjeto ().'</td>';
			echo '</tr>';
		}
		echo '</table>';
		
		
	}			
	
		
}
?>