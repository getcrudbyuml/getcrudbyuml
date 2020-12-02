<?php 


namespace GetCrudByUML\controller;
use GetCrudByUML\custom\controller\UsuarioCustomController;
use GetCrudByUML\model\Usuario;
use GetCrudByUML\util\Sessao;
use blogGetCrudByUML\custom\controller\PostsCustomController;

class MainController{
    
    public static function mainDeslogado(){
        if(!isset($_GET['pagina'])){
            HomeController::main();
            return;
        }
        switch ($_GET['pagina']) {
            case 'login':
                $controller = new UsuarioCustomController();
                $controller->login();
                break;
            case 'post':
                $controller = new PostsCustomController();
                $controller->main();
                break;
            default:
                echo '404';
                break;
        } 
    }
    public static function mainLogado(){
        if(!isset($_GET['pagina'])){
            HomeController::main();
            return;
        }
            
            
        switch ($_GET['pagina']) {
            case 'software':
                $controller = new SoftwareController();
                $controller->selecionar();
                $controller->deletar();
                $controller->editar();
                
                break;
            case 'auditoria':
                $controller = new AuditoriaController();
                $controller->listar();
                break;
            case 'objeto':
                ObjetoController::main();
                break;
            case 'atributo':
                AtributoController::main();
                break;
            
            case 'mudar_senha':
                $controller = new UsuarioCustomController();
                $usuario = new Usuario();
                $sessao = new Sessao();
                $usuario->setId($sessao->getIdUsuario());
                $controller->editarSenha($usuario);
                break;
            case 'post':
                $controller = new PostsCustomController();
                $controller->main();
                break;
            default:
                echo '404';
                break;
                
        }
        
        
    }
    public static function main(Sessao $sessao){
        if($sessao->getNivelAcesso() == Sessao::NIVEL_DESLOGADO){
            self::mainDeslogado();
        }else{
            self::mainLogado();
        }
    }
    
    
    
}








?>