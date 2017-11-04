<?php



class EscritorPHP extends Escritor{
	
	/**
	 * criando cada pasta do software se ele for PHP.
	 * (non-PHPdoc)
	 * @see Escritor::criaEstrutura()
	 */
	public function escreverSoftware(){
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
// 		$arquivo = new Arquivo();
		$gerador = new GeradorDeCodigoPHP();
		$gerador->geraBancoSqlite($this->software);
// 		$arquivo->setCaminho($gerador->getCaminho());
// 		$arquivo->setConteudo($gerador->getCodigo());
// 		$arquivo->criaArquivo();
	}
	public function criaEstrutura(){
		
		$nomeDoSite = $this->software->getNome();
		
		//pasta do programa
		$caminho = 'sistemasphp/'.$nomeDoSite.'/src' ;
		$pastadoprograma = new Diretorio();
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//pasta das classes
		$caminho = 'sistemasphp/'.$nomeDoSite .'/src/classes';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//Classes especificas para o programa
		$caminho ='sistemasphp/'.$nomeDoSite.'/src/classes/model';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//Classes especificas para o programa
		$caminho ='sistemasphp/'.$nomeDoSite.'/src/classes/view';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//Classes especificas para o programa
		$caminho ='sistemasphp/'.$nomeDoSite.'/src/classes/controller';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		
		//Classes especificas para o programa
		$caminho ='sistemasphp/'.$nomeDoSite.'/src/classes/view';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//Classes especificas para o programa
		$caminho ='sistemasphp/'.$nomeDoSite.'/src/classes/dao';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//pasta de imagens
		$caminho ='sistemasphp/'.$nomeDoSite.'/src/img';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//pasta de imagens
		$caminho ='sistemasphp/'.$nomeDoSite.'/src/css';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//pasta de imagens
		$caminho ='sistemasphp/'.$nomeDoSite.'/src/js';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
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