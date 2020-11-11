<?php

namespace GetCrudByUML\controller;
use GetCrudByUML\util\Sessao;

class NavBarController{
    
    public static function main(Sessao $sessao)
    {
        
        echo '


      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">';
        if($sessao->getNivelAcesso() == Sessao::NIVEL_COMPLETO || $sessao->getNivelAcesso() == Sessao::NIVEL_ADM){
            echo '
        <button class="btn btn-primary" id="menu-toggle">Menu</button>';
        }
        
        echo '

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item active">
              <a class="nav-link" href="./">';
        if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt')
        {
            echo 'Início';
        }else{
            echo 'Home';
        }
              
              echo '</a>
            </li>';
              
             echo '
            <li class="nav-item">
              <a class="nav-link" href="index.php#noticias">Blog</a>
            </li>';
              
               
        
        if($sessao->getNivelAcesso() == Sessao::NIVEL_DESLOGADO || $sessao->getNivelAcesso() == Sessao::NIVEL_VERIFICADO)
        {
            echo '
    
              <li class="nav-item"> 
                <a class="nav-link" href="?pagina=login">Login</a>
             </li> 

              <li class="nav-item"> 
                <a class="nav-link" data-toggle="modal" data-target="#modalAddUsuario">Create Account</a>
             </li> 


 ';
            
        }else if($sessao->getNivelAcesso() == Sessao::NIVEL_COMPLETO || $sessao->getNivelAcesso() == Sessao::NIVEL_ADM){
            echo '
                
                
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ';
            if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
                echo 'Configurações';
            }else{
                echo 'Set up Account';
            }
                
                echo '
              </a>
                
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="?pagina=mudar_senha">                ';
            if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
                echo 'Mudar Senha';
            }else{
                echo 'Change Password';
            }
                
                echo '</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="?sair=1">';
            if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2) == 'pt'){
                echo 'Sair';
            }else{
                echo 'Sign Out';
            }
                
                echo '</a>
              </div>
            </li>';
        }

        echo '
          </ul>
        </div>
      </nav>

';
        
        
    }
    
    
}