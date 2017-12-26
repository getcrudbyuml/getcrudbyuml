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
		if (!isset($_GET ['idsoftware'])) {
			return;
		}
		$softwaredao = new SoftwareDAO();
		$software = new Software ();
		$software->setId ( $_GET ['idsoftware'] );
		$software = $softwaredao->retornaPorId($software);
		echo '<h1>'.$software->getNome().'</h1>';
		echo '<h2>Objetos:</h2>';
		if($software->getObjetos()){
				
			foreach ($software->getObjetos() as $objeto){
					
				echo '<div class="classe">
							<h1>'.$objeto->getNome().'<img src="images/delete.png" alt="" width="20"/></h1>
								<ul>';
				foreach ($objeto->getAtributos() as $atributo){
		
					if($atributo->getIndice() == "padrao"){
						echo '		<li>'.$atributo->getNome().' - '.$atributo->getTipo().'<a href="deletaratributo.php?id_atributo='.$atributo->getId().'"> <img src="images/delete.png" alt="" width="20"/></a></li>';
					}else
					{
						echo '		<li>'.$atributo->getNome().' - '.$atributo->getTipo().'; '.$atributo->getIndice() .'<img src="images/delete.png" alt="" width="20"/></li>';
					}
					 
		
		
				}
				echo '
		
				
		
		
		
								</ul>
		
		
							</div>
							';
			}
		
		}

		echo '<a href="escreversoftware.php?idsoftware='. $_GET ['idsoftware'].'">Escrever Software</a>';
			
		
	}
}
?>