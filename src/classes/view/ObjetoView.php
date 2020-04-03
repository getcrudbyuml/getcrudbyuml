<?php
            
/**
 * Classe de visao para Objeto
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */
class ObjetoView {
	public function mostraFormInserir($listaSoftware) {
	    
	    
		echo '

    

<div class="card">
  <div class="card-body">';
		
		echo '
		    
		    
						              <form class="user" method="post">
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user campmo-selecionado" id="nome" name="nome" placeholder="Adicionar Classe">
                						</div>';
		
		if(count($listaSoftware)){
		    echo '
                                        <div class="form-group">
                						  <select class="form-control form-control-user" id="idsoftware" name="idsoftware">
                                            <option>Selecione Um Software</option>';
		    foreach($listaSoftware as $software){
		        echo '<option value="'.$software->getId().'">'.$software->getNome().'</option>';
		    }
		    echo '
                                          </select>
                						</div>';
		}
		echo '
		    
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Cadastrar" name="enviar_objeto">
						              </form>
		    
		    
	';
		
		echo '
    
  </div>
</div>';

	}
                                            
    public function exibirLista($lista){
           echo '
                                            
<div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">objeto</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Menu:</div>
                      <a class="dropdown-item" href="?pagina=objeto&cadastrar=1">Adicionar objeto</a>
                    </div>
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                          
                          
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%"
				cellspacing="0">
				<thead>
					<tr>
						<th>id</th>
						<th>nome</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
                        <th>id</th>
                        <th>nome</th>
                        <th>Ações</th>
					</tr>
				</tfoot>
				<tbody>';
            
            foreach($lista as $elemento){
                echo '<tr>';
                echo '<td>'.$elemento->getId().'</td>';
                echo '<td>'.$elemento->getNome().'</td>';
                echo '<td>
                        <a href="?pagina=objeto&selecionar='.$elemento->getId().'" class="btn btn-info">Selecionar</a> 
                        <a href="?pagina=objeto&editar='.$elemento->getId().'" class="btn btn-success">Editar</a>
                        <a href="?pagina=objeto&deletar='.$elemento->getId().'" class="btn btn-danger">Deletar</a>
                      </td>';
                echo '<tr>';
            }
            
        echo '
				</tbody>
			</table>
		</div>
            
            
            
                </div>
              </div>
            
            
';
    }
            
            
        public function mostrarSelecionado(Objeto $objeto){
            echo '<div class="row justify-content-center">';
            echo '
                        <div class="col-lg-3">
                          <!-- Default Card Example -->
                          <div class="card mb-4">
                            <div class="card-header">
                              <a href="?pagina=objeto&selecionar='.$objeto->getId().'">'.$objeto->getNome().'</a>
                            </div>
                            <div class="card-body">';
            foreach($objeto->getAtributos() as $atributo){
                echo '<a href="?pagina=atributo&selecionar='.$atributo->getId().'"> - '.$atributo->getNome().' : '.$atributo->getTipo().' '.$atributo->getIndice().'</a><br>';
                
            }
            
            
            echo '
                
                
                            </div>
                          </div>
                        </div>';
            echo '</div>';
    }

	public function mostraFormEditar(Objeto $objeto) {
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
										<h1 class="h4 text-gray-900 mb-4"> Adicionar Objeto</h1>
									</div>
						              <form class="user" method="post">
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" value="'.$objeto->getNome().'" id="nome" name="nome" placeholder="nome">
                						</div>
                                        
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Alterar" name="editar_objeto">
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
    
    public function confirmarDeletar(Objeto $objeto) {
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
										<h1 class="h4 text-gray-900 mb-4"> Deletar Objeto</h1>
									</div>
						              <form class="user" method="post">                    Tem Certeza que deseja deletar o '.$objeto->getNome().'
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Deletar" name="deletar_objeto">
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
            
}