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
    private $selecionado;

    public static function main(){
        $controller = new SoftwareController();

        if(isset($_GET['deletar'])){
            $controller->deletar();
            return;
        }
        if(isset($_GET['editar'])){
            $controller->editar();
            return;
        }

        $controller->cadastrar();

    

        
    }
	public function __construct(){
		$this->dao = new SoftwareDAO();
		$this->view = new SoftwareView();
		$this->selecionado = null;
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
	        echo 'Bem vindo ao Escritor de Software';
	        return;
	    }
	    
        $this->selecionado = new Software();
        $this->selecionado->setId($_GET['selecionar']);
        $this->dao->pesquisaPorId($this->selecionado);
	    $objetoDao = new ObjetoDAO($this->dao->getConexao());    
	    $objetoDao->pesquisaPorIdSoftware($this->selecionado);
        $atributoDao = new AtributoDAO($this->dao->getConexao());
        foreach($this->selecionado->getObjetos() as $objeto){
            $atributoDao->pesquisaPorIdObjeto($objeto);
        }
        echo '<div class="row">';
        echo '<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">';
        echo '<h3>Software: '.$this->selecionado->getNome().'</h3>';
        echo '<a href="?pagina=software&selecionar='.$this->selecionado->getId().'&escrever=1" class="btn btn-success m-3">Escrever Software</a>';
        echo '</div>';
        echo '<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">';
        $objetoController = new ObjetoController();
        $objetoController->cadastrar($this->selecionado);
        echo '</div>';
        echo '</div>';
        echo '<hr>';
        
        if(isset($_GET['escrever'])){
            if(count($this->selecionado->getObjetos()) == 0){
                echo "<p>Não existem Objetos Cadastrados, cadastre pelo menos um objeto.</p>";
                return;
            }
            EscritorDeSoftware::main($this->selecionado);
            $zipador = new Zipador();
         
            echo '<div class="row justify-content-center">';
            $zipador->zipaArquivo('sistemas/'.$this->selecionado->getNome(), 'sistemas/'.$this->selecionado->getNome().'.zip');
            echo ' - <a href="sistemas/'.$this->selecionado->getNome().'"> Acessar Software</a>';
            echo ' - <a href="sistemas/'.$this->selecionado->getNome().'.zip"> Baixar Software</a>';
            echo '</div>';
            echo '<br><hr>';
            
            echo '<div class="row">';
            echo '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">';
            echo '<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          SQL de Criação para PostgreSQL
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">';
            $sqlPG = file_get_contents('sistemas/'.$this->selecionado->getNome().'/'.strtolower($this->selecionado->getNome()).'_banco_pg.sql');
            $sqlPG = $this->formatarPG($sqlPG);
            echo $sqlPG;
            echo '
        
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          SQL de Criação para SQLITE
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">';
            $sql = file_get_contents('sistemas/'.$this->selecionado->getNome().'/'.strtolower($this->selecionado->getNome()).'_banco_sqlite.sql');
            $sql = $this->formatarSQLITE($sql);
            
            echo $sql;
            echo '
        
      </div>
    </div>
  </div>
  
</div>';
            
            echo '</div>';
            
            
            
            
            return;

        }
        
        
        $this->view->mostrarSelecionado($this->selecionado);
        

        
    }
    public function formatarPG($sql){
        
        
        $sql = str_replace(" ", "&nbsp;", $sql);
        $sql = nl2br($sql);
        $sql = str_replace("ON&nbsp;UPDATE&nbsp;NO&nbsp;ACTION", "<span class=\"text-secondary font-weight-bold\">ON UPDATE NO ACTION</span>", $sql);
        $sql = str_replace("ON&nbsp;DELETE&nbsp;NO&nbsp;ACTION", "<span class=\"text-secondary font-weight-bold\">ON DELETE NO ACTION</span>", $sql);
        $sql = str_replace("MATCH&nbsp;SIMPLE", "<span class=\"text-secondary font-weight-bold\">MATCH&nbsp;SIMPLE</span>", $sql);
        
        
        
        
        
        
        
        
        
        $keyWords = array(
            "CREATE",
            "TABLE",
            "CONSTRAINT",
            "ALTER",
            "PRIMARY",
            "KEY",
            "NOT",
            "NULL",
            "FOREIGN",
            "ADD",
            "REFERENCES",
            "CONSTRAINT",
            "COLUMN"
            
        );
        $keyWords2 = array(
            "<span class=\"text-danger font-weight-bold\">CREATE</span>",
            "<span class=\"text-danger font-weight-bold\">TABLE</span>",
            "<span class=\"text-danger font-weight-bold\">CONSTRAINT</span>",
            "<span class=\"text-danger font-weight-bold\">ALTER</span>",
            "<span class=\"text-danger font-weight-bold\">PRIMARY</span>",
            "<span class=\"text-danger font-weight-bold\">KEY</span>",
            "<span class=\"text-danger font-weight-bold\">NOT</span>",
            "<span class=\"text-danger font-weight-bold\">NULL</span>",
            "<span class=\"text-danger font-weight-bold\">FOREIGN</span>",
            "<span class=\"text-secondary font-weight-bold\">ADD</span>",
            "<span class=\"text-secondary font-weight-bold\">REFERENCES</span>",
            "<span class=\"text-secondary font-weight-bold\">CONSTRAINT</span>", 
            "<span class=\"text-secondary font-weight-bold\">COLUMN</span>"
        );
        
        
        $sql = str_replace($keyWords, $keyWords2, $sql);
        $keyWords = array();
        $keyWords2 = array();
        foreach($this->selecionado->getObjetos() as $objeto){
            $keyWords[] = '&nbsp;'.$objeto->getNomeSnakeCase().'&nbsp;';
            $keyWords2[] = "&nbsp;<span class=\"text-info\">".$objeto->getNomeSnakeCase()."</span>&nbsp;";
        }
        
        
        $sql = str_replace($keyWords, $keyWords2, $sql);
        
        
        $keyWords = array();
        $keyWords2 = array();
        $tipos = array("numeric", "&nbsp;integer", "&nbsp;serial&nbsp;", "character&nbsp;varying");
        foreach($tipos as $tipo){
            $keyWords[] = $tipo;
            $keyWords2[] = "<span class=\"text-success\">".$tipo."</span>&nbsp;";
        }
        $sql = str_replace($keyWords, $keyWords2, $sql);
        
        return $sql;
    }
    public function formatarSQLITE($sql){
        
        
        $sql = str_replace(" ", "&nbsp;", $sql);
        $sql = nl2br($sql);
        $sql = str_replace("ON&nbsp;UPDATE&nbsp;NO&nbsp;ACTION", "<span class=\"text-secondary font-weight-bold\">ON UPDATE NO ACTION</span>", $sql);
        $sql = str_replace("ON&nbsp;DELETE&nbsp;NO&nbsp;ACTION", "<span class=\"text-secondary font-weight-bold\">ON DELETE NO ACTION</span>", $sql);
        $sql = str_replace("MATCH&nbsp;SIMPLE", "<span class=\"text-secondary font-weight-bold\">MATCH&nbsp;SIMPLE</span>", $sql);
        
        
        
        
        
        
        
        
        
        $keyWords = array(
            "CREATE",
            "TABLE",
            "CONSTRAINT",
            "ALTER",
            "PRIMARY",
            "KEY",
            "NOT",
            "NULL",
            "FOREIGN",
            "ADD&nbsp;COLUMN",
            "REFERENCES",
            "CONSTRAINT"
        );
        $keyWords2 = array(
            "<span class=\"text-danger font-weight-bold\">CREATE</span>",
            "<span class=\"text-danger font-weight-bold\">TABLE</span>",
            "<span class=\"text-danger font-weight-bold\">CONSTRAINT</span>",
            "<span class=\"text-danger font-weight-bold\">ALTER</span>",
            "<span class=\"text-danger font-weight-bold\">PRIMARY</span>",
            "<span class=\"text-danger font-weight-bold\">KEY</span>",
            "<span class=\"text-danger font-weight-bold\">NOT</span>",
            "<span class=\"text-danger font-weight-bold\">NULL</span>",
            "<span class=\"text-danger font-weight-bold\">FOREIGN</span>",
            "<span class=\"text-secondary font-weight-bold\">ADD COLUMN</span>",
            "<span class=\"text-secondary font-weight-bold\">REFERENCES</span>",
            "<span class=\"text-secondary font-weight-bold\">CONSTRAINT</span>"
        );
        
        
        $sql = str_replace($keyWords, $keyWords2, $sql);
        $keyWords = array();
        $keyWords2 = array();
        foreach($this->selecionado->getObjetos() as $objeto){
            $keyWords[] = '&nbsp;'.$objeto->getNomeSnakeCase().'&nbsp;';
            $keyWords2[] = "&nbsp;<span class=\"text-info\">".$objeto->getNomeSnakeCase()."</span>&nbsp;";
        }
        
        
        $sql = str_replace($keyWords, $keyWords2, $sql);
        
        
        $keyWords = array();
        $keyWords2 = array();
        $tipos = array("TEXT","NUMERIC", "AUTOINCREMENT", "&nbsp;INTEGER&nbsp;", "&nbsp;serial&nbsp;", "character&nbsp;varying");
        foreach($tipos as $tipo){
            $keyWords[] = $tipo;
            $keyWords2[] = "<span class=\"text-success\">".$tipo."</span>&nbsp;";
        }
        $sql = str_replace($keyWords, $keyWords2, $sql);
        
        return $sql;
    }
	public function cadastrar() {
	    $this->view->mostraFormInserir();
	    
        if(!isset($this->post['enviar_software'])){   
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