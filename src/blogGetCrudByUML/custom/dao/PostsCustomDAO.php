<?php
                
/**
 * Customize sua classe
 *
 */


namespace blogGetCrudByUML\custom\dao;
use blogGetCrudByUML\dao\PostsDAO;
use blogGetCrudByUML\model\Posts;


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
        AND post_type = '$tipo'
         ORDER BY ID DESC LIMIT $limit";
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ( $result as $linha ) {
            
            $post = new Posts();
            
            $post->setID( $linha ['ID'] );
            $post->setContent(utf8_encode (  $linha ['post_content'] ));
            $post->setTitle(utf8_encode (   $linha ['post_title']) );
            $post->setDate( $linha ['post_date'] );
            $lista [] = $post;
        }
        return $lista;
    }
    
    public function pesquisaPorID(Posts $post) {
        $lista = array();
        $ID = $post->getID();
        $sql = "SELECT * FROM ".PREFIXO_WP."posts
                 WHERE ID = $ID";
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ( $result as $linha ) {
            $post->setID( $linha ['ID'] );
            $post->setContent(utf8_encode (  $linha ['post_content'] ));
            $post->setTitle( utf8_encode ( $linha ['post_title'] ));
            $post->setDate( $linha ['post_date'] );
            $lista [] = $post;
        }
        return $lista;
    }
    
    public function pesquisaPorContent(Posts $post) {
        $lista = array();
        $content = strtolower($post->getContent());
        $sql = "SELECT * FROM ".PREFIXO_WP."posts
        WHERE
        post_status = 'publish'
        AND post_type = 'post'
        AND LOWER(post_content) like '%$content%'
        ORDER BY ID DESC LIMIT 1000";
        
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ( $result as $linha ) {
            $post2 = new Posts();
            $post2->setID( $linha ['ID'] );
            $post2->setContent(utf8_encode (  $linha ['post_content'] ));
            $post2->setTitle( utf8_encode ( $linha ['post_title'] ));
            $post2->setDate( $linha ['post_date'] );
            $lista [] = $post2;
        }
        return $lista;
    }
    
    public function pesquisaPorTitle(Posts $post) {
        $lista = array();
        $title = strtolower($post->getTitle());
        $sql = "SELECT * FROM ".PREFIXO_WP."posts
                 WHERE
                post_status = 'publish'
                AND post_type = 'post'
                AND LOWER(post_title) like '%$title%'
                 ORDER BY ID DESC LIMIT 1000";
        
        
        $result = $this->getConexao ()->query ( $sql );
        
        foreach ( $result as $linha ) {
            $post2 = new Posts();
            $post2->setID( $linha ['ID'] );
            $post2->setContent(utf8_encode (  $linha ['post_content'] ));
            $post2->setTitle( utf8_encode ( $linha ['post_title'] ));
            $post2->setDate( $linha ['post_date'] );
            $lista [] = $post2;
        }
        return $lista;
    }
    
    


}