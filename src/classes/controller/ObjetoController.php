<?php

/**
 * Classe feita para manipulação do objeto Objeto
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson UchÃ´a Ponte <j.pontee@gmail.com>
 */
class ObjetoController {
	private $post;
	private $view;
	public function ObjetoController() {
		$this->view = new ObjetoView ();
		foreach ( $_POST as $chave => $valor ) {
			$this->post [$chave] = $valor;
		}
	}
	public function cadastrar() {
		$software = new Software ();
		$software->setId ( $_GET ['idsoftware'] );
		$this->view->mostraFormInserir ( $software );
		if (! isset ( $this->post ['enviar_objeto'] )) {
			return;
		}
		if (! (isset ( $this->post ['nome'] ))) {
			echo "Incompleto";
			return;
		}
		
		$objeto = new Objeto ();
		$objeto->setNome ( $this->post ['nome'] );
		$objetoDao = new ObjetoDAO ();
		if ($objetoDao->inserir ( $objeto, $software )) {
			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
		echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=software.php?idsoftware=' . $_GET ['idsoftware'] . '">';
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
			)
			;
		}
		echo json_encode ( $listagem );
	}
	public function listar() {
		$objetoDao = new ObjetoDAO ();
		$lista = $objetoDao->retornaLista ();
		echo '<table border="1">';
		echo '<th>Id</th>';
		echo '<th>Nome</th>';
		
		foreach ( $lista as $objeto ) {
			echo '<tr>';
			echo '<td>' . $objeto->getId () . '</td>';
			echo '<td>' . $objeto->getNome () . '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
}
?>