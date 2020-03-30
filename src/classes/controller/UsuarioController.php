<?php	

/**
 * Classe feita para manipulação do objeto Usuario
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class UsuarioController {
	private $post;
	private $view;
	private $dao;
	public static function main($nivelDeAcesso)
	{
	    switch ($nivelDeAcesso){
	        case Sessao::NIVEL_ADM:
	            self::mainAdm();
	            break;
	        default:
	            echo "Pagina Inacessível";
	            break;
	    }

	}
	public static function mainAdm(){
	    $controller = new UsuarioController();
	    if (isset($_GET['selecionar'])){
	        echo '<div class="row justify-content-center">';
	        $controller->selecionar();
	        echo '</div>';
	        return;
	    }
	    echo '
		<div class="row justify-content-center">';
	    echo '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">';
	    
	    $controller->adminApp();
	    echo '</div>';
	    echo '<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">';
        $controller->cadastrar(Sessao::NIVEL_ATENDENTE);
	    echo '</div>';
	    echo '</div>';
	}
	public function __construct(){		
		$this->view = new UsuarioView();
		$this->dao = new UsuarioDAO();
		foreach($_POST as $chave => $valor){
			$this->post[$chave] = $valor;
		}
	}
	
	
	public function editarPerfil(Usuario $usuario){
	    if(!isset($this->post['editar_usuario'])){
	        $this->view->formEditar($usuario);
	        return;
	    }
	    
	    if (! ( isset ( $this->post ['nome'] ) && isset ( $this->post ['email'] ))) {
	        echo "Incompleto";
	        return;
	    }
	    
	    
	    
	    $usuario->setNome ( $this->post ['nome'] );
	    $usuario->setEmail ( $this->post ['email'] );

	    
	    if ($this->dao->atualizar( $usuario )) {
	        echo "Sucesso";
	    } else {
	        echo "Fracasso";
	    }
	    echo '<META HTTP-EQUIV="REFRESH" CONTENT="2; URL=index.php?pagina=editar_perfil">';
	}
	public function editarSenha(Usuario $usuario)
	{
	    if(!isset($this->post['atualizar_senha'])){
	        $this->view->editarSenha();
	        return;
	    }
	    
	    if (! ( isset ( $this->post ['senha'] ) && isset ( $this->post ['senha_confirmada'] ))) {
	        echo "Incompleto";
	        return;
	    }
	    if($this->post['senha'] != $this->post['senha_confirmada']){
	        echo "As senhas digitadas não são iguais. ";
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
	public function cadastrar($nivelDeAcesso) {

        if(!isset($this->post['enviar_usuario'])){
            $this->view->mostraFormInserir();
		    return;
		}
		if (! ( isset ( $this->post ['nome'] ) && isset ( $this->post ['email'] ) && isset ( $this->post ['senha'] ))) {
			echo "Incompleto";
			return;
		}
		if($this->post['senha'] != $this->post['senha_confirmada']){
		    echo "A confirmação da senha não está igual. ";
		    return;
		}
	
		$usuario = new Usuario ();		
		$usuario->setNome ( $this->post ['nome'] );		
		$usuario->setEmail ( $this->post ['email'] );		
		$usuario->setLogin ( $this->post ['email'] );		
		$usuario->setSenha ( md5 (  $this->post ['senha'] ));		
		$usuario->setNivel ( $nivelDeAcesso );	
		
		if ($this->dao->inserir ( $usuario )) {
			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=usuario">';
	}
	public function login(){
	    $this->view->formLogin();
	    if(!isset($this->post['form_login'])){
	       return; 
	    }
	    if (! (isset($this->post['login']) && isset ( $this->post ['senha'] ))) {
	        echo "Incompleto";
	        return;
	    }
	    $usuarioDAO = new UsuarioDAO();
	    $usuario = new Usuario();
	    $usuario->setLogin($this->post['login']);
	    
	    $usuario->setSenha(md5($this->post['senha']));
	    if($usuarioDAO->autentica($usuario)){
	        
	        $sessao2 = new Sessao();
	        $sessao2->criaSessao($usuario->getId(), $usuario->getNivel(), $usuario->getLogin());
	        echo '<meta http-equiv="refresh" content=0;url="./index.php">';
	        return;
	    }
	    echo 'Errou usuario ou senha';
	}
				
	public function adminApp(){
	    $this->view->modalConfirmarNivel();
	    $this->editarNivel();
	    $this->listar();
	}
	public function listar() {
	    $usuarioDao = new UsuarioDAO ();
	    $lista = $usuarioDao->retornaLista ();
	    $this->view->exibirLista($lista);
	}
	public function editarNivel(){
	    
	    if(!isset($_POST['editar_nivel'])){
	        return;
	    }
	    
	    $usuario = new Usuario();
	    $usuario->setId($_POST['id-usuario']);
	    $usuario->setNivel($_POST['nivel']);
	    
	    if($this->dao->atualizarNivelPorId($usuario)){
	        $this->view->exibirMensagem("Nível de Acesso Alterado Com Sucesso!");
	    }else{
	        $this->view->exibirMensagem("Falha ao tentar Inserir Usuário!");
	    }
	    echo '<meta http-equiv="refresh" content=1;url="?pagina=usuario">';
	}
		
}
?>