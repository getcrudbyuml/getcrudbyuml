<?php
namespace GetCrudByUML\dao;
use GetCrudByUML\model\Atributo as Atributo;
use GetCrudByUML\model\Objeto as Objeto;
use GetCrudByUML\model\Software as Software;
use PDO;
use PDOException;

/**
 * Classe feita para manipulação do objeto Atributo
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte
 *
 *
 */
class AtributoDAO extends DAO {
	

    public function atualizar(Atributo $atributo)
    {

        $id = $atributo->getId();
        $sql = "UPDATE atributo 
                SET
                nome = :nome, 
                tipo = :tipo, 
                indice = :indice
                WHERE atributo.id = :id;";
			$nome = $atributo->getNome();
			$tipo = $atributo->getTipo();
			$indice = $atributo->getIndice();

        try {
            
            $stmt = $this->getConexao()->prepare($sql);
			$stmt->bindParam("id", $id, PDO::PARAM_STR);
			$stmt->bindParam("nome", $nome, PDO::PARAM_STR);
			$stmt->bindParam("tipo", $tipo, PDO::PARAM_STR);
			$stmt->bindParam("indice", $indice, PDO::PARAM_STR);
           
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();   
        }

    }
	
	public function inserir(Atributo $atributo, Objeto $objeto){
		
		$sql = "INSERT INTO atributo(nome, tipo, indice, id_objeto_atributos)
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
			$stmt->bindParam("idobjeto", $idobjeto, PDO::PARAM_STR);
			return $stmt->execute();
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	public function excluir(Atributo $atributo){
		$id = $atributo->getId();
		$sql = "DELETE FROM atributo WHERE id = :id";
		
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
		$sql = "SELECT * FROM atributo LIMIT 1000";
		$result = $this->getConexao ()->query ( $sql );
	
		foreach ( $result as $linha ) {
				
			$atributo = new Atributo();
        
			$atributo->setId( $linha ['id'] );
			$atributo->setNome( $linha ['nome'] );
			$atributo->setTipo( $linha ['tipo'] );
			$atributo->setIndice( $linha ['indice'] );
			$lista [] = $atributo;
		}
		return $lista;
	}

    public function pesquisaPorId(Atributo $atributo) {
        $lista = array();
	    $id = $atributo->getId();
	    $sql = "SELECT * FROM atributo WHERE id = $id";
	    $result = $this->getConexao ()->query ( $sql );
	        
	    foreach ( $result as $linha ) {
	        $atributo->setId( $linha ['id'] );
	        $atributo->setNome( $linha ['nome'] );
	        $atributo->setTipo( $linha ['tipo'] );
	        $atributo->setIndice( $linha ['indice'] );
			$lista [] = $atributo;
		}
		return $lista;
	}

    

    public function pesquisaPorIdObjeto(Objeto $objeto) {
	    $idobjeto = $objeto->getId();
	    $sql = "SELECT * FROM atributo WHERE id_objeto_atributos = $idobjeto";
	    $result = $this->getConexao ()->query ( $sql );
	        
	    foreach ( $result as $linha ) {
	        $atributo = new Atributo();
	        $atributo->setId( $linha ['id'] );
	        $atributo->setNome( $linha ['nome'] );
	        $atributo->setTipo( $linha ['tipo'] );
	        $atributo->setIndice( $linha ['indice'] );
	        $objeto->addAtributo($atributo);
		}
		return $objeto->getAtributos();
	}
	public function softwareDoAtributo(Atributo $atributo){
	    $software= new Software();
	    $idAtributo = $atributo->getId();
	    $sql = "SELECT
                    software.id as id_software,
                    software.nome as nome_software
                 FROM 
                software 
                INNER JOIN 
                objeto
                ON objeto.id_software_objetos = software.id
                INNER JOIN 
                atributo 
                ON atributo.id_objeto_atributos = objeto.id
                WHERE atributo.id = $idAtributo";
	    $result = $this->getConexao ()->query ( $sql );
	    
	    foreach ( $result as $linha ) {
	        $software->setId( $linha ['id_software'] );
	        $software->setNome( $linha ['nome_software'] );
	    }
	    return $software;
	}
				
}