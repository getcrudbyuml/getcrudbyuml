<?php
				
/**
 * Classe de visao para Usuario
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */				
class UsuarioView {
    public function mostraFormInserir($mensagem = ''){
        
        if($mensagem != ''){
            $mensagem = '
<div class="alert alert-danger" role="alert">
  '.$mensagem.'
</div>

';
        }
        echo '

<div class="container">

	<!-- Outer Row -->
	<div class="row justify-content-center">

		<div class="col-xl-6 col-lg-12 col-md-9">

			<div class="card o-hidden border-0 shadow-lg my-5">
				<div class="card-body p-0">
					<!-- Nested Row within Card Body -->
					<div class="row">

						<div class="col-lg-12">
                        
							<div class="p-5">
                            '.$mensagem.'
          <form class="user" method="post">
        
            <div class="form-group">
              <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome Completo" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" id="login" name="login" placeholder="Login" required>
            </div>
            <div class="form-group row">
              <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="password" class="form-control" id="exampleInputPassword" name="senha" placeholder="Senha" required>
              </div>
              <div class="col-sm-6">
                <input type="password" class="form-control" id="exampleRepeatPassword" name="senha_confirmada" placeholder="Repita sua Senha" required>
              </div>
            </div>

        <input type="submit" class="btn btn-primary" value="Cadastrar" name="enviar_usuario">
</form>



							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



';
        
       
    }
    public function modalConfirmarNivel(){
        echo '


<!-- Modal -->
<div class="modal fade" id="modal-confirmar-nivel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="?pagina=usuario" method="post">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Alterar Nível de Acesso</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            
                <input type="hidden" name="id-usuario" id="campo-id-usuario" value="0">
                <div class="form-group">
                    <select class="custom-select" id="simbolo" name="nivel" required>
                        <option selected value="">Selecione o Novo Nível</option>
                        <option value="'.Sessao::NIVEL_ADM.'">Administrador</option>
                        <option value="'.Sessao::NIVEL_DENTISTA.'">Dentista</option>
                        <option value="'.Sessao::NIVEL_ATENDENTE.'">Atendente</option>
                        <option value="'.Sessao::NIVEL_INATIVO.'">Inativo</option>
                    </select>
				</div>
        
      </div>
      <div class="modal-footer">
        <input type="submit" value="Confirmar" name="editar_nivel" class="btn btn-primary" >
      </div>
    </div>
  </div>
    </form>
</div>

';
        
        
    }
    public function formConfirmacao(){
        echo '
            
<div class="container">
            
	<!-- Outer Row -->
	<div class="row justify-content-center">
            
		<div class="col-xl-6 col-lg-12 col-md-9">
            
			<div class="card o-hidden border-0 shadow-lg my-5">
				<div class="card-body p-0">
					<!-- Nested Row within Card Body -->
					<div class="row">
            
						<div class="col-lg-12">
							<div class="p-5">
            
            
            
                       
                            <div class="form-group">
                              <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome Completo">
                            </div>
                            <div class="form-group">
                              <input type="email" class="form-control" id="email" name="email" placeholder="Endereço de E-mail">
                            </div>
                            <div class="form-group row">
                              <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="password" class="form-control" id="exampleInputPassword" name="senha" placeholder="Senha">
                              </div>
                              <div class="col-sm-6">
                                <input type="password" class="form-control" id="exampleRepeatPassword" name="senha_confirmada" placeholder="Repita sua Senha">
                              </div>
                            </div>
                            <input type="submit" class="btn btn-primary" value="Cadastrar" name="enviar_usuario">
                                        
            
            
            
            
            
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
            
            
';
        
        
        
    }
	public function formLogin(){
	    echo '         

<div class="container">

	<!-- Outer Row -->
	<div class="row justify-content-center">

		<div class="col-xl-6 col-lg-12 col-md-9">

			<div class="card o-hidden border-0 shadow-lg my-5">
				<div class="card-body p-0">
					<!-- Nested Row within Card Body -->
					<div class="row">

						<div class="col-lg-12">
							<div class="p-5">
    
                                <form id="login-form" class="form" action="" method="post">
                                    <h3 class="text-center text-info">Login</h3>
                                    <div class="form-group">
                                        <label for="username" class="text-info">Login ou E-mail:</label><br>
                                        <input type="text" name="login" id="username" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="text-info">Senha:</label><br>
                                        <input type="password" name="senha" id="password" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="form_login" class="btn btn-info btn-md" value="Entrar">
                                    </div>
                                    
                                </form>


							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


';
	}
	
	public function formEditar(Usuario $usuario){
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
										<h1 class="h4 text-gray-900 mb-4">Editar Meus Dados</h1>
									</div>
						              <form class="user" method="post">
						                <div class="form-group">
                                            <label for="nome">Nome:</label>
						                  <input value="'.$usuario->getNome().'" type="text"  class="form-control" id="nome" name="nome" placeholder="Nome Completo">
						                </div>
						                <div class="form-group">
                                        <label for="email">E-mail:</label>    
						                  <input value="'.$usuario->getEmail().'" type="email" class="form-control" id="email" name="email" placeholder="Endereço de E-mail">
						                </div>
						                 
						                <input type="submit" class="btn btn-primary btn-user btn-block" value="Alterar" name="editar_usuario">
	                                    
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
	
	public function editarSenha($mensagem = ''){
	    if($mensagem != ''){
	        $mensagem = '
<div class="alert alert-danger" role="alert">
  '.$mensagem.'
</div>
';
	    }
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
                                    '.$mensagem.'
									<div class="text-center">
										<h1 class="h4 text-gray-900 mb-4">Digite sua nova senha duas vezes</h1>
									</div>
						              <form class="user" method="post">
						                <div class="form-group row">
						                  <div class="col-sm-6 mb-3 mb-sm-0">
						                    <input type="password" class="form-control" id="exampleInputPassword" name="senha" placeholder="Senha">
						                  </div>
						                  <div class="col-sm-6">
						                    <input type="password" class="form-control" id="exampleRepeatPassword" name="senha_confirmada" placeholder="Repita sua Senha">
						                  </div>
						                </div>
						                <input type="submit" class="btn btn-primary btn-user btn-block" value="Alterar" name="atualizar_senha">
	        
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
	    
	    echo '
	        
	        
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Lista de Usuários</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" data-toggle="modal" data-target="#modal-add-usuario">
                      <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i>
                    </a>
	        
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">';
	    
	    echo '
	        
	        
	        
	        
	        
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%"
				cellspacing="0">
				<thead>
					<tr>
						<th>id</th>
						<th>Nome</th>
						<th>Nível de Acesso</th>

					</tr>
				</thead>
				<tbody>';
	    
	    foreach($lista as $elemento){
	        echo '<tr>';
	        echo '<td>'.$elemento->getId().'</td>';
	        echo '<td>'.$elemento->getNome().'</td>';
	        echo '<td>
                    <button type="button" class="btn btn-success botao-editar-nivel" href="'.$elemento->getId().'" data-toggle="modal" data-target="#modal-confirmar-nivel">
                      '.$elemento->getStrNivel().'
                    </button>
                </td>';
	        
	        echo '</tr>';
	    }
	    
	    echo '
				</tbody>
			</table>
		</div>
';
	    echo '
	        
                </div>
              </div>';

	}
	
	public function exibirMensagem($strMensagem){
	    echo '<p>'.$strMensagem.'</p>';
	}
	
}