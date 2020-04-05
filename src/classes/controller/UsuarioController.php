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
	public function cadastrar($nivelDeAcesso) {

	    $sessao = new Sessao();
	    if($sessao->getNivelAcesso() != Sessao::NIVEL_VERIFICADO){
	        return;
	    }
	    
        if(!isset($this->post['enviar_usuario'])){
            $this->view->mostraFormInserir();
		    return;
		}
		if (! ( isset ( $this->post ['nome'] ) && isset ( $this->post ['senha'] ))) 
		{
			$this->view->mostraFormInserir("Digite todos os campos para continuar.");
			return;
		}
		if (!isset ( $this->post ['login'] )) {
		    $this->view->mostraFormInserir("Digite todos os campos para continuar.");
		    return;
		}
		if ($this->post ['login'] == "" || strlen($this->post ['login']) < 3) 
		{
		    $this->view->mostraFormInserir("Digite um login válido");
		    return;
		}
		if($this->post['senha'] != $this->post['senha_confirmada'])
		{
		    $this->view->mostraFormInserir("A confirmação da senha não está batendo.");
		    return;
		}
		
	
		$usuario = new Usuario ();		
		$usuario->setLogin ( $this->post['login']);
		
		if($this->dao->pesquisaPorLogin($usuario)){
		    
		    $this->view->mostraFormInserir("Este Login já pertence a outro usuário, tente outro.");
		    return;
		}
		
		
		$usuario->setNome ( $this->post ['nome'] );		
		$usuario->setEmail ( $sessao->getLoginUsuario());
		
		$usuario->setSenha ( md5 (  $this->post ['senha'] ));		
		$usuario->setNivel ( $nivelDeAcesso );	
		
		if ($this->dao->inserir ( $usuario )) {
		    $id = $this->dao->getConexao()->lastInsertId();
		    $usuario->setId($id);
		    
		    $sessao->criaSessao($id, Sessao::NIVEL_COMPLETO, $usuario->getLogin());
			echo "Sucesso";
		} else {
		    $this->view->mostraFormInserir("Falha na gravação dos dados. Tente novamente.");
			
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