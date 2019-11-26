<?php

/**
 * Classe feita para manipulaÃ§Ã£o do objeto Usuario
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson UchÃ´a Ponte <j.pontee@gmail.com>
 */
class UsuarioController {
    private $post;
    private $view;
    private $dao;
    
    public static function main(){
        $controller = new UsuarioController();
        if (!(isset($_GET['cadastrar']) || isset($_GET['selecionar']) || isset($_GET['editar']) || isset($_GET['deletar']) )){
            $controller->listar();
        }
        $controller->cadastrar();
        $controller->selecionar();
        $controller->editar();
        $controller->deletar();
    }
    public function __construct(){
        $this->dao = new UsuarioDAO();
        $this->view = new UsuarioView();
        foreach($_POST as $chave => $valor){
            $this->post[$chave] = $valor;
        }
    }
    public function listar() {
        $usuarioDao = new UsuarioDAO ();
        $lista = $usuarioDao->retornaLista ();
        $this->view->exibirLista($lista);
    }
    public function selecionar(){
        if(!isset($_GET['selecionar'])){
            return;
        }
        $selecionado = new Usuario();
        $selecionado->setId($_GET['selecionar']);
        $this->dao->pesquisaPorId($selecionado);
        $this->view->mostrarSelecionado($selecionado);
    }
    
    public function cadastrar($nivelDeAcesso = Sessao::NIVEL_COMUM) {
        
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
        $usuarioDao = new UsuarioDAO ();
        if ($usuarioDao->inserir ( $usuario )) {
            echo "Sucesso";
        } else {
            echo "Fracasso";
        }
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=usuario">';
    }
    
    public function editar(){
        if(!isset($_GET['editar'])){
            return;
        }
        $selecionado = new Usuario();
        $selecionado->setId($_GET['editar']);
        $this->dao->pesquisaPorId($selecionado);
        
        if(!isset($_POST['editar_usuario'])){
            $this->view->mostraFormEditar($selecionado);
            return;
        }
        
        if (! ( isset ( $this->post ['nome'] ) && isset ( $this->post ['email'] ) && isset ( $this->post ['login'] ) && isset ( $this->post ['senha'] ) && isset ( $this->post ['nivel'] ))) {
            echo "Incompleto";
            return;
        }
        
        $selecionado->setNome ( $this->post ['nome'] );
        $selecionado->setEmail ( $this->post ['email'] );
        $selecionado->setLogin ( $this->post ['login'] );
        $selecionado->setSenha ( $this->post ['senha'] );
        $selecionado->setNivel ( $this->post ['nivel'] );
        
        if ($this->dao->atualizar ($selecionado ))
        {
            
            echo "Sucesso";
        } else {
            echo "Fracasso";
        }
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; URL=index.php?pagina=usuario">';
        
    }
    public function deletar(){
        if(!isset($_GET['deletar'])){
            return;
        }
        $selecionado = new Usuario();
        $selecionado->setId($_GET['deletar']);
        $this->dao->pesquisaPorId($selecionado);
        if(!isset($_POST['deletar_usuario'])){
            $this->view->confirmarDeletar($selecionado);
            return;
        }
        if($this->dao->excluir($selecionado)){
            echo "excluido com sucesso";
        }else{
            echo "Errou";
        }
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=usuario">';
    }
    public function listarJSON()
    {
        $usuarioDao = new UsuarioDAO ();
        $lista = $usuarioDao->retornaLista ();
        $listagem = array ();
        foreach ( $lista as $linha ) {
            $listagem ['lista'] [] = array (
                'id' => $linha->getId (),
                'nome' => $linha->getNome (),
                'email' => $linha->getEmail (),
                'login' => $linha->getLogin (),
                'senha' => $linha->getSenha (),
                'nivel' => $linha->getNivel ()
                
                
            );
        }
        echo json_encode ( $listagem );
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
        
        $usuarioDao = new UsuarioDAO ();
        if ($usuarioDao->atualizar( $usuario )) {
            echo "Sucesso";
        } else {
            echo "Fracasso";
        }
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="2; URL=index.php?pagina=editar_perfil">';
    }
    public function editarSenha(Usuario $usuario){
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
        
        $usuarioDao = new UsuarioDAO ();
        
        if ($usuarioDao->atualizarSenha($usuario)) {
            echo "Sucesso";
        } else {
            echo "Fracasso";
        }
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="2; URL=index.php?pagina=editar_senha">';
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
            echo '<meta http-equiv="refresh" content=1;url="./index.php">';
            return;
        }
        echo 'Errou usuario ou senha';
    }
    
}
?>