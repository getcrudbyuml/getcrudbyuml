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

    
    public function mostraFormInserir() {
        echo '<div class="container">
            
		<!-- Outer Row -->
		<div class="row justify-content-center">
            
			<div class="col-xl-6 col-lg-12 col-md-9">
            
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
            
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Adicionar Post</h1>
									</div>
						              <form class="user" method="post">
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" id="content" name="content" placeholder="content">
                						</div>
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" id="title" name="title" placeholder="title">
                						</div>
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" id="date" name="date" placeholder="date">
                						</div>
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Cadastre-se" name="enviar_post">
                                        <hr>
            
						              </form>
            
								</div>
							</div>
						</div>
					</div>
				</div>
            
			</div>
            
		</div>
            
	</div>';
    }
    
    public function exibirLista($lista){
        
        
        echo '	<section id="institucional" class="container">
		<div class="card">
            
			<div class="card-body">
				<blockquote class="blockquote mb-0">
            
            
            
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%"
				cellspacing="0">
				<thead>
					<tr>
						<th>Título</th>
						<th>Data</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
                        <th>Título</th>
                        <th>Data</th>
					</tr>
				</tfoot>
				<tbody>';
        
        foreach($lista as $elemento){
            echo '<tr>';
            
            echo '<td> <a href="?pagina=post&selecionar='.$elemento->getID().'">'.$elemento->getTitle().'</a> </td>';
            echo '<td>'.date('d/m/Y',strtotime($elemento->getDate())).'</td>';
            echo '<tr>';
        }
        
        echo '
				</tbody>
			</table>
		</div>
            
          		</blockquote>
			</div>
		</div>
            
	</section>
	';
    }
    
    
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