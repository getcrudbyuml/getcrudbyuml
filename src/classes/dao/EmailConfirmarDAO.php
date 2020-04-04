<?php
                
/**
 * Classe feita para manipulação do objeto EmailConfirmar
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte
 *
 *
 */
class EmailConfirmarDAO extends DAO {
    
    
    public function atualizar(EmailConfirmar $emailConfirmar)
    {
        $id = $emailConfirmar->getId();
        
        
        $sql = "UPDATE emailConfirmar
                SET
                email = :email,
                codigo = :codigo
            
                WHERE emailConfirmar.id = :id;";
			$email = $emailConfirmar->getEmail();
			$codigo = $emailConfirmar->getCodigo();
			
                
        try {
                
            $stmt = $this->getConexao()->prepare($sql);
			$stmt->bindParam("id", $id, PDO::PARAM_STR);
			$stmt->bindParam("email", $email, PDO::PARAM_STR);
			$stmt->bindParam("codigo", $codigo, PDO::PARAM_STR);
			
                
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
                
    }
                
	public function inserir(EmailConfirmar $emailConfirmar){
	    
		$sql = "INSERT INTO email_confirmar(email)
				VALUES(:email)";
		$email = $emailConfirmar->getEmail();
			
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("email", $email, PDO::PARAM_STR);
			
			return $stmt->execute();
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	public function atualizarCodigo(EmailConfirmar $emailConfirmar)
	{
	    $codigo = $emailConfirmar->getCodigo();
	    $id = $emailConfirmar->getId();
	    
	    $sql = "UPDATE email_confirmar
                SET
                codigo = '$codigo'
                WHERE email_confirmar.id = $id;";
	    
	    return $this->getConexao()->exec($sql);

	    
	}
	public function excluir(EmailConfirmar $emailConfirmar){
		$id = $emailConfirmar->getId();
		$sql = "DELETE FROM email_confirmar WHERE id = :id";
		    
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
		$sql = "        SELECT
        email_confirmar.id, 
        email_confirmar.email, 
        email_confirmar.codigo, 
        email_confirmar.confirmado
        FROM email_confirmar
                 LIMIT 1000";
		$result = $this->getConexao ()->query ( $sql );
                
		foreach ( $result as $linha ) {
                
			$emailConfirmar = new EmailConfirmar();
        
			$email_confirmar->setId( $linha ['id'] );
			$email_confirmar->setEmail( $linha ['email'] );
			$email_confirmar->setCodigo( $linha ['codigo'] );
			$email_confirmar->setConfirmado( $linha ['confirmado'] );
			$lista [] = $emailConfirmar;
		}
		return $lista;
	}
                    
    public function pesquisaPorId(EmailConfirmar $emailConfirmar) {
        $lista = array();
	    $id = $emailConfirmar->getId();
	    $sql = "SELECT 
                emailconfirmar.id, 
                emailconfirmar.email, 
                emailconfirmar.codigo, 
                emailconfirmar.confirmado
                FROM emailConfirmar
                WHERE emailconfirmar.id = $id";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
            $emailConfirmar = new EmailConfirmar();
	        $emailConfirmar->setId( $linha ['id'] );
	        $emailConfirmar->setEmail( $linha ['email'] );
	        $emailConfirmar->setCodigo( $linha ['codigo'] );
	        $emailConfirmar->setConfirmado( $linha ['confirmado'] );
			$lista [] = $emailConfirmar;
		}
		return $lista;
    }
                    
    public function pesquisaPorEmail(EmailConfirmar $emailConfirmar) {
        $lista = array();
	    $email = $emailConfirmar->getEmail();
	    $sql = "SELECT 
                emailconfirmar.id, 
                emailconfirmar.email, 
                emailconfirmar.codigo, 
                emailconfirmar.confirmado
                FROM emailConfirmar
                WHERE emailconfirmar.email like '%$email%'";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
            $emailConfirmar = new EmailConfirmar();
	        $emailConfirmar->setId( $linha ['id'] );
	        $emailConfirmar->setEmail( $linha ['email'] );
	        $emailConfirmar->setCodigo( $linha ['codigo'] );
	        $emailConfirmar->setConfirmado( $linha ['confirmado'] );
			$lista [] = $emailConfirmar;
		}
		return $lista;
    }
                    
    public function pesquisaPorCodigo(EmailConfirmar $emailConfirmar) {
	    $codigo = $emailConfirmar->getCodigo();
	    $sql = "SELECT 
                email_confirmar.id, 
                email_confirmar.email, 
                email_confirmar.codigo
                FROM email_confirmar
                WHERE email_confirmar.codigo like '$codigo'";
	    
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
	        $emailConfirmar->setId( $linha ['id'] );
	        $emailConfirmar->setEmail( $linha ['email'] );
	        $emailConfirmar->setCodigo( $linha ['codigo'] );
			return true;
		}
		return false;
    }
                    
    public function pesquisaPorConfirmado(EmailConfirmar $emailConfirmar) {
        $lista = array();
	    $confirmado = $emailConfirmar->getConfirmado();
	    $sql = "SELECT 
                emailconfirmar.id, 
                emailconfirmar.email, 
                emailconfirmar.codigo, 
                emailconfirmar.confirmado
                FROM emailConfirmar
                WHERE emailconfirmar.confirmado = $confirmado";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
            $emailConfirmar = new EmailConfirmar();
	        $emailConfirmar->setId( $linha ['id'] );
	        $emailConfirmar->setEmail( $linha ['email'] );
	        $emailConfirmar->setCodigo( $linha ['codigo'] );
	        $emailConfirmar->setConfirmado( $linha ['confirmado'] );
			$lista [] = $emailConfirmar;
		}
		return $lista;
    }
                    
    public function preenchePorId(EmailConfirmar $emailConfirmar) {

	    $id = $emailConfirmar->getId();
	    $sql = "SELECT 
                emailconfirmar.id, 
                emailconfirmar.email, 
                emailconfirmar.codigo, 
                emailconfirmar.confirmado
                FROM emailConfirmar
                WHERE emailconfirmar.id = $id";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
	        $emailConfirmar->setId( $linha ['id'] );
	        $emailConfirmar->setEmail( $linha ['email'] );
	        $emailConfirmar->setCodigo( $linha ['codigo'] );
	        $emailConfirmar->setConfirmado( $linha ['confirmado'] );

		}
		return $emailConfirmar;
    }
                    
    public function preenchePorEmail(EmailConfirmar $emailConfirmar) {

	    $email = $emailConfirmar->getEmail();
	    $sql = "SELECT 
                emailconfirmar.id, 
                emailconfirmar.email, 
                emailconfirmar.codigo, 
                emailconfirmar.confirmado
                FROM emailConfirmar
                WHERE emailconfirmar.email like '%$email%'";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
	        $emailConfirmar->setId( $linha ['id'] );
	        $emailConfirmar->setEmail( $linha ['email'] );
	        $emailConfirmar->setCodigo( $linha ['codigo'] );
	        $emailConfirmar->setConfirmado( $linha ['confirmado'] );

		}
		return $emailConfirmar;
    }
                    
    public function preenchePorCodigo(EmailConfirmar $emailConfirmar) {

	    $codigo = $emailConfirmar->getCodigo();
	    $sql = "SELECT 
                emailconfirmar.id, 
                emailconfirmar.email, 
                emailconfirmar.codigo, 
                emailconfirmar.confirmado
                FROM emailConfirmar
                WHERE emailconfirmar.codigo like '%$codigo%'";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
	        $emailConfirmar->setId( $linha ['id'] );
	        $emailConfirmar->setEmail( $linha ['email'] );
	        $emailConfirmar->setCodigo( $linha ['codigo'] );
	        $emailConfirmar->setConfirmado( $linha ['confirmado'] );

		}
		return $emailConfirmar;
    }
                    
    public function preenchePorConfirmado(EmailConfirmar $emailConfirmar) {

	    $confirmado = $emailConfirmar->getConfirmado();
	    $sql = "SELECT 
                emailconfirmar.id, 
                emailconfirmar.email, 
                emailconfirmar.codigo, 
                emailconfirmar.confirmado
                FROM emailConfirmar
                WHERE emailconfirmar.confirmado = $confirmado";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
	        $emailConfirmar->setId( $linha ['id'] );
	        $emailConfirmar->setEmail( $linha ['email'] );
	        $emailConfirmar->setCodigo( $linha ['codigo'] );
	        $emailConfirmar->setConfirmado( $linha ['confirmado'] );

		}
		return $emailConfirmar;
    }
                
                
}