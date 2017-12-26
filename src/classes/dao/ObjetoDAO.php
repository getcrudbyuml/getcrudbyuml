<?php

/**
 * Classe feita para manipulaÃ§Ã£o do objeto Objeto
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson UchÃ´a Ponte
 *
 *
 */
class ObjetoDAO extends DAO {

	
	/**
	 * Este serve para inserir objeto em um software.  
	 * @param Objeto $objeto
	 * @param Software $software
	 * @return bool
	 */
	public function inserir(Objeto $objeto, Software $software){
		
	    $idSoftware = $software->getId();
	    $nome = $objeto->getNome();
	    $sql = "INSERT INTO objeto(nome_objeto, id_software)
				VALUES(:nome, :idSoftware)";
	    try {
	        $db = $this->getConexao();
	        $stmt = $db->prepare($sql);
	        $stmt->bindParam("nome", $nome, PDO::PARAM_STR);
	        $stmt->bindParam("idSoftware", $idSoftware, PDO::PARAM_STR);
	        return $stmt->execute();
	    } catch(PDOException $e) {
	        echo '{"error":{"text":'. $e->getMessage() .'}}';
	    }

	}

	public function excluir(Objeto $objeto){
	    $id = $objeto->getId();
	    $sql = "DELETE FROM objeto WHERE id_objeto = :id";
	    
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
	        $objeto->setId( $linha ['id_objeto'] );
	        $objeto->setNome( $linha ['nome_objeto'] );

	        $lista [] = $objeto;
	    }
	    return $lista;
	}
	
}


?>