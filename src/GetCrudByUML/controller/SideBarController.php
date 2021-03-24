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



';
       
        echo '<b>PIX: jefponte@gmail.com</b>


</div>


    </div>
    <!-- /#sidebar-wrapper -->';







    }
    
    
    
    
}