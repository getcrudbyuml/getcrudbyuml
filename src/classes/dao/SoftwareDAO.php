<?php
		
/**
 * Classe feita para manipulação do objeto Software
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte
 *
 *
 */
class SoftwareDAO extends DAO {
	

    public function atualizar(Software $software)
    {

        $id = $software->getId();
        $sql = "UPDATE software 
                SET
                nome = :nome
                WHERE software.id = :id;";
			$nome = $software->getNome();

        try {
            
            $stmt = $this->getConexao()->prepare($sql);
			$stmt->bindParam("id", $id, PDO::PARAM_STR);
			$stmt->bindParam("nome", $nome, PDO::PARAM_STR);
           
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();   
        }

    }
	
	public function inserir(Software $software){
		
		$sql = "INSERT INTO software(nome)
				VALUES(:nome)";
			$nome = $software->getNome();
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);		
			$stmt->bindParam("nome", $nome, PDO::PARAM_STR);
			return $stmt->execute();
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	public function excluir(Software $software){
		$id = $software->getId();
		$sql = "DELETE FROM software WHERE id = :id";
		
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
		$sql = "SELECT * FROM software ORDER BY id DESC LIMIT 1000";
		$result = $this->getConexao ()->query ( $sql );
	
		foreach ( $result as $linha ) {
				
			$software = new Software();
        
			$software->setId( $linha ['id'] );
			$software->setNome( $linha ['nome'] );
			$lista [] = $software;
		}
		return $lista;
	}

    public function pesquisaPorId(Software $software) {
	    $idSoftware = $software->getId();
	    $sql = "SELECT * FROM software WHERE id = $idSoftware";
	    $result = $this->getConexao ()->query ( $sql );
	    
	    
	    foreach ( $result as $linha ) {
	        $software->setNome( $linha ['nome'] );
		}
		
		return $software;
	}

    public function pesquisaPorNome(Software $software) {
        $lista = array();
	    $nome = $software->getNome();
	    $sql = "SELECT * FROM software WHERE nome like '%$nome%'";
	    $result = $this->getConexao ()->query ( $sql );
	        
	    foreach ( $result as $linha ) {
	        $software->setId( $linha ['id'] );
	        $software->setNome( $linha ['nome'] );
			$lista [] = $software;
		}
		return $lista;
	}
		
				
}