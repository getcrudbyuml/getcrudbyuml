<?php
            
/**
 * Customize o controller do objeto Usuario aqui 
 * @author Jefferson Uchôa Ponte <jefponte@gmail.com>
 */



class UsuarioCustomController  extends UsuarioController {
    

	public function __construct(){
		$this->dao = new UsuarioCustomDAO();
		$this->view = new UsuarioCustomView();
	}


	public function login(){
	    $this->view->formLogin();
	    
	    if(!isset($_POST['form_login'])){
	        return;
	    }
	    
	    if (! (isset($_POST['login']) && isset ( $_POST['senha'] ))) {
	        echo "Incompleto";
	        return;
	    }
	    $usuarioDAO = new UsuarioCustomDAO();
	    $usuario = new Usuario();
	    $usuario->setLogin($_POST['login']);
	    
	    $usuario->setSenha(md5($_POST['senha']));
	    
	    if($usuarioDAO->autentica($usuario)){
	        
	        $sessao2 = new Sessao();
	        $sessao2->criaSessao($usuario->getId(), $usuario->getNivel(), $usuario->getLogin());
	        echo '<meta http-equiv="refresh" content=0;url="./index.php">';
	        return;
	    }
	    echo 'Errou usuario ou senha';
	}
	public function editarSenha(Usuario $usuario)
	{
	    
	    
	    if(!isset($this->post['atualizar_senha'])){
	        $this->view->editarSenha();
	        return;
	    }
	    if (! ( isset ( $this->post ['senha'] ) && isset ( $this->post ['senha_confirmada'] ))) {
	        $this->view->editarSenha('Preencha com a mesma senha.');
	        return;
	    }
	    
	    if($this->post['senha'] != $this->post['senha_confirmada']){
	        
	        $this->view->editarSenha('As senhas digitadas não correspondem.');
	        return;
	    }
	    if (strlen($this->post ['senha']) == 0) {
	        $this->view->editarSenha('Digite uma senha.');
	        return;
	    }
	    if (strlen($this->post ['senha']) < 4) {
	        $this->view->editarSenha('Digite uma senha com mais caracteres.');
	        return;
	    }
	    
	    $usuario->setSenha( $this->post ['senha'] );
	    
	    if ($this->dao->atualizarSenha($usuario)) {
	        echo "Sucesso";
	    } else {
	        echo "Fracasso";
	    }
	    echo '<META HTTP-EQUIV="REFRESH" CONTENT="2; URL=index.php?pagina=editar_senha">';
	}
}
?>