<?php 


class LoginController{
    

    public static function main(){
        $controller = new LoginController();
        $controller->formLogin();
        
        
    }
    public function formConfirmacao(){
        echo '
            
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
            
';
        
    }
    
    
    
    
}



?>