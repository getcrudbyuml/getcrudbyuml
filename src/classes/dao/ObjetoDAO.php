<?php
		
/**
 * Classe feita para manipulação do objeto Objeto
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte
 *
 *
 */
class ObjetoDAO extends DAO {
	

    public function atualizar(Objeto $objeto)
    {

        $nome = $objeto->getNome();
        
        $id = $objeto->getId();
        $sql = "UPDATE objeto 
                SET
                nome = :nome
                WHERE objeto.id = :id;";
			

        try {
            
            $stmt = $this->getConexao()->prepare($sql);
			$stmt->bindParam("id", $id, PDO::PARAM_STR);
			$stmt->bindParam("nome", $nome, PDO::PARAM_STR);
			
           
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();   
        }

    }
	
	public function inserir(Objeto $objeto, Software $software){
		
		$sql = "INSERT INTO objeto(nome, id_software_objetos)
				VALUES(:nome, :idsoftware)";
			$nome = ucfirst($objeto->getNome());
			$idsoftware = $software->getId();
			
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);		
			$stmt->bindParam("nome", $nome, PDO::PARAM_STR);		
			$stmt->bindParam("idsoftware", $idsoftware, PDO::PARAM_STR);
			return $stmt->execute();
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	public function excluir(Objeto $objeto){
		$id = $objeto->getId();
		$sql = "DELETE FROM objeto WHERE id = :id";
		
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
		$sql = "SELECT * FROM objeto LIMIT 1000";
		$result = $this->getConexao ()->query ( $sql );
	
		foreach ( $result as $linha ) {
				
			$objeto = new Objeto();
        
			$objeto->setId( $linha ['id'] );
			$objeto->setNome( $linha ['nome'] );
			$lista [] = $objeto;
		}
		return $lista;
	}

	
	
    public function pesquisaPorId(Objeto $objeto) {
        $lista = array();
	    $id = $objeto->getId();
	    $sql = "SELECT * FROM objeto WHERE id = '$id'";
	    $result = $this->getConexao ()->query ( $sql );
	        
	    foreach ( $result as $linha ) {
	        $objeto->setId( $linha ['id'] );
	        $objeto->setNome( $linha ['nome'] );
			$lista [] = $objeto;
		}
		return $lista;
	}
	

    public function pesquisaPorNome(Objeto $objeto) {
        $lista = array();
	    $nome = $objeto->getNome();
	    $sql = "SELECT * FROM objeto WHERE nome like '%$nome%'";
	    $result = $this->getConexao ()->query ( $sql );
	        
	    foreach ( $result as $linha ) {
	        $objeto->setId( $linha ['id'] );
	        $objeto->setNome( $linha ['nome'] );
			$lista [] = $objeto;
		}
		return $lista;
	}

    public function pesquisaPorIdSoftware(Software $software) {
	    $idsoftware = $software->getId();
	    $sql = "SELECT * FROM objeto WHERE id_software_objetos = $idsoftware ORDER BY id DESC";
	    $result = $this->getConexao ()->query ( $sql );
	        
	    foreach ( $result as $linha ) {
	        $objeto = new Objeto();
	        $objeto->setId( $linha ['id'] );
	        $objeto->setNome( $linha ['nome'] );
			$software->addObjeto($objeto);
		}
		return $software->getObjetos();
	}
	
	public function softwareDoObjeto(Objeto $objeto){
	    $software= new Software();
	    $idObjeto = $objeto->getId();
	    $sql = "SELECT 
                    objeto.id as id_objeto,
                    objeto.id_software_objetos as idsoftware, 
                    software.id as id_software, 
                    software.nome as nome_software 
                 FROM software INNER JOIN objeto 
                ON software.id = objeto.id_software_objetos
                WHERE id_objeto = $idObjeto";
	    $result = $this->getConexao ()->query ( $sql );
	    
	    foreach ( $result as $linha ) {
	        $software->setId( $linha ['id_software'] );
	        $software->setNome( $linha ['nome_software'] );
	    }
	    return $software;
	}
		
				
}