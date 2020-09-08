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
	
	
	
	public function cadastrarAjax() {
	    if(!isset($_POST['form_enviar_usuario'])){
	        return;
	    }
	    
	    
	    if (! ( isset ( $_POST ['nome'] ) && isset ( $_POST ['email'] ) && isset ( $_POST ['login'] ) && isset ( $_POST ['senha'] ))) {
	        echo "Entrou";
	        echo ':incompleto';
	        return;
	    }
	    
	    if($_POST['senha'] != $_POST['senha_confirmada']){
	        echo ':falha_senhas';
	        return;
	    }
	    
	    
	    $usuario = new Usuario ();
	    $usuario->setNome ( $_POST ['nome'] );
	    $usuario->setEmail ( $_POST ['email'] );
	    $usuario->setLogin ( $_POST ['login'] );
	    $usuario->setSenha ( $_POST ['senha'] );
	    $usuario->setNivel ( Sessao::NIVEL_COMUM );
	    
	        
	    
	    
	    if(count($this->dao->pesquisaPorEmail($usuario)) > 0){
	        echo ':falha_email';
	        return;
	    }
	    if(count($this->dao->pesquisaPorLogin($usuario)) > 0){
	        echo ':falha_login';
	        return;
	    }
	    
	    if ($this->dao->inserir ( $usuario ))
	    {
	        $id = $this->dao->getConexao()->lastInsertId();
	        echo ':sucesso:'.$id;
	        
	    } else {
	        echo ':falha';
	        return;
	    }
	    
	    $to = $usuario->getEmail();
	    $subject = "GetCrudByID - Seu usuário foi cadastrado com sucesso!";
	    $message = "<p>Bem vindo ao getcrudbyuml! Seu usuário foi cadastrado com sucesso! Aproveite!</p>";
	    $headers = 'MIME-Version: 1.0' . "\r\n";
	    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	    $headers .= 'From: getCrudById <contato@getcrudbyuml.com>';
	    
	    mail($to, $subject, $message, $headers);
	    $sessao = new Sessao();
	    $sessao->criaSessao($id, Sessao::NIVEL_COMUM, $usuario->getLogin());
	}
}
?>