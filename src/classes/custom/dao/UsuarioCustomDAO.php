<?php
                
/**
 * Customize sua classe
 *
 */



class  UsuarioCustomDAO extends UsuarioDAO {
    

    
    public function autentica(Usuario $usuario){
        $login = $usuario->getLogin();
        $senha = $usuario->getSenha() ;
        $sql = "SELECT * FROM usuario WHERE login  = :login AND senha = :senha LIMIT 1";
        
        try {
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(":login", $login, PDO::PARAM_STR);
            $stmt->bindParam(":senha", $senha, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $linha ) {
                $usuario->setLogin ( $linha ['login'] );
                $usuario->setId( $linha ['id'] );
                $usuario->setNivel( $linha ['nivel'] );
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return false;
        }
        
        
    }
    

}