<?php
            
/**
 * Classe de visao para Posts
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */

namespace blogGetCrudByUML\custom\view;
use blogGetCrudByUML\view\PostsView;
use blogGetCrudByUML\model\Posts;


class PostsCustomView extends PostsView {

    
    public function mostrarSelecionado(Posts $post){
        
        
        
        
        
        echo '	<section id="institucional" class="container">
		<div class="card">
            
			<div class="card-body">
				<blockquote class="blockquote mb-0">';
        
        
        echo '	  <!-- Page Content -->
  <div class="container">
            
    <div class="row">
            
      <!-- Post Content Column -->
      <div class="col-lg-8">
            
        <!-- Title -->
        <h1 class="mt-4">'.$post->getTitle().'</h1>
        <!-- Date/Time -->
        <p class="text-right">Postado em '.date("d/m/Y", strtotime($post->getDate())).'</p>
        <hr>
            
            
            
            
            
        <!-- Post Content -->
         '.$post->getContent().'
             
             
             
             
      </div>
             
      <!-- Sidebar Widgets Column -->
      <div class="col-md-4">
             
        <!-- Search Widget -->
        <div class="card my-4">
          <h5 class="card-header">Pesquisar</h5>
          <div class="card-body">
            <div class="input-group">
              <form method="get" id="formpesquisa" >
                <input type="hidden" name="pagina" value="post">
                <input type="text" class="form-control" name="pesquisar" placeholder="Pesquisar...">
              </form>
              <span class="input-group-btn">
                <button class="btn btn-secondary" type="submit" form="formpesquisa">Ir!</button>
              </span>
            </div>
          </div>
        </div>
             
             
             
             
             
        <!-- Side Widget
        <div class="card my-4">
          <h5 class="card-header">Side Widget</h5>
          <div class="card-body">
            You can put anything you want inside of these side widgets. They are easy to use, and feature the new Bootstrap 4 card containers!
          </div>
        </div>
                -->
      </div>
             
    </div>
    <!-- /.row -->
             
  </div>
  <!-- /.container -->
            ';
        
        echo '        </blockquote>
        </div>
        </div>
            
        </section>
        ';
        
    }


}