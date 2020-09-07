<?php
                
/**
 * Customize sua classe
 *
 */



class  UsuarioCustomDAO extends UsuarioDAO {
    
    public function autentica(Usuario $usuario){
        $login = $usuario->getLogin();
        $senha = $usuario->getSenha() ;
        $sql = "SELECT * FROM usuario WHERE (login  ='$login' AND senha = '$senha') OR (email ='$login' AND senha = '$senha') LIMIT 1";
        
        foreach ( $this->getConexao ()->query ( $sql ) as $linha ) {
            $usuario->setLogin ( $linha ['login'] );
            $usuario->setEmail( $linha ['email'] );
            $usuario->setId( $linha ['id'] );
            $usuario->setNivel( $linha ['nivel'] );
            return true;
        }
        return false;
        
    }
    

}