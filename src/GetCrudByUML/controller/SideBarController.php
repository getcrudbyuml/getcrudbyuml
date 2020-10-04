<?php

namespace GetCrudByUML\controller;
use GetCrudByUML\custom\controller\UsuarioCustomController;
use GetCrudByUML\model\Usuario;
use GetCrudByUML\util\Sessao;


class SideBarController{
    

    public static function main(Sessao $sessao){
        if($sessao->getNivelAcesso() == Sessao::NIVEL_DESLOGADO || $sessao->getNivelAcesso() == Sessao::NIVEL_VERIFICADO){
            return;
        }
        echo '<!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading">getcrudbyuml.com</div>';
 

        $usuario = new Usuario();
        $usuario->setId($sessao->getIdUsuario());
        $softwareController = new SoftwareController();
        $softwareController->cadastrar();
        $softwareController->listarPorUsuario($usuario);
        if($sessao->getNivelAcesso() == Sessao::NIVEL_ADM){
            $controller = new UsuarioCustomController();
            $controller->listar();
        }

        echo '

<div class="row justify-content-center m-3">



<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="BXZLNT3CHZU7Q" />
<input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Faça doações com o botão do PayPal" />
<img alt="" border="0" src="https://www.paypal.com/pt_BR/i/scr/pixel.gif" width="1" height="1" />
</form>

</div>


    </div>
    <!-- /#sidebar-wrapper -->';







    }
    
    
    
    
}