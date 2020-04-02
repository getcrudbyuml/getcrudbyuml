<?php
            
/**
 * Classe de visao para Software
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */
class SoftwareView {
	public function mostraFormInserir() {
		echo '
                    
<div class="col-md-12">
    <form class="user" method="post">
        <div class="form-group">
          <input type="text" class="form-control form-control-user campmo-selecionado" id="nome" name="nome" placeholder="Novo Software" required>
        </div>
        <input type="submit" class="btn btn-primary btn-user btn-block" value="Cadastrar" name="enviar_software">
        <br>
            
    </form>
</div>
                                          

';
	}
                                            
    public function exibirLista($lista){
        
        echo '
                    
<div class="list-group list-group-flush">';
        
        foreach($lista as $elemento){
            echo '
  <a href="?pagina=software&selecionar='.$elemento->getId().'" class="list-group-item list-group-item-action bg-light">'.$elemento->getNome().'</a>';
        }  
        echo '
</div>
';


    }
            
            
        public function mostrarSelecionado(Software $software){
            
            
            if(count($software->getObjetos()) == 0){
                echo '<div class="alert alert-info" role="alert">
                        Começe inserindo alguma classe! Use o formulário acima!
                        </div>';
                
            }
            foreach($software->getObjetos() as $objeto){
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
            }
            

    }

	public function mostraFormEditar(Software $software) {
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
										<h1 class="h4 text-gray-900 mb-4"> Adicionar Software</h1>
									</div>
						              <form class="user" method="post">
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" value="'.$software->getNome().'" id="nome" name="nome" placeholder="nome">
                						</div>
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Alterar" name="editar_software">
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
    
    public function confirmarDeletar(Software $software) {
		echo '
    
		    <div class="d-flex justify-content-center">
			<div class="col-xl-6 col-lg-6 col-md-12">
    
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
    
							<div class="col-lg-12">
								<div class="p-5">
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4"> Deletar Software</h1>
									</div>
						              <form class="user" method="post">                    Tem Certeza que deseja deletar o '.$software->getNome().'
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Deletar" name="deletar_software">
                                        <hr>
                                            
						              </form>
                                            
								</div>
							</div>
						</div>
					</div>
				</div>
                                            
			</div>
			</div>

';
	}        
            
}