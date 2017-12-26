<?php


class SoftwareDAO extends DAO {

	/**
	 * Serve para inserir nome, linguagem em uma tabela software, 
	 * retorna o id desta insersao
	 * @param Software $software
	 */
	
    
    public function inserir(Software $software){
        
        $sql = "INSERT INTO software(nome_software)
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
    
    public function retornaLista() {
        $lista = array ();
        $sql = "SELECT * FROM software LIMIT 1000";
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ( $result as $linha ) {
            
            $software = new Software();
            $software->setId( $linha ['id_software'] );
            $software->setNome( $linha ['nome_software'] );
            $lista [] = $software;
        }
        return $lista;
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
    

	public function retornaSoftwareDetalhado(Software $software)
	{
		if($software->getId())
		{
			//Pega dados do software. 
			$idSoftware = $software->getId();
			$selectSoftware = "Select * From software Where id_software = $idSoftware";
			$result = $this->getConexao()->query($selectSoftware);
			foreach ($result as $linha)
			{

				$software->setNome($linha['nome_software']);
				
				
			}

			
			$selectObjetos = "SELECT * FROM objeto WHERE id_software = $idSoftware";
			$result = $this->getConexao()->query($selectObjetos);
			foreach ($result as $linha)
			{
				$objeto = new Objeto();
				$objeto->setNome($linha['nome_objeto']);
				
				$objeto->setId($linha['id_objeto']);
				$idObjeto = $linha['id_objeto'];
				
				$selectAtributo = "SELECT * FROM atributo WHERE id_objeto = $idObjeto";
				$resultAtributo = $this->getConexao()->query($selectAtributo);
				foreach ($resultAtributo as $linhaatributo){
					$atributo = new Atributo();
					$atributo->setId($linhaatributo['id_atributo']);
					$atributo->setNome($linhaatributo['nome_atributo']);
					$atributo->setTipo($linhaatributo['tipo_atributo']);
					$atributo->setIndice($linhaatributo['indice_atributo']);
					$atributo->setTipoDeRelacionamentoComObjeto($linhaatributo['relacionamento_atributo']);
					$objeto->addAtributo($atributo);
					
				}
				
				
				$software->addObjeto($objeto);
				
				
			}
			
			return $software;
			
		}
		else
		{
			
			return null;
		}
		
	}
	
	
	
	
}




?>


