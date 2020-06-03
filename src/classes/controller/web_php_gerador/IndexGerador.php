<?php

class IndexGerador
{

    private $software;
    
    private $listaDeArquivos;
    
    private $diretorio;
    
    public static function main(Software $software, $diretorio = 'sistemas'){
        $gerador = new IndexGerador($software, $diretorio);
        $gerador->gerarCodigo();
    }
    
    
    public function __construct(Software $software, $diretorio)
    {
        $this->software = $software;
        $this->diretorio = $diretorio;
        $this->listaDeArquivos = array();
    }
    public function gerarCodigo(){
        $this->geraHTACCESS();
        $this->geraStyle();
        $this->geraIndex();
        $this->criarArquivos();
        
    }
    
    public function geraHTACCESS(){
        if (! count($this->software->getObjetos())) {
            return;
        }
        $codigo  = 'RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?api=$1 [QSA,L]
';
        $caminho = $this->diretorio.'/AppWebPHP/'.$this->software->getNomeSimples().'/src/';
        $caminho = $caminho.'.htaccess';
        $this->listaDeArquivos[$caminho] = $codigo;
        
    }
    
    public function geraIndex(){
        
        if (! count($this->software->getObjetos())) {
            return;
        }
        $codigo = '<?php
            
            
function autoload($classe) {
            
    if (file_exists ( \'classes/dao/\' . $classe . \'.php\' )){
		include_once \'classes/dao/\' . $classe . \'.php\';
	}
	else if (file_exists ( \'classes/model/\' . $classe . \'.php\' )){
		include_once \'classes/model/\' . $classe . \'.php\';
	}
	else if (file_exists ( \'classes/controller/\' . $classe . \'.php\' )){
		include_once \'classes/controller/\' . $classe . \'.php\';
	}
	else if (file_exists ( \'classes/util/\' . $classe . \'.php\' )){
		include_once \'classes/util/\' . $classe . \'.php\';
	}
	else if (file_exists ( \'classes/view/\' . $classe . \'.php\' )){
		include_once \'classes/view/\' . $classe . \'.php\';
	}
}
spl_autoload_register(\'autoload\');
            
if(isset($_REQUEST[\'api\'])){
';
        foreach ($this->software->getObjetos() as $objeto) {
            $codigo .= '
    '.ucfirst($objeto->getNome()).'Controller::mainREST();';
            
        }
        
        
        $codigo .= '
    exit;
}
            
            
?>
            
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>' . $this->software->getNome() . '</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">'.$this->software->getNome().'</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Alterna navegação">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">';
        
        
        
        foreach ($this->software->getObjetos() as $objeto) {
            
            $codigo .= '<a class="nav-item nav-link" href="?pagina=' . $objeto->getNomeSnakeCase() . '">' . $objeto->getNomeTextual() . '</a>';
        }
        
        $codigo .= '
            
    </div>
  </div>
</nav>
	<main role="main">
            
      <section class="jumbotron text-center">
        <div class="container">
          <h1 class="jumbotron-heading">'.$this->software->getNome().'</h1>
              
        </div>
      </section>
              
        <div class="album py-5 bg-light">
            <div class="container">';
        
        
        $codigo .= '
            
            
            
<?php
if(isset($_GET[\'pagina\'])){
					switch ($_GET[\'pagina\']){';
        
        foreach ($this->software->getObjetos() as $objeto) {
            $codigo .= '
						case \'' .$objeto->getNomeSnakeCase() . '\':
						    ' . ucfirst ($objeto->getNome()). 'Controller::main();
							break;';
        }
        
        $codigo .= '
						default:
							' . ucfirst ($this->software->getObjetos()[0]->getNome()) . 'Controller::main();
							break;
					}
				}else{
					' . ucfirst ($this->software->getObjetos()[0]->getNome()) . 'Controller::main();
				}
					    
?>';
        
        $codigo .= '
            
            
              </div>
            
            </div>
            
     </main>
            
            
    <footer class="text-muted">
      <div class="container">
        <p class="float-right">
          <a href="#">Voltar ao topo</a>
        </p>
        <p>Este é um software desenvolvido automaticamente pelo escritor de Software.</p>
        <p>Novo no Escritor De Software? Problema o seu.</p>
      </div>
    </footer>
            
';
        
        
        
        $codigo  .= '
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</body>
</html>';
        $caminho = $this->diretorio.'/AppWebPHP/'.$this->software->getNomeSimples().'/src/index.php';
        $this->listaDeArquivos[$caminho] = $codigo;
        
    }
    
    public function geraStyle()
    {
        $codigo = "/*Digite aqui seu arquivo css*/";
        $caminho = $this->diretorio.'/AppWebPHP/'.$this->software->getNomeSimples().'/src/css/style.css';
        $this->listaDeArquivos[$caminho] = $codigo;
    }
    
    public function criarArquivos(){
        
        $caminho = $this->diretorio.'/AppWebPHP/'.$this->software->getNomeSimples().'/src';
        if(!file_exists($caminho.'/img')) {
            mkdir($caminho.'/img', 0777, true);
        }
        if(!file_exists($caminho.'/css')) {
            mkdir($caminho.'/css', 0777, true);
        }
        if(!file_exists($caminho.'/js')) {
            mkdir($caminho.'/js', 0777, true);
        }
        
        foreach ($this->listaDeArquivos as $path => $codigo) {  
            if (file_exists($path)) {
                unlink($path);
            }
            $file = fopen($path, "w+");
            fwrite($file, stripslashes($codigo));
            fclose($file);
        }
    }
}

?>