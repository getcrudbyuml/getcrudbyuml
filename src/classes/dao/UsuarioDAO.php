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
			$stmt->bindParam(":id", $id, PDO::PARAM_INT);
			$stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
			$stmt->bindParam(":email", $email, PDO::PARAM_STR);
			$stmt->bindParam(":login", $login, PDO::PARAM_STR);
			$stmt->bindParam(":senha", $senha, PDO::PARAM_STR);
			$stmt->bindParam(":nivel", $nivel, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
            
    }
            
            

    public function inserir(Usuario $usuario){
        $sql = "INSERT INTO usuario(nome, email, login, senha, nivel) VALUES (:nome, :email, :login, :senha, :nivel);";
		$nome = $usuario->getNome();
		$email = $usuario->getEmail();
		$login = $usuario->getLogin();
		$senha = $usuario->getSenha();
		$nivel = $usuario->getNivel();
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
			$stmt->bindParam(":email", $email, PDO::PARAM_STR);
			$stmt->bindParam(":login", $login, PDO::PARAM_STR);
			$stmt->bindParam(":senha", $senha, PDO::PARAM_STR);
			$stmt->bindParam(":nivel", $nivel, PDO::PARAM_INT);
			return $stmt->execute();
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
            
    }
    public function inserirComPK(Usuario $usuario){
        $sql = "INSERT INTO usuario(id, nome, email, login, senha, nivel) VALUES (:id, :nome, :email, :login, :senha, :nivel);";
		$id = $usuario->getId();
		$nome = $usuario->getNome();
		$email = $usuario->getEmail();
		$login = $usuario->getLogin();
		$senha = $usuario->getSenha();
		$nivel = $usuario->getNivel();
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $id, PDO::PARAM_INT);
			$stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
			$stmt->bindParam(":email", $email, PDO::PARAM_STR);
			$stmt->bindParam(":login", $login, PDO::PARAM_STR);
			$stmt->bindParam(":senha", $senha, PDO::PARAM_STR);
			$stmt->bindParam(":nivel", $nivel, PDO::PARAM_INT);
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
			$stmt->bindParam(":id", $id, PDO::PARAM_INT);
			return $stmt->execute();
			    
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}


	public function retornaLista() {
		$lista = array ();
		$sql = "
		SELECT
        usuario.id, 
        usuario.nome, 
        usuario.email, 
        usuario.login, 
        usuario.senha, 
        usuario.nivel
		FROM usuario
                 LIMIT 1000";

        try {
            $stmt = $this->conexao->prepare($sql);
            
		    if(!$stmt){   
                echo "<br>Mensagem de erro retornada: ".$this->conexao->errorInfo()[2]."<br>";
		        return $lista;
		    }
            $stmt->execute();
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach ( $result as $linha ) 
            {
		        $usuario = new Usuario();
                $usuario->setId( $linha ['id'] );
                $usuario->setNome( $linha ['nome'] );
                $usuario->setEmail( $linha ['email'] );
                $usuario->setLogin( $linha ['login'] );
                $usuario->setSenha( $linha ['senha'] );
                $usuario->setNivel( $linha ['nivel'] );
                $lista [] = $usuario;

	
		    }
		} catch(PDOException $e) {
		    echo $e->getMessage();
 		}
        return $lista;	
    }
        
                
    public function pesquisaPorId(Usuario $usuario) {
        $lista = array();
	    $id = $usuario->getId();
                
        $sql = "
		SELECT
        usuario.id, 
        usuario.nome, 
        usuario.email, 
        usuario.login, 
        usuario.senha, 
        usuario.nivel
		FROM usuario
            WHERE usuario.id = :id";
                
        try {
                
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $linha ){
		        $usuario = new Usuario();
                $usuario->setId( $linha ['id'] );
                $usuario->setNome( $linha ['nome'] );
                $usuario->setEmail( $linha ['email'] );
                $usuario->setLogin( $linha ['login'] );
                $usuario->setSenha( $linha ['senha'] );
                $usuario->setNivel( $linha ['nivel'] );
                $lista [] = $usuario;

	
		    }
    			    
        } catch(PDOException $e) {
            echo $e->getMessage();
    			    
        }
		return $lista;
    }
                
    public function pesquisaPorNome(Usuario $usuario) {
        $lista = array();
	    $nome = $usuario->getNome();
                
        $sql = "
		SELECT
        usuario.id, 
        usuario.nome, 
        usuario.email, 
        usuario.login, 
        usuario.senha, 
        usuario.nivel
		FROM usuario
            WHERE usuario.nome like :nome";
                
        try {
                
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $linha ){
		        $usuario = new Usuario();
                $usuario->setId( $linha ['id'] );
                $usuario->setNome( $linha ['nome'] );
                $usuario->setEmail( $linha ['email'] );
                $usuario->setLogin( $linha ['login'] );
                $usuario->setSenha( $linha ['senha'] );
                $usuario->setNivel( $linha ['nivel'] );
                $lista [] = $usuario;

	
		    }
    			    
        } catch(PDOException $e) {
            echo $e->getMessage();
    			    
        }
		return $lista;
    }
                
    public function pesquisaPorEmail(Usuario $usuario) {
        $lista = array();
	    $email = $usuario->getEmail();
                
        $sql = "
		SELECT
        usuario.id, 
        usuario.nome, 
        usuario.email, 
        usuario.login, 
        usuario.senha, 
        usuario.nivel
		FROM usuario
            WHERE usuario.email like :email";
                
        try {
                
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $linha ){
		        $usuario = new Usuario();
                $usuario->setId( $linha ['id'] );
                $usuario->setNome( $linha ['nome'] );
                $usuario->setEmail( $linha ['email'] );
                $usuario->setLogin( $linha ['login'] );
                $usuario->setSenha( $linha ['senha'] );
                $usuario->setNivel( $linha ['nivel'] );
                $lista [] = $usuario;

	
		    }
    			    
        } catch(PDOException $e) {
            echo $e->getMessage();
    			    
        }
		return $lista;
    }
                
    public function pesquisaPorLogin(Usuario $usuario) {
        $lista = array();
	    $login = $usuario->getLogin();
                
        $sql = "
		SELECT
        usuario.id, 
        usuario.nome, 
        usuario.email, 
        usuario.login, 
        usuario.senha, 
        usuario.nivel
		FROM usuario
            WHERE usuario.login like :login";
                
        try {
                
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(":login", $login, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $linha ){
		        $usuario = new Usuario();
                $usuario->setId( $linha ['id'] );
                $usuario->setNome( $linha ['nome'] );
                $usuario->setEmail( $linha ['email'] );
                $usuario->setLogin( $linha ['login'] );
                $usuario->setSenha( $linha ['senha'] );
                $usuario->setNivel( $linha ['nivel'] );
                $lista [] = $usuario;

	
		    }
    			    
        } catch(PDOException $e) {
            echo $e->getMessage();
    			    
        }
		return $lista;
    }
                
    public function pesquisaPorSenha(Usuario $usuario) {
        $lista = array();
	    $senha = $usuario->getSenha();
                
        $sql = "
		SELECT
        usuario.id, 
        usuario.nome, 
        usuario.email, 
        usuario.login, 
        usuario.senha, 
        usuario.nivel
		FROM usuario
            WHERE usuario.senha like :senha";
                
        try {
                
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(":senha", $senha, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $linha ){
		        $usuario = new Usuario();
                $usuario->setId( $linha ['id'] );
                $usuario->setNome( $linha ['nome'] );
                $usuario->setEmail( $linha ['email'] );
                $usuario->setLogin( $linha ['login'] );
                $usuario->setSenha( $linha ['senha'] );
                $usuario->setNivel( $linha ['nivel'] );
                $lista [] = $usuario;

	
		    }
    			    
        } catch(PDOException $e) {
            echo $e->getMessage();
    			    
        }
		return $lista;
    }
                
    public function pesquisaPorNivel(Usuario $usuario) {
        $lista = array();
	    $nivel = $usuario->getNivel();
                
        $sql = "
		SELECT
        usuario.id, 
        usuario.nome, 
        usuario.email, 
        usuario.login, 
        usuario.senha, 
        usuario.nivel
		FROM usuario
            WHERE usuario.nivel = :nivel";
                
        try {
                
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(":nivel", $nivel, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $linha ){
		        $usuario = new Usuario();
                $usuario->setId( $linha ['id'] );
                $usuario->setNome( $linha ['nome'] );
                $usuario->setEmail( $linha ['email'] );
                $usuario->setLogin( $linha ['login'] );
                $usuario->setSenha( $linha ['senha'] );
                $usuario->setNivel( $linha ['nivel'] );
                $lista [] = $usuario;

	
		    }
    			    
        } catch(PDOException $e) {
            echo $e->getMessage();
    			    
        }
		return $lista;
    }
                
    public function preenchePorId(Usuario $usuario) {
        
	    $id = $usuario->getId();
	    $sql = "
		SELECT
        usuario.id, 
        usuario.nome, 
        usuario.email, 
        usuario.login, 
        usuario.senha, 
        usuario.nivel
		FROM usuario
                WHERE usuario.id = :id
                 LIMIT 1000";
                
        try {
            $stmt = $this->conexao->prepare($sql);
                
		    if(!$stmt){
                echo "<br>Mensagem de erro retornada: ".$this->conexao->errorInfo()[2]."<br>";
		    }
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach ( $result as $linha )
            {
                $usuario->setId( $linha ['id'] );
                $usuario->setNome( $linha ['nome'] );
                $usuario->setEmail( $linha ['email'] );
                $usuario->setLogin( $linha ['login'] );
                $usuario->setSenha( $linha ['senha'] );
                $usuario->setNivel( $linha ['nivel'] );
                
                
		    }
		} catch(PDOException $e) {
		    echo $e->getMessage();
 		}
		return $usuario;
    }
                
    public function preenchePorNome(Usuario $usuario) {
        
	    $nome = $usuario->getNome();
	    $sql = "
		SELECT
        usuario.id, 
        usuario.nome, 
        usuario.email, 
        usuario.login, 
        usuario.senha, 
        usuario.nivel
		FROM usuario
                WHERE usuario.nome = :nome
                 LIMIT 1000";
                
        try {
            $stmt = $this->conexao->prepare($sql);
                
		    if(!$stmt){
                echo "<br>Mensagem de erro retornada: ".$this->conexao->errorInfo()[2]."<br>";
		    }
            $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
            $stmt->execute();
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach ( $result as $linha )
            {
                $usuario->setId( $linha ['id'] );
                $usuario->setNome( $linha ['nome'] );
                $usuario->setEmail( $linha ['email'] );
                $usuario->setLogin( $linha ['login'] );
                $usuario->setSenha( $linha ['senha'] );
                $usuario->setNivel( $linha ['nivel'] );
                
                
		    }
		} catch(PDOException $e) {
		    echo $e->getMessage();
 		}
		return $usuario;
    }
                
    public function preenchePorEmail(Usuario $usuario) {
        
	    $email = $usuario->getEmail();
	    $sql = "
		SELECT
        usuario.id, 
        usuario.nome, 
        usuario.email, 
        usuario.login, 
        usuario.senha, 
        usuario.nivel
		FROM usuario
                WHERE usuario.email = :email
                 LIMIT 1000";
                
        try {
            $stmt = $this->conexao->prepare($sql);
                
		    if(!$stmt){
                echo "<br>Mensagem de erro retornada: ".$this->conexao->errorInfo()[2]."<br>";
		    }
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach ( $result as $linha )
            {
                $usuario->setId( $linha ['id'] );
                $usuario->setNome( $linha ['nome'] );
                $usuario->setEmail( $linha ['email'] );
                $usuario->setLogin( $linha ['login'] );
                $usuario->setSenha( $linha ['senha'] );
                $usuario->setNivel( $linha ['nivel'] );
                
                
		    }
		} catch(PDOException $e) {
		    echo $e->getMessage();
 		}
		return $usuario;
    }
                
    public function preenchePorLogin(Usuario $usuario) {
        
	    $login = $usuario->getLogin();
	    $sql = "
		SELECT
        usuario.id, 
        usuario.nome, 
        usuario.email, 
        usuario.login, 
        usuario.senha, 
        usuario.nivel
		FROM usuario
                WHERE usuario.login = :login
                 LIMIT 1000";
                
        try {
            $stmt = $this->conexao->prepare($sql);
                
		    if(!$stmt){
                echo "<br>Mensagem de erro retornada: ".$this->conexao->errorInfo()[2]."<br>";
		    }
            $stmt->bindParam(":login", $login, PDO::PARAM_STR);
            $stmt->execute();
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach ( $result as $linha )
            {
                $usuario->setId( $linha ['id'] );
                $usuario->setNome( $linha ['nome'] );
                $usuario->setEmail( $linha ['email'] );
                $usuario->setLogin( $linha ['login'] );
                $usuario->setSenha( $linha ['senha'] );
                $usuario->setNivel( $linha ['nivel'] );
                
                
		    }
		} catch(PDOException $e) {
		    echo $e->getMessage();
 		}
		return $usuario;
    }
                
    public function preenchePorSenha(Usuario $usuario) {
        
	    $senha = $usuario->getSenha();
	    $sql = "
		SELECT
        usuario.id, 
        usuario.nome, 
        usuario.email, 
        usuario.login, 
        usuario.senha, 
        usuario.nivel
		FROM usuario
                WHERE usuario.senha = :senha
                 LIMIT 1000";
                
        try {
            $stmt = $this->conexao->prepare($sql);
                
		    if(!$stmt){
                echo "<br>Mensagem de erro retornada: ".$this->conexao->errorInfo()[2]."<br>";
		    }
            $stmt->bindParam(":senha", $senha, PDO::PARAM_STR);
            $stmt->execute();
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach ( $result as $linha )
            {
                $usuario->setId( $linha ['id'] );
                $usuario->setNome( $linha ['nome'] );
                $usuario->setEmail( $linha ['email'] );
                $usuario->setLogin( $linha ['login'] );
                $usuario->setSenha( $linha ['senha'] );
                $usuario->setNivel( $linha ['nivel'] );
                
                
		    }
		} catch(PDOException $e) {
		    echo $e->getMessage();
 		}
		return $usuario;
    }
                
    public function preenchePorNivel(Usuario $usuario) {
        
	    $nivel = $usuario->getNivel();
	    $sql = "
		SELECT
        usuario.id, 
        usuario.nome, 
        usuario.email, 
        usuario.login, 
        usuario.senha, 
        usuario.nivel
		FROM usuario
                WHERE usuario.nivel = :nivel
                 LIMIT 1000";
                
        try {
            $stmt = $this->conexao->prepare($sql);
                
		    if(!$stmt){
                echo "<br>Mensagem de erro retornada: ".$this->conexao->errorInfo()[2]."<br>";
		    }
            $stmt->bindParam(":nivel", $nivel, PDO::PARAM_INT);
            $stmt->execute();
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach ( $result as $linha )
            {
                $usuario->setId( $linha ['id'] );
                $usuario->setNome( $linha ['nome'] );
                $usuario->setEmail( $linha ['email'] );
                $usuario->setLogin( $linha ['login'] );
                $usuario->setSenha( $linha ['senha'] );
                $usuario->setNivel( $linha ['nivel'] );
                
                
		    }
		} catch(PDOException $e) {
		    echo $e->getMessage();
 		}
		return $usuario;
    }
    
    public function buscarSoftwares(Usuario $usuario)
    {
        $id = $usuario->getId();
        $sql = "SELECT * FROM
                usuario_software
                INNER JOIN software
                ON  usuario_software.id_software = software.id
                 WHERE usuario_software.id_usuario = $id ORDER BY software.id DESC";
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ($result as $linha) {
            $software = new Software();
            $software->setId( $linha ['id'] );
            $software->setNome( $linha ['nome'] );
            $usuario->addSoftware($software);
            
        }
        return $usuario;
    }
    public function inserirSoftware(Usuario $usuario, Software $software)
    {
        $idUsuario =  $usuario->getId();
        $idSoftware = $software->getId();
        $sql = "INSERT INTO usuario_software(
                    id_usuario,
                    id_software)
				VALUES(
                :idUsuario,
                :idSoftware)";
        
        
        try {
            $db = $this->getConexao();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("idUsuario", $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam("idSoftware", $idSoftware, PDO::PARAM_INT);
            
            
            return $stmt->execute();
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }
    public function verificarPosse(Usuario $usuario, Software $software)
    {
        $idUsuario = $usuario->getId();
        $idSoftware = $software->getId();
        $sql = "SELECT * FROM
                usuario_software
                 WHERE
                (usuario_software.id_usuario = $idUsuario
                    AND
                usuario_software.id_software = $idSoftware);";
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ($result as $linha) {
            return true;
        }
        return false;
    }
    public function verificarPosseObjeto(Usuario $usuario, Objeto $objeto)
    {
        $idUsuario = $usuario->getId();
        $idObjeto = $objeto->getId();
        $sql = "SELECT * FROM
                objeto
                INNER JOIN
                usuario_software
                ON objeto.id_software_objetos = usuario_software.id_software
                 WHERE
                (usuario_software.id_usuario = $idUsuario
                    AND
                objeto.id = $idObjeto);";
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ($result as $linha) {
            return true;
        }
        return false;
    }
    public function verificarPosseAtributo(Usuario $usuario, Atributo $atributo)
    {
        $idUsuario = $usuario->getId();
        $idAtributo = $atributo->getId();
        $sql = "SELECT * FROM
                atributo
                INNER JOIN objeto
                ON atributo.id_objeto_atributos = objeto.id
                INNER JOIN
                usuario_software
                ON objeto.id_software_objetos = usuario_software.id_software
                 WHERE
                (usuario_software.id_usuario = $idUsuario
                    AND
                atributo.id = $idAtributo);";
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ($result as $linha) {
            return true;
        }
        return false;
    }
}

