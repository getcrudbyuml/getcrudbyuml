<?php
            
/**
 * Classe feita para manipulação do objeto Posts
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */

namespace blogGetCrudByUML\model;

class Posts {
	private $id;
	private $content;
	private $title;
	private $date;
	private $imgPath;
    public function __construct(){

    }
	public function setId($id) {
		$this->id = $id;
	}
		    
	public function getId() {
		return $this->id;
	}
	public function setContent($content) {
	    $this->content = $content;
	    $html = new \simple_html_dom($content);
	    $i = 0;
	    if($html == null){
	        $this->content = '';
	        return;
	    }
	    foreach($html->find('img') as $img)
	    {
	        $search = 'http://dti.unilab.edu.br/wp-content';
	        $replacement = 'wp-content';
	        
	        $i++;
	        if($i == 1){
	            $this->imgPath = $img->src;
	            $this->imgPath = str_replace($search, $replacement, $this->imgPath);
	        }
	        
	        
	        $this->content = preg_replace('#<img.+?src=[\'"]([^\'"]+)[\'"].*>#i', '<img src="$1" class="img-fluid"></a>', $this->content);
	        $this->content = str_replace($search, $replacement, $this->content);
	        
	    }
	}
		    
	public function getContent() {
		return $this->content;
	}
	public function setTitle($title) {
		$this->title = $title;
	}
		    
	public function getTitle() {
		return $this->title;
	}
	public function setDate($date) {
		$this->date = $date;
	}
		    
	public function getDate() {
		return $this->date;
	}
	public function setImgPath($imgPath) {
		$this->imgPath = $imgPath;
	}
		    
	public function getImgPath() {
		return $this->imgPath;
	}
	public function __toString(){
	    return $this->id.' - '.$this->content.' - '.$this->title.' - '.$this->date.' - '.$this->imgPath;
	}
                

}
?>