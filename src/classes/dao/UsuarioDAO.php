<?php

/**
 * Classe feita para manipulação do objeto Usuario
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte
 *
 *
 */
class UsuarioDAO extends DAO {
    
    
    public function atualizar(Usuario $usuario)
    {
        
        $id = $usuario->getId();
        $sql = "UPDATE usuario
                SET
                nome = :nome,
                email = :email,
                login = :login,
                senha = :senha,
                nivel = :nivel
                WHERE usuario.id = :id;";
        $nome = $usuario->getNome();
        $email = $usuario->getEmail();
        $login = $usuario->getLogin();
        $senha = $usuario->getSenha();
        $nivel = $usuario->getNivel();
        
        try {
            
            $stmt = $this->getConexao()->prepare($sql);
            $stmt->bindParam("id", $id, PDO::PARAM_STR);
            $stmt->bindParam("nome", $nome, PDO::PARAM_STR);
            $stmt->bindParam("email", $email, PDO::PARAM_STR);
            $stmt->bindParam("login", $login, PDO::PARAM_STR);
            $stmt->bindParam("senha", $senha, PDO::PARAM_STR);
            $stmt->bindParam("nivel", $nivel, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        
    }
    
    public function inserir(Usuario $usuario){
        
        $sql = "INSERT INTO usuario(nome, email, login, senha, nivel)
				VALUES(:nome, :email, :login, :senha, :nivel)";
        $nome = $usuario->getNome();
        $email = $usuario->getEmail();
        $login = $usuario->getLogin();
        $senha = $usuario->getSenha();
        $nivel = $usuario->getNivel();
        try {
            $db = $this->getConexao();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("nome", $nome, PDO::PARAM_STR);
            $stmt->bindParam("email", $email, PDO::PARAM_STR);
            $stmt->bindParam("login", $login, PDO::PARAM_STR);
            $stmt->bindParam("senha", $senha, PDO::PARAM_STR);
            $stmt->bindParam("nivel", $nivel, PDO::PARAM_STR);
            return $stmt->execute();
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }
    public function excluir(Usuario $usuario){
        $id = $usuario->getId();
        $sql = "DELETE FROM usuario WHERE id = :id";
        
        try {
            $db = $this->getConexao();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("id", $id, PDO::PARAM_INT);
            return $stmt->execute();
            
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }
    
    
    public function retornaLista() {
        $lista = array ();
        $sql = "SELECT * FROM usuario LIMIT 1000";
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ( $result as $linha ) {
            
            $usuario = new Usuario();
            
            $usuario->setId( $linha ['id'] );
            $usuario->setNome( $linha ['nome'] );
            $usuario->setEmail( $linha ['email'] );
            $usuario->setLogin( $linha ['login'] );
            $usuario->setSenha( $linha ['senha'] );
            $usuario->setNivel( $linha ['nivel'] );
            $lista [] = $usuario;
        }
        return $lista;
    }
    
    public function pesquisaPorId(Usuario $usuario) {
        $lista = array();
        $id = $usuario->getId();
        $sql = "SELECT * FROM usuario WHERE id like '%$id%'";
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ( $result as $linha ) {
            $usuario->setId( $linha ['id'] );
            $usuario->setNome( $linha ['nome'] );
            $usuario->setEmail( $linha ['email'] );
            $usuario->setLogin( $linha ['login'] );
            $usuario->setSenha( $linha ['senha'] );
            $usuario->setNivel( $linha ['nivel'] );
            $lista [] = $usuario;
        }
        return $lista;
    }
    
    public function pesquisaPorNome(Usuario $usuario) {
        $lista = array();
        $nome = $usuario->getNome();
        $sql = "SELECT * FROM usuario WHERE nome like '%$nome%'";
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ( $result as $linha ) {
            $usuario->setId( $linha ['id'] );
            $usuario->setNome( $linha ['nome'] );
            $usuario->setEmail( $linha ['email'] );
            $usuario->setLogin( $linha ['login'] );
            $usuario->setSenha( $linha ['senha'] );
            $usuario->setNivel( $linha ['nivel'] );
            $lista [] = $usuario;
        }
        return $lista;
    }
    
    public function pesquisaPorEmail(Usuario $usuario) {
        $lista = array();
        $email = $usuario->getEmail();
        $sql = "SELECT * FROM usuario WHERE email like '%$email%'";
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ( $result as $linha ) {
            $usuario->setId( $linha ['id'] );
            $usuario->setNome( $linha ['nome'] );
            $usuario->setEmail( $linha ['email'] );
            $usuario->setLogin( $linha ['login'] );
            $usuario->setSenha( $linha ['senha'] );
            $usuario->setNivel( $linha ['nivel'] );
            $lista [] = $usuario;
        }
        return $lista;
    }
    
    public function pesquisaPorLogin(Usuario $usuario) {
        $lista = array();
        $login = $usuario->getLogin();
        $sql = "SELECT * FROM usuario WHERE login like '%$login%'";
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ( $result as $linha ) {
            $usuario->setId( $linha ['id'] );
            $usuario->setNome( $linha ['nome'] );
            $usuario->setEmail( $linha ['email'] );
            $usuario->setLogin( $linha ['login'] );
            $usuario->setSenha( $linha ['senha'] );
            $usuario->setNivel( $linha ['nivel'] );
            $lista [] = $usuario;
        }
        return $lista;
    }
    
    public function pesquisaPorSenha(Usuario $usuario) {
        $lista = array();
        $senha = $usuario->getSenha();
        $sql = "SELECT * FROM usuario WHERE senha like '%$senha%'";
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ( $result as $linha ) {
            $usuario->setId( $linha ['id'] );
            $usuario->setNome( $linha ['nome'] );
            $usuario->setEmail( $linha ['email'] );
            $usuario->setLogin( $linha ['login'] );
            $usuario->setSenha( $linha ['senha'] );
            $usuario->setNivel( $linha ['nivel'] );
            $lista [] = $usuario;
        }
        return $lista;
    }
    
    public function pesquisaPorNivel(Usuario $usuario) {
        $lista = array();
        $nivel = $usuario->getNivel();
        $sql = "SELECT * FROM usuario WHERE nivel like '%$nivel%'";
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ( $result as $linha ) {
            $usuario->setId( $linha ['id'] );
            $usuario->setNome( $linha ['nome'] );
            $usuario->setEmail( $linha ['email'] );
            $usuario->setLogin( $linha ['login'] );
            $usuario->setSenha( $linha ['senha'] );
            $usuario->setNivel( $linha ['nivel'] );
            $lista [] = $usuario;
        }
        return $lista;
    }
    
    public function autentica(Usuario $usuario){
        $login = $usuario->getLogin();
        $senha = $usuario->getSenha() ;
        $sql = "SELECT * FROM usuario WHERE login  ='$login' AND senha = '$senha' LIMIT 1";
        
        foreach ( $this->getConexao ()->query ( $sql ) as $linha ) {
            $usuario->setLogin ( $linha ['login'] );
            $usuario->setId( $linha ['id'] );
            $usuario->setNivel( $linha ['nivel'] );
            return true;
        }
        return false;
        
    }
    
    
    public function atualizarSenha(Usuario $usuario)
    {
        $sql = "UPDATE usuario
                SET
                senha = :senha
                WHERE usuario.id = :id;";
        
        $id = $usuario->getId();
        $senha = md5($usuario->getSenha());
        try {
            
            $stmt = $this->getConexao()->prepare($sql);
            $stmt->bindParam(":senha", $senha, PDO::PARAM_STR);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    
    
}