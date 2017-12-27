<?php



class EscritorPHP extends Escritor{
	
	/**
	 * criando cada pasta do software se ele for PHP.
	 * (non-PHPdoc)
	 * @see Escritor::criaEstrutura()
	 */
	public function escreverSoftware(){
		if(!count($this->software->getObjetos())){
			echo "Ausencia de Objetos, software não criado";
			return;
		}
		foreach ($this->software->getObjetos() as $objeto){
			if(!count($objeto->getAtributos())){
				echo "Encontrado objeto sem atributos, software não criado";
				return;
			} 
		}
		$this->criaEstrutura();
		$this->criaClasseDAO();
		$this->criaClasses();
		$this->criaControllers();
		$this->geraIndex();
		$this->criaStyle();
		$this->criaClassesDAO();
		$this->criaForms();
		$this->geraINI();
		$this->geraBancoSql();
		
		
	}
	public function geraIndex(){
		$gerador = new GeradorDeCodigoPHP();
		$gerador->geraIndex($this->software);
		self::criaArquivo($gerador->getCaminho(), $gerador->getCodigo());
	}
	public static function criaArquivo($caminho, $conteudo){
		if(file_exists($caminho)){
			unlink($caminho);
		}
		$file = @fopen($caminho, "w+");
		@fwrite($file, stripslashes($conteudo));
		@fclose($file);
	}
	public function geraINI(){

		$gerador = new GeradorDeCodigoPHP();
		$gerador->geraINI($this->software);
		self::criaArquivo($gerador->getCaminho(), $gerador->getCodigo());
		
	}
	public function geraBancoSql(){
		$gerador = new GeradorDeCodigoPHP();
		$gerador->geraBancoSqlite($this->software);
		self::criaArquivo($gerador->getCaminho(), $gerador->getCodigo());
		
	}
		
	public function criaEstrutura(){
		$nomeDoSite = $this->software->getNome();	
		@mkdir (  'sistemasphp/'.$nomeDoSite.'/src' , 0777, true );
		@mkdir (  'sistemasphp/'.$nomeDoSite .'/src/classes' , 0777, true );
		@mkdir (  'sistemasphp/'.$nomeDoSite.'/src/classes/model' , 0777, true );
		@mkdir (  'sistemasphp/'.$nomeDoSite.'/src/classes/view' , 0777, true );
		@mkdir (  'sistemasphp/'.$nomeDoSite.'/src/classes/controller' , 0777, true );
		@mkdir (  'sistemasphp/'.$nomeDoSite.'/src/classes/view' , 0777, true );
		@mkdir (  'sistemasphp/'.$nomeDoSite.'/src/classes/dao' , 0777, true );
		@mkdir (  'sistemasphp/'.$nomeDoSite.'/src/img' , 0777, true );
		@mkdir (  'sistemasphp/'.$nomeDoSite.'/src/css' , 0777, true );
		@mkdir ( 'sistemasphp/'.$nomeDoSite.'/src/js' , 0777, true );
	}
	
	public function criaClasses(){
		$software = $this->software;
		$vetorGeradores = GeradorDeCodigoPHP::geraClasses($software);
		if($vetorGeradores){
			foreach ($vetorGeradores as $gerador)
			{
				self::criaArquivo($gerador->getCaminho(), $gerador->getCodigo());
			}
		}
	}
	public function criaControllers(){
		$software = $this->software;
		$vetorGeradores = GeradorDeCodigoPHP::geraClassesController($software);
		if($vetorGeradores){
				
			foreach ($vetorGeradores as $gerador)
			{
				self::criaArquivo($gerador->getCaminho(), $gerador->getCodigo());
			}
		}
	}
	public function criaForms(){
		$software = $this->software;
		$vetorGeradores = GeradorDeCodigoPHP::geraFormularios($software);
		if($vetorGeradores){
			foreach ($vetorGeradores as $gerador)
			{
				self::criaArquivo($gerador->getCaminho(), $gerador->getCodigo());
				
			}
		}
	}
	public function criaClassesDAO(){
		
		$software = $this->software;
		$vetorGeradores = GeradorDeCodigoPHP::geraDaos($software);
		if($vetorGeradores){
				
			foreach ($vetorGeradores as $gerador)
			{
				self::criaArquivo($gerador->getCaminho(), $gerador->getCodigo());
				
			}
		}
	}
	public function criaClasseDAO(){
	
		$software = $this->software;
		$gerador = GeradorDeCodigoPHP::geraClasseDao($this->software);
		self::criaArquivo($gerador->getCaminho(), $gerador->getCodigo());
		
	
			
	}
	public function criaStyle(){
		$gerador = new GeradorDeCodigoPHP();
		$gerador->geraStyle($this->software);
		self::criaArquivo($gerador->getCaminho(), $gerador->getCodigo());
		
			
	}
	
	
}

?>