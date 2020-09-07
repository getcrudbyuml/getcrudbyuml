<?php
		
/**
 * Classe feita para manipulação do objeto Usuario
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte
 *
 *
 */
class UsuarioDAO extends DAO {
	
	
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
	public function alterar(){
		//Aqui vc escreve o codigo pra alterar usuario
	
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
	
	public function pesquisaPorID(Usuario $usuario) {
	    $id = $usuario->getId();
	    $sql = "SELECT * FROM usuario WHERE id = $id";
	    $result = $this->getConexao ()->query ( $sql );
	    
	    foreach ( $result as $linha ) {
	        $usuario->setId( $linha ['id'] );
	        $usuario->setNome( $linha ['nome'] );
	        $usuario->setEmail( $linha ['email'] );
	        $usuario->setLogin( $linha ['login'] );
	        $usuario->setSenha( $linha ['senha'] );
	        $usuario->setNivel( $linha ['nivel'] );
            return $usuario;
	    }
	    return null;
	}		
	public function pesquisaPorEmail(Usuario $usuario) {
	    $email = $usuario->getEmail();
	    $sql = "SELECT * FROM usuario WHERE email like '$email'";
	    $result = $this->getConexao ()->query ( $sql );
	    
	    foreach ( $result as $linha ) {
	        $usuario->setId( $linha ['id'] );
	        $usuario->setNome( $linha ['nome'] );
	        $usuario->setEmail( $linha ['email'] );
	        $usuario->setLogin( $linha ['login'] );
	        $usuario->setSenha( $linha ['senha'] );
	        $usuario->setNivel( $linha ['nivel'] );
	        return $usuario;
	    }
	    return null;
	}		
	public function pesquisaPorLogin(Usuario $usuario) {
	    $login = $usuario->getLogin();
	    $sql = "SELECT * FROM usuario WHERE login like '$login'";
	    $result = $this->getConexao ()->query ( $sql );
	    
	    foreach ( $result as $linha ) {
	        $usuario->setId( $linha ['id'] );
	        $usuario->setNome( $linha ['nome'] );
	        $usuario->setEmail( $linha ['email'] );
	        $usuario->setLogin( $linha ['login'] );
	        $usuario->setSenha( $linha ['senha'] );
	        $usuario->setNivel( $linha ['nivel'] );
	        return $usuario;
	    }
	    return null;
	}		

	
	public function atualizar(Usuario $usuario)
	{
	    $sql = "UPDATE usuario
                SET
                nome = :nome,
                email = :email
                WHERE usuario.id = :id;";
	    
	    $id = $usuario->getId();
	    $nome = $usuario->getNome();
	    $email = $usuario->getEmail();
	    
	    try {
	        
	        $stmt = $this->getConexao()->prepare($sql);
	        $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
	        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
	        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
	        return $stmt->execute();
	    } catch (PDOException $e) {
	        echo $e->getMessage();
	    }
	}
	public function atualizarNivelPorId(Usuario $usuario)
	{
	    $id = $usuario->getId();
	    $nivel = $usuario->getNivel();
	    $sql = "UPDATE usuario
                SET
                nivel = :nivel
                WHERE usuario.id = :id;";
	    
	    try {
	        
	        $stmt = $this->getConexao()->prepare($sql);
	        $stmt->bindParam(":nivel", $nivel, PDO::PARAM_INT);
	        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
	        return $stmt->execute();
	    } catch (PDOException $e) {
	        echo $e->getMessage();
	    }
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