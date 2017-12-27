<?php

/**
 * Classe feita para manipulação do objeto Software
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class SoftwareController {
	private $post;
	private $view;
	public function SoftwareController() {
		$this->view = new SoftwareView ();
		foreach ( $_POST as $chave => $valor ) {
			$this->post [$chave] = $valor;
		}
	}
	public function cadastrar() {
		$this->view->mostraFormInserir ();
		if (! isset ( $this->post ['enviar'] )) {
			return;
		}
		
		if (! (isset ( $this->post ['nome'] )) || strlen ( $this->post ['nome'] ) < 2) {
			echo "Incompleto";
			return;
		}
		
		$software = new Software ();
		$software->setNome ( $this->post ['nome'] );
		$softwareDao = new SoftwareDAO ();
		$idSoftware = $softwareDao->inserir ( $software );
		
		if ($idSoftware) {
			echo "Sucesso";
			echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php">';
		} else {
			echo "Fracasso";
		}
	}
	public function listarJSON() {
		$softwareDao = new SoftwareDAO ();
		$lista = $softwareDao->retornaLista ();
		$listagem ['lista'] = array ();
		foreach ( $lista as $linha ) {
			$listagem ['lista'] [] = array (
					'id' => $linha->getId (),
					'nome' => $linha->getNome () 
			)
			;
		}
		echo json_encode ( $listagem );
	}
	public function listar() {
		$softwaredao = new SoftwareDAO ();
		$softwares = $softwaredao->retornaLista ();
		echo '<h1>Lista de Softwares</h1>';
		
		echo '<ul>';
		if ($softwares) {
			foreach ( $softwares as $software ) {
				echo '<li><a href="index.php?pagina=objeto&idsoftware=' . $software->getId () . '">' . $software->getNome () . '</a></li>';
			}
		} else {
			echo "Nenhum software adicionado ainda.";
		}
		echo '</ul>';
	}
}
?>