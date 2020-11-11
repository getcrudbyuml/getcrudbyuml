<?php
            
/**
 * Classe feita para manipulação do objeto PostsController
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */

namespace blogGetCrudByUML\controller;

use blogGetCrudByUML\dao\PostsDAO;


use blogGetCrudByUML\model\Posts;
use blogGetCrudByUML\view\PostsView;


class PostsController {

	protected  $view;
    protected $dao;

	public function __construct(){
		$this->dao = new PostsDAO();
		$this->view = new PostsView();
	}


    public function delete(){
	    if(!isset($_GET['delete'])){
	        return;
	    }
        $selected = new Posts();
	    $selected->setId($_GET['delete']);
        if(!isset($_POST['delete_posts'])){
            $this->view->confirmDelete($selected);
            return;
        }
        if($this->dao->delete($selected))
        {
			echo '

<div class="alert alert-success" role="alert">
  Sucesso ao excluir Posts
</div>

';
		} else {
			echo '

<div class="alert alert-danger" role="alert">
  Falha ao tentar excluir Posts
</div>

';
		}
    	echo '<META HTTP-EQUIV="REFRESH" CONTENT="2; URL=index.php?page=posts">';
    }



	public function list() 
    {
		$list = $this->dao->fetch();
		$this->view->showList($list);
	}


	public function add() {
            
        if(!isset($_POST['enviar_posts'])){
            $this->view->showInsertForm();
		    return;
		}
		if (! ( isset ( $_POST ['content'] ) && isset ( $_POST ['title'] ) && isset ( $_POST ['date'] ) && isset ( $_POST ['img_path'] ))) {
			echo '
                <div class="alert alert-danger" role="alert">
                    Failed to register. Some field must be missing. 
                </div>

                ';
			return;
		}
		
        
		$posts = new Posts ();
		$posts->setContent ( $_POST ['content'] );
		$posts->setTitle ( $_POST ['title'] );
		$posts->setDate ( $_POST ['date'] );
		$posts->setImgPath ( $_POST ['img_path'] );
            
		if ($this->dao->insert ( $posts ))
        {
			echo '

<div class="alert alert-success" role="alert">
  Sucesso ao inserir Posts
</div>

';
		} else {
			echo '

<div class="alert alert-danger" role="alert">
  Falha ao tentar Inserir Posts
</div>

';
		}
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; URL=index.php?page=posts">';
	}



            
	public function addAjax() {
            
        if(!isset($_POST['enviar_posts'])){
            return;    
        }
        
		    
		
		if (! ( isset ( $_POST ['content'] ) && isset ( $_POST ['title'] ) && isset ( $_POST ['date'] ) && isset ( $_POST ['img_path'] ))) {
			echo ':incompleto';
			return;
		}
            
		$posts = new Posts ();
		$posts->setContent ( $_POST ['content'] );
		$posts->setTitle ( $_POST ['title'] );
		$posts->setDate ( $_POST ['date'] );
		$posts->setImgPath ( $_POST ['img_path'] );
            
		if ($this->dao->insert ( $posts ))
        {
			$id = $this->dao->getConnection()->lastInsertId();
            echo ':sucesso:'.$id;
            
		} else {
			 echo ':falha';
		}
	}
            
            

            
    public function edit(){
	    if(!isset($_GET['edit'])){
	        return;
	    }
        $selected = new Posts();
	    $selected->setId($_GET['edit']);
	    $this->dao->fillById($selected);
	        
        if(!isset($_POST['edit_posts'])){
            $this->view->showEditForm($selected);
            return;
        }
            
		if (! ( isset ( $_POST ['content'] ) && isset ( $_POST ['title'] ) && isset ( $_POST ['date'] ) && isset ( $_POST ['img_path'] ))) {
			echo "Incompleto";
			return;
		}

		$selected->setContent ( $_POST ['content'] );
		$selected->setTitle ( $_POST ['title'] );
		$selected->setDate ( $_POST ['date'] );
		$selected->setImgPath ( $_POST ['img_path'] );
            
		if ($this->dao->update ($selected ))
        {
			echo '

<div class="alert alert-success" role="alert">
  Sucesso 
</div>

';
		} else {
			echo '

<div class="alert alert-danger" role="alert">
  Falha 
</div>

';
		}
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; URL=index.php?page=posts">';
            
    }
        

    public function main(){
        
        if (isset($_GET['select'])){
            echo '<div class="row justify-content-center">';
                $this->select();
            echo '</div>';
            return;
        }
        echo '
		<div class="row justify-content-center">';
        echo '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">';
        
        if(isset($_GET['edit'])){
            $this->edit();
        }else if(isset($_GET['delete'])){
            $this->delete();
	    }else{
            $this->add();
        }
        $this->list();
        
        echo '</div>';
        echo '</div>';
            
    }
    public function mainAjax(){

        $this->addAjax();
        
            
    }


            
    public function select(){
	    if(!isset($_GET['select'])){
	        return;
	    }
        $selected = new Posts();
	    $selected->setId($_GET['select']);
	        
        $this->dao->fillById($selected);
            
        echo '<div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">';
	    $this->view->showSelected($selected);
        echo '</div>';
            

            
    }
}
?>