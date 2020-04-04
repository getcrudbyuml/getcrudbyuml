<?php
                
/**
 * Classe feita para manipulação do objeto Auditoria
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte
 *
 *
 */
class AuditoriaDAO extends DAO {
    
    
    public function atualizar(Auditoria $auditoria)
    {
        $id = $auditoria->getId();
        
        
        $sql = "UPDATE auditoria
                SET
                pagina = :pagina,
                ipVisitante = :ipVisitante,
                infoSessao = :infoSessao,
                data = :data
                WHERE auditoria.id = :id;";
			$pagina = $auditoria->getPagina();
			$ipVisitante = $auditoria->getIpVisitante();
			$infoSessao = $auditoria->getInfoSessao();
			$data = $auditoria->getData();
                
        try {
                
            $stmt = $this->getConexao()->prepare($sql);
			$stmt->bindParam("id", $id, PDO::PARAM_STR);
			$stmt->bindParam("pagina", $pagina, PDO::PARAM_STR);
			$stmt->bindParam("ipVisitante", $ipVisitante, PDO::PARAM_STR);
			$stmt->bindParam("infoSessao", $infoSessao, PDO::PARAM_STR);
			$stmt->bindParam("data", $data, PDO::PARAM_STR);
                
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
                
    }


    public function inserirComPK(Auditoria $auditoria){
        $sql = "INSERT INTO auditoria(id, pagina, ip_visitante, info_sessao, data) VALUES (:id, :pagina, :ipVisitante, :infoSessao, :data);";
		$id = $auditoria->getId();
		$pagina = $auditoria->getPagina();
		$ipVisitante = $auditoria->getIpVisitante();
		$infoSessao = $auditoria->getInfoSessao();
		$data = $auditoria->getData();
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("id", $id, PDO::PARAM_INT);
			$stmt->bindParam("pagina", $pagina, PDO::PARAM_STR);
			$stmt->bindParam("ipVisitante", $ipVisitante, PDO::PARAM_STR);
			$stmt->bindParam("infoSessao", $infoSessao, PDO::PARAM_STR);
			$stmt->bindParam("data", $data, PDO::PARAM_STR);
			return $stmt->execute();
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}

    }
    public function inserir(Auditoria $auditoria){
        $sql = "INSERT INTO auditoria(pagina, ip_visitante, info_sessao, data) VALUES (:pagina, :ipVisitante, :infoSessao, :data);";
		$pagina = $auditoria->getPagina();
		$ipVisitante = $auditoria->getIpVisitante();
		$infoSessao = $auditoria->getInfoSessao();
		$data = $auditoria->getData();
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("pagina", $pagina, PDO::PARAM_STR);
			$stmt->bindParam("ipVisitante", $ipVisitante, PDO::PARAM_STR);
			$stmt->bindParam("infoSessao", $infoSessao, PDO::PARAM_STR);
			$stmt->bindParam("data", $data, PDO::PARAM_STR);
			return $stmt->execute();
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
            
    }
    
	public function excluir(Auditoria $auditoria){
		$id = $auditoria->getId();
		$sql = "DELETE FROM auditoria WHERE id = :id";
		    
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
        auditoria.id, 
        auditoria.pagina, 
        auditoria.ip_visitante, 
        auditoria.info_sessao, 
        auditoria.data
        FROM auditoria
                 LIMIT 1000";
		$result = $this->getConexao ()->query ( $sql );
                
		foreach ( $result as $linha ) {
                
			$auditoria = new Auditoria();
        
			$auditoria->setId( $linha ['id'] );
			$auditoria->setPagina( $linha ['pagina'] );
			$auditoria->setIpVisitante( $linha ['ip_visitante'] );
			$auditoria->setInfoSessao( $linha ['info_sessao'] );
			$auditoria->setData( $linha ['data'] );
			$lista [] = $auditoria;
		}
		return $lista;
	}
                    
    public function pesquisaPorId(Auditoria $auditoria) {
        $lista = array();
	    $id = $auditoria->getId();
	    $sql = "SELECT 
                auditoria.id, 
                auditoria.pagina, 
                auditoria.ipvisitante, 
                auditoria.infosessao, 
                auditoria.data
                FROM auditoria
                WHERE auditoria.id = $id";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
            $auditoria = new Auditoria();
	        $auditoria->setId( $linha ['id'] );
	        $auditoria->setPagina( $linha ['pagina'] );
	        $auditoria->setIpVisitante( $linha ['ipVisitante'] );
	        $auditoria->setInfoSessao( $linha ['infoSessao'] );
	        $auditoria->setData( $linha ['data'] );
			$lista [] = $auditoria;
		}
		return $lista;
    }
                    
    public function pesquisaPorPagina(Auditoria $auditoria) {
        $lista = array();
	    $pagina = $auditoria->getPagina();
	    $sql = "SELECT 
                auditoria.id, 
                auditoria.pagina, 
                auditoria.ipvisitante, 
                auditoria.infosessao, 
                auditoria.data
                FROM auditoria
                WHERE auditoria.pagina like '%$pagina%'";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
            $auditoria = new Auditoria();
	        $auditoria->setId( $linha ['id'] );
	        $auditoria->setPagina( $linha ['pagina'] );
	        $auditoria->setIpVisitante( $linha ['ipVisitante'] );
	        $auditoria->setInfoSessao( $linha ['infoSessao'] );
	        $auditoria->setData( $linha ['data'] );
			$lista [] = $auditoria;
		}
		return $lista;
    }
                    
    public function pesquisaPorIpVisitante(Auditoria $auditoria) {
        $lista = array();
	    $ipVisitante = $auditoria->getIpVisitante();
	    $sql = "SELECT 
                auditoria.id, 
                auditoria.pagina, 
                auditoria.ipvisitante, 
                auditoria.infosessao, 
                auditoria.data
                FROM auditoria
                WHERE auditoria.ipVisitante like '%$ipVisitante%'";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
            $auditoria = new Auditoria();
	        $auditoria->setId( $linha ['id'] );
	        $auditoria->setPagina( $linha ['pagina'] );
	        $auditoria->setIpVisitante( $linha ['ipVisitante'] );
	        $auditoria->setInfoSessao( $linha ['infoSessao'] );
	        $auditoria->setData( $linha ['data'] );
			$lista [] = $auditoria;
		}
		return $lista;
    }
                    
    public function pesquisaPorInfoSessao(Auditoria $auditoria) {
        $lista = array();
	    $infoSessao = $auditoria->getInfoSessao();
	    $sql = "SELECT 
                auditoria.id, 
                auditoria.pagina, 
                auditoria.ipvisitante, 
                auditoria.infosessao, 
                auditoria.data
                FROM auditoria
                WHERE auditoria.infoSessao like '%$infoSessao%'";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
            $auditoria = new Auditoria();
	        $auditoria->setId( $linha ['id'] );
	        $auditoria->setPagina( $linha ['pagina'] );
	        $auditoria->setIpVisitante( $linha ['ipVisitante'] );
	        $auditoria->setInfoSessao( $linha ['infoSessao'] );
	        $auditoria->setData( $linha ['data'] );
			$lista [] = $auditoria;
		}
		return $lista;
    }
                    
    public function pesquisaPorData(Auditoria $auditoria) {
        $lista = array();
	    $data = $auditoria->getData();
	    $sql = "SELECT 
                auditoria.id, 
                auditoria.pagina, 
                auditoria.ipvisitante, 
                auditoria.infosessao, 
                auditoria.data
                FROM auditoria
                WHERE auditoria.data like '%$data%'";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
            $auditoria = new Auditoria();
	        $auditoria->setId( $linha ['id'] );
	        $auditoria->setPagina( $linha ['pagina'] );
	        $auditoria->setIpVisitante( $linha ['ipVisitante'] );
	        $auditoria->setInfoSessao( $linha ['infoSessao'] );
	        $auditoria->setData( $linha ['data'] );
			$lista [] = $auditoria;
		}
		return $lista;
    }
                    
    public function preenchePorId(Auditoria $auditoria) {

	    $id = $auditoria->getId();
	    $sql = "SELECT 
                auditoria.id, 
                auditoria.pagina, 
                auditoria.ipvisitante, 
                auditoria.infosessao, 
                auditoria.data
                FROM auditoria
                WHERE auditoria.id = $id";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
	        $auditoria->setId( $linha ['id'] );
	        $auditoria->setPagina( $linha ['pagina'] );
	        $auditoria->setIpVisitante( $linha ['ipVisitante'] );
	        $auditoria->setInfoSessao( $linha ['infoSessao'] );
	        $auditoria->setData( $linha ['data'] );

		}
		return $auditoria;
    }
                    
    public function preenchePorPagina(Auditoria $auditoria) {

	    $pagina = $auditoria->getPagina();
	    $sql = "SELECT 
                auditoria.id, 
                auditoria.pagina, 
                auditoria.ipvisitante, 
                auditoria.infosessao, 
                auditoria.data
                FROM auditoria
                WHERE auditoria.pagina like '%$pagina%'";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
	        $auditoria->setId( $linha ['id'] );
	        $auditoria->setPagina( $linha ['pagina'] );
	        $auditoria->setIpVisitante( $linha ['ipVisitante'] );
	        $auditoria->setInfoSessao( $linha ['infoSessao'] );
	        $auditoria->setData( $linha ['data'] );

		}
		return $auditoria;
    }
                    
    public function preenchePorIpVisitante(Auditoria $auditoria) {

	    $ipVisitante = $auditoria->getIpVisitante();
	    $sql = "SELECT 
                auditoria.id, 
                auditoria.pagina, 
                auditoria.ipvisitante, 
                auditoria.infosessao, 
                auditoria.data
                FROM auditoria
                WHERE auditoria.ipVisitante like '%$ipVisitante%'";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
	        $auditoria->setId( $linha ['id'] );
	        $auditoria->setPagina( $linha ['pagina'] );
	        $auditoria->setIpVisitante( $linha ['ipVisitante'] );
	        $auditoria->setInfoSessao( $linha ['infoSessao'] );
	        $auditoria->setData( $linha ['data'] );

		}
		return $auditoria;
    }
                    
    public function preenchePorInfoSessao(Auditoria $auditoria) {

	    $infoSessao = $auditoria->getInfoSessao();
	    $sql = "SELECT 
                auditoria.id, 
                auditoria.pagina, 
                auditoria.ipvisitante, 
                auditoria.infosessao, 
                auditoria.data
                FROM auditoria
                WHERE auditoria.infoSessao like '%$infoSessao%'";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
	        $auditoria->setId( $linha ['id'] );
	        $auditoria->setPagina( $linha ['pagina'] );
	        $auditoria->setIpVisitante( $linha ['ipVisitante'] );
	        $auditoria->setInfoSessao( $linha ['infoSessao'] );
	        $auditoria->setData( $linha ['data'] );

		}
		return $auditoria;
    }
                    
    public function preenchePorData(Auditoria $auditoria) {

	    $data = $auditoria->getData();
	    $sql = "SELECT 
                auditoria.id, 
                auditoria.pagina, 
                auditoria.ipvisitante, 
                auditoria.infosessao, 
                auditoria.data
                FROM auditoria
                WHERE auditoria.data like '%$data%'";
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
	        $auditoria->setId( $linha ['id'] );
	        $auditoria->setPagina( $linha ['pagina'] );
	        $auditoria->setIpVisitante( $linha ['ipVisitante'] );
	        $auditoria->setInfoSessao( $linha ['infoSessao'] );
	        $auditoria->setData( $linha ['data'] );

		}
		return $auditoria;
    }
                
                
}