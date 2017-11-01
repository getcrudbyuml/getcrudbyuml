<?php



class EscritorPHP extends Escritor{
	
	/**
	 * criando cada pasta do software se ele for PHP.
	 * (non-PHPdoc)
	 * @see Escritor::criaEstrutura()
	 */
	public function escreverSoftware(){
		$this->criaEstrutura();
		$this->criaClasses();
		$this->criaControllers();
		$this->geraIndex();
		$this->criaStyle();
		$this->criaClassesDAO();
		$this->criaForms();
		
		
	}
	public function geraIndex(){
		$arquivo = new Arquivo();
		$gerador = new GeradorDeCodigoPHP();
		$gerador->geraIndex($this->software);
		$arquivo->setCaminho($gerador->getCaminho());
		$arquivo->setConteudo($gerador->getCodigo());
		$arquivo->criaArquivo();
		
		
	}
	public function criaEstrutura(){
		
		$nomeDoSite = $this->software->getNome();
		
		//pasta do programa
		$caminho = 'sistemasphp/'.$nomeDoSite ;
		
		$pastadoprograma = new Diretorio();
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//pasta das classes
		$caminho = 'sistemasphp/'.$nomeDoSite .'/classes';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//Classes especificas para o programa
		$caminho ='sistemasphp/'.$nomeDoSite.'/classes/model';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//Classes especificas para o programa
		$caminho ='sistemasphp/'.$nomeDoSite.'/classes/view';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//Classes especificas para o programa
		$caminho ='sistemasphp/'.$nomeDoSite.'/classes/controller';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		
		//Classes especificas para o programa
		$caminho ='sistemasphp/'.$nomeDoSite.'/classes/view';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//Classes especificas para o programa
		$caminho ='sistemasphp/'.$nomeDoSite.'/classes/dao';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//pasta de imagens
		$caminho ='sistemasphp/'.$nomeDoSite.'/img';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//pasta de imagens
		$caminho ='sistemasphp/'.$nomeDoSite.'/css';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
		//pasta de imagens
		$caminho ='sistemasphp/'.$nomeDoSite.'/js';
		$pastadoprograma->setCaminho($caminho);
		$pastadoprograma->geraDiretorio();
		
	}
	
	
	public function criaClasses(){
		$software = $this->software;
		$vetorGeradores = GeradorDeCodigoPHP::geraClasses($software);
		if($vetorGeradores){
			
			foreach ($vetorGeradores as $gerador)
			{
				
				
				$arquivo = new Arquivo();
				$arquivo->setCaminho($gerador->getCaminho());
				$arquivo->setConteudo($gerador->getCodigo());
				$arquivo->criaArquivo();
				
				
				
			}
		}
		

		
		
	}
	public function criaControllers(){
		$software = $this->software;
		$vetorGeradores = GeradorDeCodigoPHP::geraClassesController($software);
		if($vetorGeradores){
				
			foreach ($vetorGeradores as $gerador)
			{
	
	
				$arquivo = new Arquivo();
				$arquivo->setCaminho($gerador->getCaminho());
				$arquivo->setConteudo($gerador->getCodigo());
				$arquivo->criaArquivo();
	
	
	
			}
		}
	
	
	
	
	}
	public function criaForms(){
		$software = $this->software;
		$vetorGeradores = GeradorDeCodigoPHP::geraFormularios($software);
		if($vetorGeradores){
				
			foreach ($vetorGeradores as $gerador)
			{
		
		
				$arquivo = new Arquivo();
				$arquivo->setCaminho($gerador->getCaminho());
				$arquivo->setConteudo($gerador->getCodigo());
				$arquivo->criaArquivo();
		

		
		
			}
		}
		
		
		
		
	}
	public function criaClassesDAO(){
		
		$software = $this->software;
		$vetorGeradores = GeradorDeCodigoPHP::geraDaos($software);
		if($vetorGeradores){
				
			foreach ($vetorGeradores as $gerador)
			{
		
		
				$arquivo = new Arquivo();
				$arquivo->setCaminho($gerador->getCaminho());
				$arquivo->setConteudo($gerador->getCodigo());
				$arquivo->criaArquivo();
		
		
		
			}
		}
		
		
	}
	public function criaStyle(){
		$arquivo = new Arquivo();
		$gerador = new GeradorDeCodigoPHP();
		$gerador->geraStyle($this->software);
		
		$arquivo->setConteudo($gerador->getCodigo());
		$arquivo->setCaminho($gerador->getCaminho());
		$arquivo->criaArquivo();
			
	}
	
	
}

?>