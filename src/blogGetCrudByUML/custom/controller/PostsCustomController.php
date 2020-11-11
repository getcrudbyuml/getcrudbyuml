<?php
            
/**
 * Customize o controller do objeto Posts aqui 
 * @author Jefferson Uchôa Ponte <jefponte@gmail.com>
 */

namespace blogGetCrudByUML\custom\controller;
use blogGetCrudByUML\controller\PostsController;
use blogGetCrudByUML\custom\dao\PostsCustomDAO;
use blogGetCrudByUML\custom\view\PostsCustomView;
use blogGetCrudByUML\model\Posts;


class PostsCustomController  extends PostsController {
    

    public function main(){
        
        if(isset($_GET['pesquisar'])){
            $this->pesquisar();
            return;
        }
        if(isset($_GET['selecionar'])){
            $this->selecionar();
            return;
        }
        $this->listar();
        
        
    }
    public function __construct(){
        $this->dao = new PostsCustomDAO();
        $this->view = new PostsCustomView();
        foreach($_POST as $chave => $valor){
            $this->post[$chave] = $valor;
        }
    }
    public function listar() {
        $lista = $this->dao->retornaLista ();
        $this->view->exibirLista($lista);
    }
    public function pesquisar(){
        if(!isset($_GET['pesquisar'])){
            return;
        }
        $pesquisa = preg_replace('/[^a-z0-9\s]/i', null, $_GET['pesquisar']);
        $post = new Posts();
        $post->setTitle($pesquisa);
        $post->setContent($pesquisa);
        $lista = $this->dao->pesquisaPorTitle($post);
        $lista2 = $this->dao->pesquisaPorContent($post);
        $resultado = array_merge($lista, $lista2);
        $this->view->exibirLista($resultado);
        
    }
    public function selecionar(){
        if(!isset($_GET['selecionar'])){
            return;
        }
        $selecionado = new Posts();
        
        $selecionado->setID($_GET['selecionar']);
        $this->dao->pesquisaPorID($selecionado);
        $this->view->mostrarSelecionado($selecionado);
        
    }
    
	        
}
?>