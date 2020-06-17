<?php


class ControllerGerador{
    private $software;
    private $listaDeArquivos;
    private $diretorio;
    
    public static function main(Software $software, $diretorio){
        $gerador = new ControllerGerador($software, $diretorio);
        $gerador->gerarCodigo();
    }
    public function __construct(Software $software, $diretorio){
        $this->software = $software;
        $this->diretorio = $diretorio;
    }

    public function gerarCodigo(){
        foreach ($this->software->getObjetos() as $objeto){
            $this->geraControllers($objeto);
        }
        $this->criarArquivos();
        
    }
    private function criarArquivos(){
        
        $caminho = $this->diretorio.'/AppWebPHP/'.$this->software->getNomeSimples().'/src/classes/controller';
        if(!file_exists($caminho)) {
            mkdir($caminho, 0777, true);
        }
        
        foreach ($this->listaDeArquivos as $path => $codigo) {
            if (file_exists($path)) {
                unlink($path);
            }
            $file = fopen($path, "w+");
            fwrite($file, stripslashes($codigo));
            fclose($file);
        }
    }
   
    private function geraControllers(Objeto $objeto)
    {
        $codigo = '';
        $nomeDoObjeto = lcfirst($objeto->getNome());
        $nomeDoObjetoMa = ucfirst($objeto->getNome());
        
        $atributosComuns = array();
        $atributosNN = array();
        $atributosObjetos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->tipoListado()){
                $atributosComuns[] = $atributo;
            }else if($atributo->isArrayNN()){
                $atributosNN[] = $atributo;
            }
            else if($atributo->isObjeto()){
                $atributosObjetos[] = $atributo;
                
            }
        }
        
        
        $codigo = '<?php
            
/**
 * Classe feita para manipulação do objeto ' . $nomeDoObjetoMa . '
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class ' . $nomeDoObjetoMa . 'Controller {
	private $post;
	private $view;
    private $dao;
    
    public static function main(){
        $controller = new '.$nomeDoObjetoMa.'Controller();
        if (isset($_GET[\'selecionar\'])){
            echo \'<div class="row justify-content-center">\';
                $controller->selecionar();
            echo \'</div>\';
            return;
        }
        echo \'
		<div class="row justify-content-center">\';
        echo \'<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">\';
        
        if(isset($_GET[\'editar\'])){
            $controller->editar();
        }else if(isset($_GET[\'deletar\'])){
            $controller->deletar();
	    }else{
            $controller->cadastrar();
        }
        $controller->listar();
        
        echo \'</div>\';
        echo \'</div>\';
            
    }
    public static function mainREST()
    {
        if(!isset($_SERVER[\'PHP_AUTH_USER\'])){
            header("WWW-Authenticate: Basic realm=\\\\"Private Area\\\\" ");
            header("HTTP/1.0 401 Unauthorized");
            echo \'{"erro":[{"status":"error","message":"Authentication failed"}]}\';
            return;
        }
        if($_SERVER[\'PHP_AUTH_USER\'] == \'usuario\' && ($_SERVER[\'PHP_AUTH_PW\'] == \'senha@12\')){
            header(\'Content-type: application/json\');
            $controller = new '.$nomeDoObjetoMa.'Controller();
            $controller->restGET();
            //$controller->restPOST();
            //$controller->restPUT();
            $controller->resDELETE();
        }else{
            header("WWW-Authenticate: Basic realm=\\\\"Private Area\\\\" ");
            header("HTTP/1.0 401 Unauthorized");
            echo \'{"erro":[{"status":"error","message":"Authentication failed"}]}\';
        }

    }

	public function __construct(){
		$this->dao = new ' . $nomeDoObjetoMa . 'DAO();
		$this->view = new ' . $nomeDoObjetoMa . 'View();
		foreach($_POST as $chave => $valor){
			$this->post[$chave] = $valor;
		}
	}
	public function listar() {
		$' . $nomeDoObjeto . 'Dao = new ' . $nomeDoObjetoMa . 'DAO ();
		$lista = $' . $nomeDoObjeto . 'Dao->retornaLista ();
		$this->view->exibirLista($lista);
	}
    public function selecionar(){
	    if(!isset($_GET[\'selecionar\'])){
	        return;
	    }
        $selecionado = new '.$nomeDoObjetoMa.'();
	    $selecionado->set'.ucfirst ($objeto->getAtributos()[0]->getNome()).'($_GET[\'selecionar\']);
	        
        $this->dao->preenchePor'.ucfirst ($objeto->getAtributos()[0]->getNome()).'($selecionado);

        echo \'<div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">\';
	    $this->view->mostrarSelecionado($selecionado);
        echo \'</div>\';
            
';
        
        foreach($atributosNN as $atributoNN){
            $codigo .= '
        $this->dao->buscar'.ucfirst($atributoNN->getNome()).'($selecionado);
        $'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'Dao = new '.ucfirst(explode(" ", $atributoNN->getTipo())[2]).'DAO($this->dao->getConexao());
        $lista = $'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'Dao->retornaLista();
            
        echo \'<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">\';
        $this->view->exibir'.ucfirst($atributoNN->getNome()).'($selecionado);
        echo \'</div>\';
            
            
        if(!isset($_POST[\'add'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'\']) && !isset($_GET[\'remover'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'\'])){
            echo \'<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">\';
            $this->view->adicionar'.ucfirst(explode(" ", $atributoNN->getTipo())[2]).'($lista);
            echo \'</div>\';
        }else if(isset($_POST[\'add'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'\'])){
            $'.strtolower(explode(" ", $atributoNN->getTipo())[2]).' = new '.ucfirst(explode(" ", $atributoNN->getTipo())[2]).'();
            $'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'->setId($_POST[\'add'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'\']);
            if($this->dao->inserir'.ucfirst(explode(" ", $atributoNN->getTipo())[2]).'($selecionado, $'.strtolower(explode(" ", $atributoNN->getTipo())[2]).')){
                echo \'<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">\';
    			$this->view->mensagem("Sucesso ao Inserir!");
                echo \'</div>\';
    		} else {
                echo \'<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">\';
    			$this->view->mensagem("Erro ao Inserir!");
                echo \'</div>\';
    		}
            echo \'<META HTTP-EQUIV="REFRESH" CONTENT="2; URL=index.php?pagina='.$nomeDoObjeto.'&selecionar=\'.$selecionado->get'.ucfirst ($objeto->getAtributos()[0]->getNome()).'().\'">\';
            return;
        }else  if(isset($_GET[\'remover'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'\'])){
            
            $'.strtolower(explode(" ", $atributoNN->getTipo())[2]).' = new '.ucfirst(explode(" ", $atributoNN->getTipo())[2]).'();
            $'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'->setId($_GET[\'remover'.strtolower(explode(" ", $atributoNN->getTipo())[2]).'\']);
            if($this->dao->remover'.ucfirst(explode(" ", $atributoNN->getTipo())[2]).'($selecionado, $'.strtolower(explode(" ", $atributoNN->getTipo())[2]).')){
                echo \'<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">\';
    			$this->view->mensagem("Sucesso ao Remover!");
                echo \'</div>\';
    		} else {
                echo \'<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">\';
    			$this->view->mensagem("Erro ao tentar Remover!");
                echo \'</div>\';
    		}
            echo \'<META HTTP-EQUIV="REFRESH" CONTENT="2; URL=index.php?pagina='.$nomeDoObjeto.'&selecionar=\'.$selecionado->get'.ucfirst ($objeto->getAtributos()[0]->getNome()).'().\'">\';
            return;
        }
                
                
        ';
            
            
        }
        $codigo .= '
            
    }';
        
        $codigo .= '
            
	public function cadastrar() {
            
        if(!isset($this->post[\'enviar_' . $objeto->getNomeSnakeCase() . '\'])){';
        foreach($atributosObjetos as $atributoObjeto){
            $codigo .= '
            $'.strtolower($atributoObjeto->getTipo()).'Dao = new '.ucfirst ($atributoObjeto->getTipo()).'DAO($this->dao->getConexao());
            $lista'.ucfirst ($atributoObjeto->getTipo()).' = $'.strtolower($atributoObjeto->getTipo()).'Dao->retornaLista();
';
            
            
        }
        $codigo .= '
            $this->view->mostraFormInserir(';
        $i = count($atributosObjetos);
        foreach($atributosObjetos as $atributoObjeto){
            $i--;
            $codigo .= '$lista'.ucfirst ($atributoObjeto->getTipo());
            if($i != 0){
                $codigo .= ', ';
            }
        }
        $codigo .= ');';
        $codigo .= '
		    return;
		}
		if (! ( ';
        $i = 0;
        $numDeComunsSemPK = 0;
        foreach ($atributosComuns as $atributo) {
            $i ++;
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            $numDeComunsSemPK++;
            $codigo .= 'isset ( $this->post [\'' . $atributo->getNome() . '\'] )';
            if ($i != count($atributosComuns)) {
                $codigo .= ' && ';
            }
        }
        $i = 0;
        foreach($atributosObjetos as $atributoObjeto){
            foreach($this->software->getObjetos() as $objeto3){
                if($atributoObjeto->getTipo() == $objeto3->getNome())
                {
                    foreach($objeto3->getAtributos() as $atributo2){
                        if($atributo2->getIndice() == Atributo::INDICE_PRIMARY){
                            
                            if($numDeComunsSemPK > 0 && $i == 0){
                                $codigo .= ' && ';
                            }else if($i > 0){
                                $codigo .= ' && ';
                            }
                            $i++;
                            $codigo .= ' isset($this->post [\'' . $atributoObjeto->getNomeSnakeCase() . '\'])';
                            break;
                        }
                    }
                    break;
                }
            }
        }
        
        $codigo .= ')) {
			echo "Incompleto";
			return;
		}';
        
        $codigo .= '
            
		$' . $nomeDoObjeto . ' = new ' . $nomeDoObjetoMa . ' ();';
        foreach ($atributosComuns as $atributo) {
            $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            $codigo .= '
		$' . $nomeDoObjeto . '->set' . $nomeDoAtributoMA . ' ( $this->post [\'' . $atributo->getNome() . '\'] );';
        }
        foreach($atributosObjetos as $atributoObjeto){
            foreach($this->software->getObjetos() as $objeto3){
                if($atributoObjeto->getTipo() == $objeto3->getNome())
                {
                    foreach($objeto3->getAtributos() as $atributo2){
                        if($atributo2->getIndice() == Atributo::INDICE_PRIMARY){
                            $codigo .= '
		$' . $nomeDoObjeto . '->get' .ucfirst($atributoObjeto->getNome()) . '()->set'.ucfirst ($atributo2->getNome()).' ( $this->post [\'' . $atributoObjeto->getNomeSnakeCase() . '\'] );';
                            break;
                        }
                    }
                    break;
                }
            }
        }
        
        $codigo .= '
            
		if ($this->dao->inserir ( $' . $nomeDoObjeto . ' ))
        {
			echo \'

<div class="alert alert-success" role="alert">
  Sucesso ao inserir '.$objeto->getNomeTextual().'
</div>

\';
		} else {
			echo \'

<div class="alert alert-danger" role="alert">
  Falha ao tentar Inserir '.$objeto->getNomeTextual().'
</div>

\';
		}
        echo \'<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=' . $objeto->getNomeSnakeCase() . '">\';
	}
    public function editar(){
	    if(!isset($_GET[\'editar\'])){
	        return;
	    }
        $selecionado = new '.$nomeDoObjetoMa.'();
	    $selecionado->set'.ucfirst ($objeto->getAtributos()[0]->getNome()).'($_GET[\'editar\']);
	    $this->dao->pesquisaPor'.ucfirst ($objeto->getAtributos()[0]->getNome()).'($selecionado);
	        
        if(!isset($_POST[\'editar_' . $nomeDoObjeto . '\'])){
            $this->view->mostraFormEditar($selecionado);
            return;
        }
            
		if (! ( ';
        $i = 0;
        $numDeComunsSemPK = 0;
        foreach ($atributosComuns as $atributo) {
            $i ++;
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            $numDeComunsSemPK++;
            $codigo .= 'isset ( $this->post [\'' . $atributo->getNome() . '\'] )';
            if ($i != count($atributosComuns)) {
                $codigo .= ' && ';
            }
        }
        $i = 0;
        foreach($atributosObjetos as $atributoObjeto){
            foreach($this->software->getObjetos() as $objeto3){
                if($atributoObjeto->getTipo() == $objeto3->getNome())
                {
                    foreach($objeto3->getAtributos() as $atributo2){
                        if($atributo2->getIndice() == Atributo::INDICE_PRIMARY){
                            
                            if($numDeComunsSemPK > 0 && $i == 0){
                                $codigo .= ' && ';
                            }else if($i > 0){
                                $codigo .= ' && ';
                            }
                            $i++;
                            $codigo .= ' isset($this->post [\'' . $atributoObjeto->getNomeSnakeCase() . '\'])';
                            break;
                        }
                    }
                    break;
                }
            }
        }
        
        $codigo .= ')) {
			echo "Incompleto";
			return;
		}
';
        foreach ($atributosComuns as $atributo) {
            $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            $codigo .= '
		$selecionado->set' . $nomeDoAtributoMA . ' ( $this->post [\'' . $atributo->getNome() . '\'] );';
        }
        
        $codigo .= '
            
		if ($this->dao->atualizar ($selecionado ))
        {
            
			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
        echo \'<META HTTP-EQUIV="REFRESH" CONTENT="3; URL=index.php?pagina=' . $nomeDoObjeto . '">\';
            
    }
    public function deletar(){
	    if(!isset($_GET[\'deletar\'])){
	        return;
	    }
        $selecionado = new '.$nomeDoObjetoMa.'();
	    $selecionado->set'.ucfirst ($objeto->getAtributos()[0]->getNome()).'($_GET[\'deletar\']);
	    $this->dao->pesquisaPor'.ucfirst ($objeto->getAtributos()[0]->getNome()).'($selecionado);
        if(!isset($_POST[\'deletar_' . $nomeDoObjeto . '\'])){
            $this->view->confirmarDeletar($selecionado);
            return;
        }
        if($this->dao->excluir($selecionado)){
            echo "excluido com sucesso";
        }else{
            echo "Errou";
        }
    	echo \'<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=' . $nomeDoObjeto . '">\';
    }
	public function restGET()
    {

        if ($_SERVER[\'REQUEST_METHOD\'] != \'GET\') {
            return;
        }

        if(!isset($_REQUEST[\'api\'])){
            return;
        }
        $url = explode("/", $_REQUEST[\'api\']);
        if (count($url) == 0 || $url[0] == "") {
            return;
        }
        if ($url[1] != \''.$nomeDoObjeto.'\') {
            return;
        }

        if(isset($url[1])){
            $parametro = $url[1];
            $id = intval($parametro);
            $pesquisado = new '.ucfirst($objeto->getNome()).'();
            $pesquisado->setId($id);
            $pesquisado = $this->dao->pesquisaPorId($pesquisado);
            if ($pesquisado == null) {
                echo "{}";
                return;
            }

            $pesquisado = array(';
        $i = 0;
        foreach ($atributosComuns as $atributo) {
            $i ++;
            $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
            $codigo .= '
					\'' . $atributo->getNome() . '\' => $pesquisado->get' . $nomeDoAtributoMA . ' ()';
            if ($i != count($objeto->getAtributos())) {
                $codigo .= ', ';
            }
        }
        
        $codigo .= '
            
            
			);
            echo json_encode($pesquisado);
            return;
        }        
        $lista = $this->dao->retornaLista();
        $listagem = array();
        foreach ( $lista as $linha ) {
			$listagem [\'lista\'] [] = array (';
        $i = 0;
        foreach ($atributosComuns as $atributo) {
            $i ++;
            $nomeDoAtributoMA = ucfirst($atributo->getNome());
            $codigo .= '
					\'' . $atributo->getNome() . '\' => $linha->get' . $nomeDoAtributoMA . ' ()';
            if ($i != count($objeto->getAtributos())) {
                $codigo .= ', ';
            }
        }
        
        $codigo .= '
            
            
			);
		}
		echo json_encode ( $listagem );
    
		
		
		
		
	}

    public function resDELETE()
    {
        if ($_SERVER[\'REQUEST_METHOD\'] != \'DELETE\') {
            return;
        }
        $path = explode(\'/\', $_GET[\'api\']);
        $parametro = "";
        if (count($path) < 2) {
            return;
        }
        $parametro = $path[1];
        if ($parametro == "") {
            return;
        }
    
        $id = intval($parametro);
        $pesquisado = new '.ucfirst($objeto->getNome()).'();
        $pesquisado->setId($id);
        $pesquisado = $this->dao->pesquisaPorId($pesquisado);
        if ($pesquisado == null) {
            echo "{}";
            return;
        }

        if($this->dao->excluir($pesquisado))
        {
            echo "{}";
            return;
        }
        
        echo "Erro.";
        
    }
    public function restPUT()
    {
        if ($_SERVER[\'REQUEST_METHOD\'] != \'PUT\') {
            return;
        }

        if (! array_key_exists(\'api\', $_GET)) {
            return;
        }
        $path = explode(\'/\', $_GET[\'api\']);
        if (count($path) == 0 || $path[0] == "") {
            echo \'Error. Path missing.\';
            return;
        }
        
        $param1 = "";
        if (count($path) > 1) {
            $parametro = $path[1];
        }

        if ($path[0] != \'info\') {
            return;
        }

        if ($param1 == "") {
            echo \'error\';
            return;
        }

        $id = intval($parametro);
        $pesquisado = new '.ucfirst($objeto->getNome()).'();
        $pesquisado->setId($id);
        $pesquisado = $this->dao->pesquisaPorId($pesquisado);

        if ($pesquisado == null) {
            return;
        }

        $body = file_get_contents(\'php://input\');
        $jsonBody = json_decode($body, true);
        
        ';
        foreach($objeto->getAtributos() as $atributo){
            if($atributo->tipoListado()){
                $codigo .= '
        if (isset($jsonBody[\''.$atributo->getNomeSnakeCase().'\'])) {
            $pesquisado->set'.ucfirst($atributo->getNome()).'($jsonBody[\''.$atributo->getNomeSnakeCase().'\']);
        }
                    
';
            }
        }
  
        $codigo .= '
        if ($this->dao->atualizar($pesquisado)) {
            echo "Sucesso";
        } else {
            echo "Erro";
        }
    }

    public function restPOST()
    {
        if ($_SERVER[\'REQUEST_METHOD\'] != \'POST\') {
            return;
        }
        if (! array_key_exists(\'path\', $_GET)) {
            echo \'Error. Path missing.\';
            return;
        }

        $path = explode(\'/\', $_GET[\'path\']);

        if (count($path) == 0 || $path[0] == "") {
            echo \'Error. Path missing.\';
            return;
        }

        $body = file_get_contents(\'php://input\');
        $jsonBody = json_decode($body, true);

        if (! ( ';
        $i = 0;
        $numDeComunsSemPK = 0;
        foreach ($atributosComuns as $atributo) {
            $i ++;
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            $numDeComunsSemPK++;
            $codigo .= 'isset ( $jsonBody [\'' . $atributo->getNome() . '\'] )';
            if ($i != count($atributosComuns)) {
                $codigo .= ' && ';
            }
        }
        $i = 0;
        foreach($atributosObjetos as $atributoObjeto){
            foreach($this->software->getObjetos() as $objeto3){
                if($atributoObjeto->getTipo() == $objeto3->getNome())
                {
                    foreach($objeto3->getAtributos() as $atributo2){
                        if($atributo2->getIndice() == Atributo::INDICE_PRIMARY){
                            
                            if($numDeComunsSemPK > 0 && $i == 0){
                                $codigo .= ' && ';
                            }else if($i > 0){
                                $codigo .= ' && ';
                            }
                            $i++;
                            $codigo .= ' isset($this->post [\'' . $atributoObjeto->getNomeSnakeCase() . '\'])';
                            break;
                        }
                    }
                    break;
                }
            }
        }
        
        $codigo .= ')) {
			echo "Incompleto";
			return;
		}

        $adicionado = new '.ucfirst($objeto->getNome()).'();';
        foreach($objeto->getAtributos() as $atributo){
            if($atributo->tipoListado()){
                
                $codigo .= '
        $adicionado->set'.ucfirst($atributo->getNome()).'($jsonBody[\''.$atributo->getNomeSnakeCase().'\']);
';
            }
        }
  
        $codigo .= '
        if ($this->dao->inserir($adicionado)) {
            echo "Sucesso";
        } else {
            echo "Fracasso";
        }
    }            
            
		';
        
        $codigo .= '
}
?>';
        $caminho = $this->diretorio.'/AppWebPHP/'.$this->software->getNomeSimples().'/src/classes/controller/'.ucfirst($objeto->getNome()).'Controller.php';
        $this->listaDeArquivos[$caminho] = $codigo;
    }
    
}


?>