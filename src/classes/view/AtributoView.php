<?php
            
/**
 * Classe de visao para Atributo
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */
class AtributoView {
    /**
     * 
     * @param array Objeto $listaObjetos
     * @param array Objeto $listaTipos
     */
	public function mostraFormInserir($listaObjetos, $listaTipos = array()) {
		echo '
    
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
										<h1 class="h4 text-gray-900 mb-4"> Adicionar Atributo</h1>
									</div>
						              <form class="user" method="post">
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user campmo-selecionado" id="nome" name="nome" placeholder="nome">
                						</div>
                                        <div class="form-group">
                						  <select class="form-control form-control-user" id="tipo" name="tipo" placeholder="tipo">
                                            <option value="'.Atributo::TIPO_STRING.'">String</option>
                                            <option value="'.Atributo::TIPO_INT.'">Inteiro</option>
                                            <option value="'.Atributo::TIPO_BOOLEAN.'">Boolean</option>
                                            <option value="'.Atributo::TIPO_FLOAT.'">Float</option>
                                            <option value="'.Atributo::TIPO_DATE.'">Data</option>
                                            <option value="'.Atributo::TIPO_DATE_TIME.'">Data e Hora</option>
';

		 
		foreach($listaTipos as $tipo){
		    echo '<option value="'.$tipo->getNome().'">'.$tipo->getNome().'</option>';
		}
		foreach($listaTipos as $tipo){
		    echo '<option value="'.Atributo::TIPO_ARRAY_NN.' '.$tipo->getNome().'">Array n:n '.$tipo->getNome().'</option>';
		    
		}
		foreach($listaTipos as $tipo){
		    echo '<option value="'.Atributo::TIPO_ARRAY_1N.' '.$tipo->getNome().'">Array 1:n '.$tipo->getNome().'</option>';
		}

                                          echo '
                                            </select>
                						</div>
                                        <div class="form-group">
                						  <select class="form-control form-control-user" id="indice" name="indice">
                                                <option value="">Nenhum Indice</option>
                                                <option value="'.Atributo::INDICE_PRIMARY.'">PRIMARY KEY</option>
                                           </select>
                						</div>';
		if(count($listaObjetos)){
		    
		    echo '
                                        <div class="form-group">
                						  <select class="form-control form-control-user" id="idobjeto" name="idobjeto">
                                            <option>Selecione um Objeto</option>';
		    foreach($listaObjetos as $objeto){
		        echo '                    <option value="'.$objeto->getId().'">'.$objeto->getNome().'</option>';
		    }
		    echo '
                                            
                                          </select>
                						</div>';
		}
		echo '

                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Cadastre-se" name="enviar_atributo">
                                            
						              </form>
                                            
								</div>
							</div>
						</div>
					</div>
				</div>
                                            
			</div>
                                            
		</div>';
	}
                                            
    public function exibirLista($lista){
           echo '
                                            
<div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">atributo</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Menu:</div>
                      <a class="dropdown-item" href="?pagina=atributo&cadastrar=1">Adicionar atributo</a>
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
						<th>tipo</th><th>Ações</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
                        <th>id</th>
                        <th>nome</th>
                        <th>tipo</th><th>Ações</th>
					</tr>
				</tfoot>
				<tbody>';
            
            foreach($lista as $elemento){
                echo '<tr>';
                echo '<td>'.$elemento->getId().'</td>';
                echo '<td>'.$elemento->getNome().'</td>';
                echo '<td>'.$elemento->getTipo().'</td>';echo '<td>
                        <a href="?pagina=atributo&selecionar='.$elemento->getId().'" class="btn btn-info">Selecionar</a> 
                        <a href="?pagina=atributo&editar='.$elemento->getId().'" class="btn btn-success">Editar</a>
                        <a href="?pagina=atributo&deletar='.$elemento->getId().'" class="btn btn-danger">Deletar</a>
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
            
            
        public function mostrarSelecionado(Atributo $atributo){
            echo '<div class="row justify-content-center">';
            echo '
                <div class="col-lg-3">
                  <!-- Default Card Example -->
                  <div class="card mb-4">
                    <div class="card-header">
                      Atributo selecionado
                    </div>
                    <div class="card-body">
                    Id: '.$atributo->getId().'<br>
                    Nome: '.$atributo->getNome().'<br>
                    Tipo: '.$atributo->getTipo().'<br>
                    Indice: '.$atributo->getIndice().'<br>
                           
                    </div>
                  </div>
                </div>';
            echo '</div>';

    }

	public function mostraFormEditar(Atributo $atributo) {
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
										<h1 class="h4 text-gray-900 mb-4"> Editar Atributo</h1>
									</div>
						              <form class="user" method="post">
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" value="'.$atributo->getNome().'" id="nome" name="nome" placeholder="nome">
                						</div>
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" value="'.$atributo->getTipo().'" id="tipo" name="tipo" placeholder="tipo">
                						</div>
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" value="'.$atributo->getIndice().'" id="indice" name="indice" placeholder="indice">
                						</div>
                                        
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Alterar" name="editar_atributo">
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
    
    public function confirmarDeletar(Atributo $atributo) {
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
										<h1 class="h4 text-gray-900 mb-4"> Deletar Atributo</h1>
									</div>
						              <form class="user" method="post">                    Tem Certeza que deseja deletar o '.$atributo->getNome().'
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Deletar" name="deletar_atributo">
                                            
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