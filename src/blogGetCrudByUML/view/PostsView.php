<?php
            
/**
 * Classe de visao para Posts
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */

namespace blogGetCrudByUML\view;
use blogGetCrudByUML\model\Posts;



class PostsView {
    public function showInsertForm() {
		echo '
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary m-3" data-toggle="modal" data-target="#modalAddPosts">
  Adicionar
</button>

<!-- Modal -->
<div class="modal fade" id="modalAddPosts" tabindex="-1" role="dialog" aria-labelledby="labelAddPosts" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="labelAddPosts">Adicionar Posts</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        


          <form id="insert_form_posts" class="user" method="post">
            <input type="hidden" name="enviar_posts" value="1">                



                                        <div class="form-group">
                                            <label for="content">Content</label>
                                            <input type="text" class="form-control"  name="content" id="content" placeholder="Content">
                                        </div>

                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" class="form-control"  name="title" id="title" placeholder="Title">
                                        </div>

                                        <div class="form-group">
                                            <label for="date">Date</label>
                                            <input type="datetime-local" class="form-control"  name="date" id="date" placeholder="Date">
                                        </div>

                                        <div class="form-group">
                                            <label for="img_path">Img Path</label>
                                            <input type="text" class="form-control"  name="img_path" id="img_path" placeholder="Img Path">
                                        </div>

						              </form>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button form="insert_form_posts" type="submit" class="btn btn-primary">Cadastrar</button>
      </div>
    </div>
  </div>
</div>
            
            
			
';
	}



                                            
                                            
    public function showList($lista){
           echo '
                                            
                                            
                                            

          <div class="card mb-4">
                <div class="card-header">
                  Lista Posts
                </div>
                <div class="card-body">
                                            
                                            
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%"
				cellspacing="0">
				<thead>
					<tr>
						<th>Id</th>
						<th>Content</th>
						<th>Title</th>
						<th>Date</th>
                        <th>Actions</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
                        <th>Id</th>
                        <th>Content</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Actions</th>
					</tr>
				</tfoot>
				<tbody>';
            
            foreach($lista as $element){
                echo '<tr>';
                echo '<td>'.$element->getId().'</td>';
                echo '<td>'.$element->getContent().'</td>';
                echo '<td>'.$element->getTitle().'</td>';
                echo '<td>'.$element->getDate().'</td>';
                echo '<td>
                        <a href="?page=posts&select='.$element->getId().'" class="btn btn-info text-white">Select</a>
                        <a href="?page=posts&edit='.$element->getId().'" class="btn btn-success text-white">Edit</a>
                        <a href="?page=posts&delete='.$element->getId().'" class="btn btn-danger text-white">Delete</a>
                      </td>';
                echo '</tr>';
            }
            
        echo '
				</tbody>
			</table>
		</div>
            
            
            
            
  </div>
</div>
            
';
    }
            

            
	public function showEditForm(Posts $selecionado) {
		echo '
	    
	    
	    
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<div class="row">
	    
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Edit Posts</h1>
									</div>
						              <form class="user" method="post">
                                        <div class="form-group">
                                            <label for="content">Content</label>
                                            <input type="text" class="form-control" value="'.$selecionado->getContent().'"  name="content" id="content" placeholder="Content">
                						</div>
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" class="form-control" value="'.$selecionado->getTitle().'"  name="title" id="title" placeholder="Title">
                						</div>
                                        <div class="form-group">
                                            <label for="date">Date</label>
                                            <input type="datetime-local" class="form-control" value="'.$selecionado->getDate().'"  name="date" id="date" placeholder="Date">
                						</div>
                                        <div class="form-group">
                                            <label for="img_path">Img Path</label>
                                            <input type="text" class="form-control" value="'.$selecionado->getImgPath().'"  name="img_path" id="img_path" placeholder="Img Path">
                						</div>
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Alterar" name="edit_posts">
                                        <hr>
                                            
						              </form>
                                            
								</div>
							</div>
						</div>
					</div>
                                            
                                            
                                            
	</div>';
	}




            
        public function showSelected(Posts $posts){
            echo '
            
	<div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card mb-4">
            <div class="card-header">
                  Posts selecionado
            </div>
            <div class="card-body">
                Id: '.$posts->getId().'<br>
                Content: '.$posts->getContent().'<br>
                Title: '.$posts->getTitle().'<br>
                Date: '.$posts->getDate().'<br>
                Img Path: '.$posts->getImgPath().'<br>
            
            </div>
        </div>
    </div>
            
            
';
    }


                                            
    public function confirmDelete(Posts $posts) {
		echo '
        
        
        
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
        
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Delete Posts</h1>
									</div>
						              <form class="user" method="post">                    Are you sure you want to delete this object?

                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Delete" name="delete_posts">
                                        <hr>
                                            
						              </form>
                                            
								</div>
							</div>
						</div>
					</div>
                                            
                                            
                                            
                                            
	</div>';
	}
                      


}