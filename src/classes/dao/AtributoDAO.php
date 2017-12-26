<?php

/**
 * Classe feita para manipulação do objeto Atributo
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte
 */

class AtributoDAO extends DAO {

	
	
    public function excluir(Atributo $atributo){
        $id = $atributo->getId();
        $sql = "DELETE FROM atributo WHERE id_atributo = :id";
        
        try {
            $db = $this->getConexao();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("id", $id, PDO::PARAM_INT);
            return $stmt->execute();
            
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }

	public function inserir(Atributo $atributo, Objeto $objeto){
		
	    
	    $sql = "INSERT INTO atributo(nome_atributo, tipo_atributo, indice_atributo, id_objeto)
				VALUES(:nome, :tipo, :indice, :idobjeto)";
	    $nome = $atributo->getNome();
	    $tipo = $atributo->getTipo();
	    $indice = $atributo->getIndice();
	    $idobjeto = $objeto->getId();
	    
	    try {
	        $db = $this->getConexao();
	        $stmt = $db->prepare($sql);
	        $stmt->bindParam("nome", $nome, PDO::PARAM_STR);
	        $stmt->bindParam("tipo", $tipo, PDO::PARAM_STR);
	        $stmt->bindParam("indice", $indice, PDO::PARAM_STR);
	        $stmt->bindParam("idobjeto", $idobjeto, PDO::PARAM_INT);
	        return $stmt->execute();
	    } catch(PDOException $e) {
	        echo '{"error":{"text":'. $e->getMessage() .'}}';
	    }
	}
	
	public function retornaLista(Objeto $objeto) {
		$id = $objeto->getId();
	    $lista = array ();
	    $sql = "SELECT * FROM atributo WHERE id_objeto = $id LIMIT 1000";
	    $result = $this->getConexao ()->query ( $sql );
	    
	    foreach ( $result as $linha ) {
	        
	        $atributo = new Atributo();
	        
	        $atributo->setId( $linha ['id'] );
	        $atributo->setNome( $linha ['nome'] );
	        $atributo->setTipo( $linha ['tipo'] );
	        $atributo->setRelacionamento( $linha ['relacionamento'] );
	        $atributo->setIdobjeto( $linha ['idobjeto'] );
	        $lista [] = $atributo;
	    }
	    return $lista;
	}			
	
}
?>