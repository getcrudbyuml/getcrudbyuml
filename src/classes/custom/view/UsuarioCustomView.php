<?php
            
/**
 * Classe de visao para Usuario
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */
class UsuarioCustomView extends UsuarioView {

    public function mostraFormInserir() {
        echo '
<button type="button" class="btn btn-outline-secondary m-3" data-toggle="modal" data-target="#modalAddUsuario">
      Quero Começar
    </button> 
            
<!-- Modal -->
<div class="modal fade" id="modalAddUsuario" tabindex="-1" role="dialog" aria-labelledby="labelAddUsuario" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="labelAddUsuario">Adicionar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            
            
            
           <form class="user" method="post">
        
            <div class="form-group">
              <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome Completo" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" id="login" name="login" placeholder="Login" required>
            </div>
            <div class="form-group">
              <input type="mail" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group row">
              <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="password" class="form-control" id="exampleInputPassword" name="senha" placeholder="Senha" required>
              </div>
              <div class="col-sm-6">
                <input type="password" class="form-control" id="exampleRepeatPassword" name="senha_confirmada" placeholder="Repita sua Senha" required>
              </div>
            </div>
        </form>

             
            
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" href="?pagina=login">Já tenho conta.</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button form="form_enviar_usuario" type="submit" class="btn btn-primary">Cadastrar</button>
      </div>
    </div>
  </div>
</div>
            
            
            
';
    }


}