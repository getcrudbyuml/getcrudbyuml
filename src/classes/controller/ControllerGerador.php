<?php


class ControllerGerador{
    private $software;
    private $listaDeArquivos;
    
    
    public function getListaDeArquivos(){
        return $this->listaDeArquivos;
    }
    public function __construct(Software $software){
        $this->software = $software;
    }

    public function gerarCodigo(){
        $this->geraCodigoPHP();
        $this->geraCodigoJava();
        
    }
    private function geraCodigoJava(){
        
        $path = 'sistemas/'.$this->software->getNome().'/java/'.$this->software->getNome().'/src/main/java';
        foreach($this->software->getObjetos() as $objeto){
            $codigo = $this->geraViewsJava($objeto, $this->software);
            $caminho = $path.'/br/com/escritordesoftware/'.strtolower($this->software->getNome()).'/controller/' . ucfirst($objeto->getNome()) . 'Controller.java';
            $this->listaDeArquivos[$caminho] = $codigo;
        }}
    private function geraCodigoPHP(){
        $path = 'sistemas/'.$this->software->getNome().'/php/src';
        foreach($this->software->getObjetos() as $objeto){
            $codigo = $this->geraControllersPHP($objeto, $this->software);
            $caminho = $path.'/classes/controller/' . strtoupper(substr($objeto->getNome(), 0, 1)) . substr($objeto->getNome(), 1, 100) . 'Controller.php';
            $this->listaDeArquivos[$caminho] = $codigo;
        }
    }
    private function geraControllersPHP(Objeto $objeto, Software $software)
    {
        $codigo = '';
        $geradorDeCodigo = new GeradorDeCodigoPHP();
        $nomeDoObjeto = strtolower($objeto->getNome());
        $nomeDoObjetoMa = strtoupper(substr($objeto->getNome(), 0, 1)) . substr($objeto->getNome(), 1, 100);
        
        $atributosComuns = array();
        $atributosNN = array();
        $atributosObjetos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if(substr($atributo->getTipo(),0,6) == 'Array '){
                $atributosNN[] = $atributo;
            }else if($atributo->getTipo() == Atributo::TIPO_INT || $atributo->getTipo() == Atributo::TIPO_STRING || $atributo->getTipo() == Atributo::TIPO_FLOAT)
            {
                $atributosComuns[] = $atributo;
            }///Depois faremos um else if pra objeto.
            else {
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
        echo \'<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">\';
        $controller->listar();
        echo \'</div>\';
        echo \'<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">\';
        if(isset($_GET[\'editar\'])){
            $controller->editar();
        }else if(isset($_GET[\'deletar\'])){
            $controller->deletar();
	    }else{
            $controller->cadastrar();
        }
        echo \'</div>\';
        echo \'</div>\';
            
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
	        
        if(count($this->dao->pesquisaPor'.ucfirst ($objeto->getAtributos()[0]->getNome()).'($selecionado)) == 0){
	        $this->view->mensagem("Página Inexistente");
	        return;
	    }
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
            
        if(!isset($this->post[\'enviar_' . $nomeDoObjeto . '\'])){';
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
        foreach ($atributosComuns as $atributo) {
            $i ++;
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            $codigo .= 'isset ( $this->post [\'' . $atributo->getNome() . '\'] )';
            if ($i != count($atributosComuns)) {
                $codigo .= ' && ';
            }
        }
        
        $codigo .= ')) {
			echo "Incompleto";
			return;
		}
            
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
            foreach($software->getObjetos() as $objeto3){
                if($atributoObjeto->getTipo() == $objeto3->getNome())
                {
                    foreach($objeto3->getAtributos() as $atributo2){
                        if($atributo2->getIndice() == Atributo::INDICE_PRIMARY){
                            $codigo .= '
		$' . $nomeDoObjeto . '->get' .ucfirst($atributoObjeto->getNome()) . '()->set'.ucfirst ($atributo2->getNome()).' ( $this->post [\'' . $atributoObjeto->getNome() . '\'] );';
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
			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
        echo \'<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=' . $nomeDoObjeto . '">\';
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
        foreach ($atributosComuns as $atributo) {
            $i ++;
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            $codigo .= 'isset ( $this->post [\'' . $atributo->getNome() . '\'] )';
            if ($i != count($atributosComuns)) {
                $codigo .= ' && ';
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
	public function listarJSON()
    {
		$' . $nomeDoObjeto . 'Dao = new ' . $nomeDoObjetoMa . 'DAO ();
		$lista = $' . $nomeDoObjeto . 'Dao->retornaLista ();
		$listagem = array ();
		foreach ( $lista as $linha ) {
			$listagem [\'lista\'] [] = array (';
        $i = 0;
        foreach ($atributosComuns as $atributo) {
            $i ++;
            $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
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
            
            
		';
        
        $codigo .= '
}
?>';
        return $codigo;
    }
    private function geraViewsJava(Objeto $objeto, Software $software)
    {
        $codigo = '';
        $codigo = '
package br.com.escritordesoftware.escola.controller;
            
/**
 * Classe de visao para ' . ucfirst($objeto->getNome()) . '
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */
class ' . ucfirst($objeto->getNome()) . 'Controller {

}';
        
        return $codigo;
    }
    
}


?>