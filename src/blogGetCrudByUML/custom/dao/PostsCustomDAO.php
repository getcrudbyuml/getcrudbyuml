<?php
                
/**
 * Customize sua classe
 *
 */


namespace blogGetCrudByUML\custom\dao;
use blogGetCrudByUML\dao\PostsDAO;
use blogGetCrudByUML\model\Posts;
use PDO;
use PDOException;


class  PostsCustomDAO extends PostsDAO {
    
    /**
     * AND post_type = 'page' se quiser mostrar páginas ao invés de posts.
     * @param number $limit
     * @param string $tipo
     * @return Posts[]
     */
    public function retornaLista($limit = 100, $tipo = null) {
        if($tipo == null){
            $tipo = 'post';
        }
        $lista = array ();
        $sql = "SELECT * FROM ".PREFIXO_WP."posts
        WHERE post_status = 'publish'
        AND post_type = :tipo
         ORDER BY ID DESC LIMIT :limit";
        
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":tipo", $tipo, PDO::PARAM_STR);
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $linha)
            {
                $post = new Posts();
                
                $post->setID( $linha ['ID'] );
                $post->setContent(utf8_encode (  $linha ['post_content'] ));
                $post->setTitle(utf8_encode (   $linha ['post_title']) );
                $post->setDate( $linha ['post_date'] );
                $lista [] = $post;
            }
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
        
        return $lista;
    }
    
    public function pesquisaPorID(Posts $post) {

        $lista = array();
        $id = $post->getId();
        
        $sql = "SELECT * FROM ".PREFIXO_WP."posts
                 WHERE ID = :id";
        try {
            
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $linha ){
                $post->setID( $linha ['ID'] );
                $post->setContent(utf8_encode (  $linha ['post_content'] ));
                $post->setTitle( utf8_encode ( $linha ['post_title'] ));
                $post->setDate( $linha ['post_date'] );
                $lista [] = $post;
                
                
            }
            
        } catch(PDOException $e) {
            echo $e->getMessage();
            
        }
        return $lista;
    }
    
    public function pesquisaPorContent(Posts $post) {
        $lista = array();
        $content = '%'.$post->getContent().'%';
        
        $sql = "SELECT * FROM ".PREFIXO_WP."posts
        WHERE
        post_status = 'publish'
        AND post_type = 'post'
        AND LOWER(post_content) like :content
        ORDER BY ID DESC LIMIT 1000";
        
        try {
            
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":content", $content, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $linha ){
                $post2 = new Posts();
                $post2->setID( $linha ['ID'] );
                $post2->setContent(utf8_encode (  $linha ['post_content'] ));
                $post2->setTitle( utf8_encode ( $linha ['post_title'] ));
                $post2->setDate( $linha ['post_date'] );
                $lista [] = $post2;
                
            }
            
        } catch(PDOException $e) {
            echo $e->getMessage();
            
        }
        return $lista;
        
        
        
    }
    
    public function pesquisaPorTitle(Posts $post) {        
        $lista = array();
        $title = '%'.$post->getTitle().'%';
        
        $sql = "SELECT * FROM ".PREFIXO_WP."posts
                 WHERE
                post_status = 'publish'
                AND post_type = 'post'
                AND LOWER(post_title) like :title
                 ORDER BY ID DESC LIMIT 1000";
        
        try {
            
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":title", $title, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $linha ){
                $post2 = new Posts();
                $post2->setID( $linha ['ID'] );
                $post2->setContent(utf8_encode (  $linha ['post_content'] ));
                $post2->setTitle( utf8_encode ( $linha ['post_title'] ));
                $post2->setDate( $linha ['post_date'] );
                $lista [] = $post2;
                
                
            }
            
        } catch(PDOException $e) {
            echo $e->getMessage();
            
        }
        return $lista;
        
    }
    
    


}