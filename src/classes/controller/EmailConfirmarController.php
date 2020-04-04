<?php 


class EmailConfirmarController{
    
    
    
    public function verificar(){
        $sessao = new Sessao();
        if($sessao->getNivelAcesso() == Sessao::NIVEL_VERIFICADO){
            echo "Seu e-mail foi confirmado! Complete o seu cadastro! ";
            echo $sessao->getLoginUsuario();
            return;
        }
        
        if(!isset($_GET['pagina'])){
            echo 'Código Inválido 1. Tente outra vez.';
            return;
        }
        if($_GET['pagina'] != 'verificar'){
            echo 'Código Inválido 2. Tente outra vez.';
            return;
        }
        if(!isset($_GET['codigo'])){
            echo 'Código Inválido 3. Tente outra vez.';
            return;
        }
        
        
        
        $emailDao = new EmailConfirmarDAO();
        $emailConfirmar = new EmailConfirmar();
        $emailConfirmar->setCodigo($_GET['codigo']);
        if(!$emailDao->pesquisaPorCodigo($emailConfirmar)){
            echo 'Código Inválido 3. Tente outra vez.';
            return;
        }
        $emailDao->excluir($emailConfirmar);        
        $sessao->criaSessao(Sessao::NIVEL_DESLOGADO, Sessao::NIVEL_VERIFICADO, $emailConfirmar->getEmail());
        echo "Seu e-mail foi confirmado! Complete o seu cadastro!";
        echo $emailConfirmar->getEmail();
        
        
    }
    
    
    
    
    
}










?>