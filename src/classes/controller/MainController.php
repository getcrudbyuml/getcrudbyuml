<?php 



class MainController{
    public static function main(Sessao $sessao){
        if($sessao->getNivelAcesso() == Sessao::NIVEL_DESLOGADO){
            if(!isset($_GET['pagina'])){
                HomeController::main();
                return;
            }
            switch ($_GET['pagina']) {
                case 'login':
                    $controller = new UsuarioController();
                    $controller->login();
                    break;
                case 'verificar':
                    
                    break;
            }            
        }
        
        
        if (isset($_GET['pagina'])) {
            
            switch ($_GET['pagina']) {
                case 'software':
                    $controller = new SoftwareController();
                    $controller->selecionar();
                    $controller->deletar();
                    $controller->editar();
                    
                    break;
                case 'objeto':
                    ObjetoController::main();
                    break;
                case 'atributo':
                    AtributoController::main();
                    break;
                case 'usuario':
                    UsuarioController::main();
                    break;
                case 'login':
                    $controller = new UsuarioController();
                    $controller->login();
                    break;
                    
                    
            }
        }
        else
        {
            
            HomeController::main();
        }
        
    }
    
    
    
}








?>