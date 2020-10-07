<?php
          
namespace GetCrudByUML\custom\view;

use GetCrudByUML\view\UsuarioView;

/**
 * Classe de visao para Usuario
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */
class UsuarioCustomView extends UsuarioView {

    public function mostraFormInserir() {
        echo '
<button type="button" class="btn btn-outline-secondary m-3" data-toggle="modal" data-target="#modalAddUsuario">
  Create Account
</button>
     
<!-- Modal -->
<div class="modal fade" id="modalAddUsuario" tabindex="-1" role="dialog" aria-labelledby="labelAddUsuario" aria-hidden="true">

  <div class="modal-dialog">
    <div class="modal-content">
        <form class="user" method="post" id="form_enviar_usuario">
      <div class="modal-header">
        <h5 class="modal-title" id="labelAddUsuario">Create Account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            
            
            
            <span id="local-do-email"></span>
        
            <div class="form-group">
              <input type="text" class="form-control" id="nome" name="nome" placeholder="Name" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" id="login" name="login" placeholder="Login" pattern="[a-zA-Z0-9]+" required>
            </div>
            <div class="form-group">
              <input type="mail" class="form-control" id="email" name="email" placeholder="E-mail" required>
            </div>
            <div class="form-group row">
              <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="password" autocomplete=​"new-password" class="form-control" id="exampleInputPassword" name="senha" placeholder="Password" required>
              </div>
              <div class="col-sm-6">
                <input type="password" autocomplete=​"new-password" class="form-control" id="exampleRepeatPassword" name="senha_confirmada" placeholder="Confirm Password" required>
              </div>
            </div>
                <input type="hidden" name="form_enviar_usuario" value="1">

             
            
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" href="?pagina=login">Já tenho conta.</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <input type="submit" name="form_enviar_usuario1" class="btn btn-primary" value="Submit">
      </div>
    </div>
    </form>     
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

    public function exibirLista($lista){
        if(count($lista) == 0){
            echo '
                
                
                
                
    <div class="alert alert-info list-group p-3" role="alert">
    Utilize o formulário acima para incluir softwares
    </div>
                
                
                
';
        }
        echo '<h3 class="m-3">Usuarios</h3>';
        echo '
            
<div class="list-group list-group-flush">';
        
        foreach($lista as $elemento){
            echo '
  <a href="?pagina=software&usuario_selecionado='.$elemento->getId().'" class="list-group-item list-group-item-action bg-light">'.$elemento->getNome().'</a>';
        }
        echo '
</div>
';
        
        
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
						                    <input type="password"  autocomplete=​"new-password"  class="form-control" id="exampleInputPassword" name="senha" placeholder="Senha">
						                  </div>
						                  <div class="col-sm-6">
						                    <input type="password"  autocomplete=​"new-password"  class="form-control" id="exampleRepeatPassword" name="senha_confirmada" placeholder="Repita sua Senha">
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
}