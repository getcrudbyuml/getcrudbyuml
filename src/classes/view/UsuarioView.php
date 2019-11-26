<?php

/**
 * Classe de visao para Usuario
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */
class UsuarioView {
    public function mostraFormInserir(){
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
										<h1 class="h4 text-gray-900 mb-4">Bem-vindo!</h1>
									</div>
						              <form class="user" method="post">
						                <div class="form-group">
						                  <input type="text" class="form-control form-control-user" id="nome" name="nome" placeholder="Nome Completo">
						                </div>
						                <div class="form-group">
						                  <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Endereço de E-mail">
						                </div>
						                <div class="form-group row">
						                  <div class="col-sm-6 mb-3 mb-sm-0">
						                    <input type="password" class="form-control form-control-user" id="exampleInputPassword" name="senha" placeholder="Senha">
						                  </div>
						                  <div class="col-sm-6">
						                    <input type="password" class="form-control form-control-user" id="exampleRepeatPassword" name="senha_confirmada" placeholder="Repita sua Senha">
						                  </div>
						                </div>
						                <input type="submit" class="btn btn-primary btn-user btn-block" value="Cadastre-se" name="enviar_usuario">
            
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
                  <h6 class="m-0 font-weight-bold text-primary">usuario</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Menu:</div>
                      <a class="dropdown-item" href="?pagina=usuario&cadastrar=1">Adicionar usuario</a>
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
						<th>email</th><th>Ações</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
                        <th>id</th>
                        <th>nome</th>
                        <th>email</th><th>Ações</th>
					</tr>
				</tfoot>
				<tbody>';
        
        foreach($lista as $elemento){
            echo '<tr>';
            echo '<td>'.$elemento->getId().'</td>';
            echo '<td>'.$elemento->getNome().'</td>';
            echo '<td>'.$elemento->getEmail().'</td>';echo '<td>
                        <a href="?pagina=usuario&selecionar='.$elemento->getId().'" class="btn btn-info">Selecionar</a>
                        <a href="?pagina=usuario&editar='.$elemento->getId().'" class="btn btn-success">Editar</a>
                        <a href="?pagina=usuario&deletar='.$elemento->getId().'" class="btn btn-danger">Deletar</a>
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
    
    
    public function mostrarSelecionado(Usuario $usuario){
        echo '
            <div class="col-lg-3">
              <!-- Default Card Example -->
              <div class="card mb-4">
                <div class="card-header">
                  Usuario selecionado
                </div>
                <div class="card-body">
                Id: '.$usuario->getId().'<br>
                Nome: '.$usuario->getNome().'<br>
                Email: '.$usuario->getEmail().'<br>
                Login: '.$usuario->getLogin().'<br>
                Senha: '.$usuario->getSenha().'<br>
                Nivel: '.$usuario->getNivel().'<br>
                    
                </div>
              </div>
            </div>';
    }
    
    public function mostraFormEditar(Usuario $usuario) {
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
										<h1 class="h4 text-gray-900 mb-4"> Adicionar Usuario</h1>
									</div>
						              <form class="user" method="post">
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" value="'.$usuario->getNome().'" id="nome" name="nome" placeholder="nome">
                						</div>
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" value="'.$usuario->getEmail().'" id="email" name="email" placeholder="email">
                						</div>
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" value="'.$usuario->getLogin().'" id="login" name="login" placeholder="login">
                						</div>
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" value="'.$usuario->getSenha().'" id="senha" name="senha" placeholder="senha">
                						</div>
                                        <div class="form-group">
                						  <input type="text" class="form-control form-control-user" value="'.$usuario->getNivel().'" id="nivel" name="nivel" placeholder="nivel">
                						</div>
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Alterar" name="editar_usuario">
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
    
    public function confirmarDeletar(Usuario $usuario) {
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
										<h1 class="h4 text-gray-900 mb-4"> Deletar Usuario</h1>
									</div>
						              <form class="user" method="post">                    Tem Certeza que deseja deletar o '.$usuario->getNome().'
                                        <input type="submit" class="btn btn-primary btn-user btn-block" value="Deletar" name="deletar_usuario">
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
								<div class="text-center">
									<h1 class="h4 text-gray-900 mb-4">Faça o Login</h1>
								</div>
            
            
								<form class="user" method="post">
									<div class="form-group">
										<input type="text" name="login"
											class="form-control form-control-user" id="exampleInputEmail"
											aria-describedby="emailHelp"
											placeholder="Seu E-mail...">
									</div>
									<div class="form-group">
										<input type="password" name="senha"
											class="form-control form-control-user"
											id="exampleInputPassword" placeholder="Sua Senha">
									</div>
            
									<input type="submit" name="form_login"
										class="btn btn-primary btn-user btn-block" value="Login" />
            
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
            
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
						                  <input value="'.$usuario->getNome().'" type="text"  class="form-control form-control-user" id="nome" name="nome" placeholder="Nome Completo">
						                </div>
						                <div class="form-group">
                                        <label for="email">E-mail:</label>
						                  <input value="'.$usuario->getEmail().'" type="email" class="form-control form-control-user" id="email" name="email" placeholder="Endereço de E-mail">
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
    
    public function editarSenha(){
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
										<h1 class="h4 text-gray-900 mb-4">Digite sua nova senha duas vezes</h1>
									</div>
						              <form class="user" method="post">
						                <div class="form-group row">
						                  <div class="col-sm-6 mb-3 mb-sm-0">
						                    <input type="password" class="form-control form-control-user" id="exampleInputPassword" name="senha" placeholder="Senha">
						                  </div>
						                  <div class="col-sm-6">
						                    <input type="password" class="form-control form-control-user" id="exampleRepeatPassword" name="senha_confirmada" placeholder="Repita sua Senha">
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