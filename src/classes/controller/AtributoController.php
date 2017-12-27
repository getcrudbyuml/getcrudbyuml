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
		if(!(isset($_GET['idobjeto']))){
			return;
		}
		$objeto = new Objeto();
		$objeto->setId($_GET['idobjeto']);
		
		$this->view->mostraFormInserir($objeto);
		if(!(isset($this->post['envia_atributo']))){
			return;
		}
		if (strlen ( $this->post ['nome'] ) < 2 || ! ( isset ( $this->post ['nome'] ) && isset ( $this->post ['tipo'] ) && isset ( $this->post ['indice'] ) && isset ( $this->post ['id_objeto'] ))) {
			echo "Incompleto";
			return;
		}
	
		$atributo = new Atributo ();		
		$atributo->setNome ( $this->post ['nome'] );		
		$atributo->setTipo ( $this->post ['tipo'] );		
		$atributo->setIndice( $this->post ['indice'] );		
		
		$atributoDao = new AtributoDAO ();
		if ($atributoDao->inserir ( $atributo , $objeto)) {
			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
		echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=atributo&idobjeto=' . $_GET ['idobjeto'] . '">';
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
		if (!isset($_GET ['idobjeto'])) {
			return;
		}
		$objeto = new Objeto();
		$objeto->setId($_GET['idobjeto']);
		$objetoDao = new ObjetoDAO();
		$objetoDao->retornaPorId($objeto);
		
		echo '<div class="classe">
							<h1><a href="objeto.php?idobjeto='.$objeto->getId().'">'.$objeto->getNome().'</a><img src="images/delete.png" alt="" width="20"/></h1>
								<ul>';
		foreach ($objeto->getAtributos() as $atributo){
		
			
			echo '		<li>'.$atributo->getNome().' - '.$atributo->getTipo().'<a href="deletaratributo.php?id_atributo='.$atributo->getId().'"> <img src="images/delete.png" alt="" width="20"/></a></li>';
			
		
		}
		$idSoftware = $objetoDao->retornaIdDoSoftware($objeto);
		echo '</ul>
				
				</div>
				
				<a href="index.php?pagina=objeto&idsoftware='.$idSoftware.'">Voltar</a>
							';
		
		
	}			
	
		
}
?>