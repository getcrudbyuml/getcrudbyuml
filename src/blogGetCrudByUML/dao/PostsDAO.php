<?php
            
/**
 * Classe feita para manipulação do objeto Posts
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte
 */
     
namespace blogGetCrudByUML\dao;
use PDO;
use PDOException;
use blogGetCrudByUML\model\Posts;


class PostsDAO extends DAO {
    
    

            
            
    public function update(Posts $posts)
    {
        $id = $posts->getId();
            
            
        $sql = "UPDATE posts
                SET
                content = :content,
                title = :title,
                date = :date,
                img_path = :imgPath
                WHERE posts.id = :id;";
			$content = $posts->getContent();
			$title = $posts->getTitle();
			$date = $posts->getDate();
			$imgPath = $posts->getImgPath();
            
        try {
            
            $stmt = $this->getConnection()->prepare($sql);
			$stmt->bindParam(":id", $id, PDO::PARAM_INT);
			$stmt->bindParam(":content", $content, PDO::PARAM_STR);
			$stmt->bindParam(":title", $title, PDO::PARAM_STR);
			$stmt->bindParam(":date", $date, PDO::PARAM_STR);
			$stmt->bindParam(":imgPath", $imgPath, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
            
    }
            
            

    public function insert(Posts $posts){
        $sql = "INSERT INTO posts(content, title, date, img_path) VALUES (:content, :title, :date, :imgPath);";
		$content = $posts->getContent();
		$title = $posts->getTitle();
		$date = $posts->getDate();
		$imgPath = $posts->getImgPath();
		try {
			$db = $this->getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":content", $content, PDO::PARAM_STR);
			$stmt->bindParam(":title", $title, PDO::PARAM_STR);
			$stmt->bindParam(":date", $date, PDO::PARAM_STR);
			$stmt->bindParam(":imgPath", $imgPath, PDO::PARAM_STR);
			return $stmt->execute();
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
            
    }
    public function insertWithPK(Posts $posts){
        $sql = "INSERT INTO posts(id, content, title, date, img_path) VALUES (:id, :content, :title, :date, :imgPath);";
		$id = $posts->getId();
		$content = $posts->getContent();
		$title = $posts->getTitle();
		$date = $posts->getDate();
		$imgPath = $posts->getImgPath();
		try {
			$db = $this->getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $id, PDO::PARAM_INT);
			$stmt->bindParam(":content", $content, PDO::PARAM_STR);
			$stmt->bindParam(":title", $title, PDO::PARAM_STR);
			$stmt->bindParam(":date", $date, PDO::PARAM_STR);
			$stmt->bindParam(":imgPath", $imgPath, PDO::PARAM_STR);
			return $stmt->execute();
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}

    }

	public function delete(Posts $posts){
		$id = $posts->getId();
		$sql = "DELETE FROM posts WHERE id = :id";
		    
		try {
			$db = $this->getConnection();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":id", $id, PDO::PARAM_INT);
			return $stmt->execute();
			    
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}


	public function fetch() {
		$list = array ();
		$sql = "SELECT 
posts.id, posts.content, posts.title, posts.date, posts.img_path FROM posts LIMIT 1000";

        try {
            $stmt = $this->connection->prepare($sql);
            
		    if(!$stmt){   
                echo "<br>Mensagem de erro retornada: ".$this->connection->errorInfo()[2]."<br>";
		        return $list;
		    }
            $stmt->execute();
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach ( $result as $row) 
            {
		        $posts = new Posts();
                $posts->setId( $row ['id'] );
                $posts->setContent( $row ['content'] );
                $posts->setTitle( $row ['title'] );
                $posts->setDate( $row ['date'] );
                $posts->setImgPath( $row ['img_path'] );
                $list [] = $posts;

	
		    }
		} catch(PDOException $e) {
		    echo $e->getMessage();
 		}
        return $list;	
    }
        
                
    public function fetchById(Posts $posts) {
        $lista = array();
	    $id = $posts->getId();
                
        $sql = "SELECT posts.id, posts.content, posts.title, posts.date, posts.img_path FROM posts
            WHERE posts.id = :id";
                
        try {
                
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $row ){
		        $posts = new Posts();
                $posts->setId( $row ['id'] );
                $posts->setContent( $row ['content'] );
                $posts->setTitle( $row ['title'] );
                $posts->setDate( $row ['date'] );
                $posts->setImgPath( $row ['img_path'] );
                $lista [] = $posts;

	
		    }
    			    
        } catch(PDOException $e) {
            echo $e->getMessage();
    			    
        }
		return $lista;
    }
                
    public function fetchByContent(Posts $posts) {
        $lista = array();
	    $content = $posts->getContent();
                
        $sql = "SELECT posts.id, posts.content, posts.title, posts.date, posts.img_path FROM posts
            WHERE posts.content like :content";
                
        try {
                
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":content", $content, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $row ){
		        $posts = new Posts();
                $posts->setId( $row ['id'] );
                $posts->setContent( $row ['content'] );
                $posts->setTitle( $row ['title'] );
                $posts->setDate( $row ['date'] );
                $posts->setImgPath( $row ['img_path'] );
                $lista [] = $posts;

	
		    }
    			    
        } catch(PDOException $e) {
            echo $e->getMessage();
    			    
        }
		return $lista;
    }
                
    public function fetchByTitle(Posts $posts) {
        $lista = array();
	    $title = $posts->getTitle();
                
        $sql = "SELECT posts.id, posts.content, posts.title, posts.date, posts.img_path FROM posts
            WHERE posts.title like :title";
                
        try {
                
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":title", $title, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $row ){
		        $posts = new Posts();
                $posts->setId( $row ['id'] );
                $posts->setContent( $row ['content'] );
                $posts->setTitle( $row ['title'] );
                $posts->setDate( $row ['date'] );
                $posts->setImgPath( $row ['img_path'] );
                $lista [] = $posts;

	
		    }
    			    
        } catch(PDOException $e) {
            echo $e->getMessage();
    			    
        }
		return $lista;
    }
                
    public function fetchByDate(Posts $posts) {
        $lista = array();
	    $date = $posts->getDate();
                
        $sql = "SELECT posts.id, posts.content, posts.title, posts.date, posts.img_path FROM posts
            WHERE posts.date like :date";
                
        try {
                
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":date", $date, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $row ){
		        $posts = new Posts();
                $posts->setId( $row ['id'] );
                $posts->setContent( $row ['content'] );
                $posts->setTitle( $row ['title'] );
                $posts->setDate( $row ['date'] );
                $posts->setImgPath( $row ['img_path'] );
                $lista [] = $posts;

	
		    }
    			    
        } catch(PDOException $e) {
            echo $e->getMessage();
    			    
        }
		return $lista;
    }
                
    public function fetchByImgPath(Posts $posts) {
        $lista = array();
	    $imgPath = $posts->getImgPath();
                
        $sql = "SELECT posts.id, posts.content, posts.title, posts.date, posts.img_path FROM posts
            WHERE posts.img_path like :imgPath";
                
        try {
                
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":imgPath", $imgPath, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $row ){
		        $posts = new Posts();
                $posts->setId( $row ['id'] );
                $posts->setContent( $row ['content'] );
                $posts->setTitle( $row ['title'] );
                $posts->setDate( $row ['date'] );
                $posts->setImgPath( $row ['img_path'] );
                $lista [] = $posts;

	
		    }
    			    
        } catch(PDOException $e) {
            echo $e->getMessage();
    			    
        }
		return $lista;
    }
                
    public function fillById(Posts $posts) {
        
	    $id = $posts->getId();
	    $sql = "SELECT posts.id, posts.content, posts.title, posts.date, posts.img_path FROM posts
                WHERE posts.id = :id
                 LIMIT 1000";
                
        try {
            $stmt = $this->connection->prepare($sql);
                
		    if(!$stmt){
                echo "<br>Mensagem de erro retornada: ".$this->connection->errorInfo()[2]."<br>";
		    }
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach ( $result as $row )
            {
                $posts->setId( $row ['id'] );
                $posts->setContent( $row ['content'] );
                $posts->setTitle( $row ['title'] );
                $posts->setDate( $row ['date'] );
                $posts->setImgPath( $row ['img_path'] );
                
                
		    }
		} catch(PDOException $e) {
		    echo $e->getMessage();
 		}
		return $posts;
    }
                
    public function fillByContent(Posts $posts) {
        
	    $content = $posts->getContent();
	    $sql = "SELECT posts.id, posts.content, posts.title, posts.date, posts.img_path FROM posts
                WHERE posts.content = :content
                 LIMIT 1000";
                
        try {
            $stmt = $this->connection->prepare($sql);
                
		    if(!$stmt){
                echo "<br>Mensagem de erro retornada: ".$this->connection->errorInfo()[2]."<br>";
		    }
            $stmt->bindParam(":content", $content, PDO::PARAM_STR);
            $stmt->execute();
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach ( $result as $row )
            {
                $posts->setId( $row ['id'] );
                $posts->setContent( $row ['content'] );
                $posts->setTitle( $row ['title'] );
                $posts->setDate( $row ['date'] );
                $posts->setImgPath( $row ['img_path'] );
                
                
		    }
		} catch(PDOException $e) {
		    echo $e->getMessage();
 		}
		return $posts;
    }
                
    public function fillByTitle(Posts $posts) {
        
	    $title = $posts->getTitle();
	    $sql = "SELECT posts.id, posts.content, posts.title, posts.date, posts.img_path FROM posts
                WHERE posts.title = :title
                 LIMIT 1000";
                
        try {
            $stmt = $this->connection->prepare($sql);
                
		    if(!$stmt){
                echo "<br>Mensagem de erro retornada: ".$this->connection->errorInfo()[2]."<br>";
		    }
            $stmt->bindParam(":title", $title, PDO::PARAM_STR);
            $stmt->execute();
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach ( $result as $row )
            {
                $posts->setId( $row ['id'] );
                $posts->setContent( $row ['content'] );
                $posts->setTitle( $row ['title'] );
                $posts->setDate( $row ['date'] );
                $posts->setImgPath( $row ['img_path'] );
                
                
		    }
		} catch(PDOException $e) {
		    echo $e->getMessage();
 		}
		return $posts;
    }
                
    public function fillByDate(Posts $posts) {
        
	    $date = $posts->getDate();
	    $sql = "SELECT posts.id, posts.content, posts.title, posts.date, posts.img_path FROM posts
                WHERE posts.date = :date
                 LIMIT 1000";
                
        try {
            $stmt = $this->connection->prepare($sql);
                
		    if(!$stmt){
                echo "<br>Mensagem de erro retornada: ".$this->connection->errorInfo()[2]."<br>";
		    }
            $stmt->bindParam(":date", $date, PDO::PARAM_STR);
            $stmt->execute();
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach ( $result as $row )
            {
                $posts->setId( $row ['id'] );
                $posts->setContent( $row ['content'] );
                $posts->setTitle( $row ['title'] );
                $posts->setDate( $row ['date'] );
                $posts->setImgPath( $row ['img_path'] );
                
                
		    }
		} catch(PDOException $e) {
		    echo $e->getMessage();
 		}
		return $posts;
    }
                
    public function fillByImgPath(Posts $posts) {
        
	    $imgPath = $posts->getImgPath();
	    $sql = "SELECT posts.id, posts.content, posts.title, posts.date, posts.img_path FROM posts
                WHERE posts.img_path = :imgPath
                 LIMIT 1000";
                
        try {
            $stmt = $this->connection->prepare($sql);
                
		    if(!$stmt){
                echo "<br>Mensagem de erro retornada: ".$this->connection->errorInfo()[2]."<br>";
		    }
            $stmt->bindParam(":imgPath", $imgPath, PDO::PARAM_STR);
            $stmt->execute();
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach ( $result as $row )
            {
                $posts->setId( $row ['id'] );
                $posts->setContent( $row ['content'] );
                $posts->setTitle( $row ['title'] );
                $posts->setDate( $row ['date'] );
                $posts->setImgPath( $row ['img_path'] );
                
                
		    }
		} catch(PDOException $e) {
		    echo $e->getMessage();
 		}
		return $posts;
    }
}