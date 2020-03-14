<?php


class MainGerador{
    private $software; 
    private $listaDeArquivos; 
    
    public function getListaDeArquivos(){
        return $this->listaDeArquivos;
    }
    
    public function __construct(Software $software){
        $this->software = $software;
    }
    
    public function gerarCodigo(){
        $codigo = $this->geraPOMXML();
        $path = 'sistemas/'.$this->software->getNome().'/java/'.$this->software->getNome().'/pom.xml';
        $this->listaDeArquivos[$path] = $codigo;
        
        $codigo = $this->geraMain();
        $path = 'sistemas/'.$this->software->getNome().'/java/'.$this->software->getNome().'/src/main/java/br/com/escritordesoftware/'.strtolower($this->software->getNome()).'/main/Main.java';
        $this->listaDeArquivos[$path] = $codigo;
        
        $codigo = $this->geraMainPHP();
        $caminho = 'sistemas/'.ucfirst($this->software->getNome()).'/php/src/index.php';
        $this->listaDeArquivos[$caminho] = $codigo;
        
        $codigo = $this->geraStyle();
        $caminho = 'sistemas/'.ucfirst($this->software->getNome()).'/php/src/css/style.css';
        $this->listaDeArquivos[$caminho] = $codigo;
        
        
    }

    public function geraMainPHP(){

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
        
        $codigo .= '<a class="nav-item nav-link" href="?pagina=' . strtolower($objeto->getNome()) . '">' . $objeto->getNome() . '</a>';
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
						case \'' . strtolower($objeto->getNome()) . '\':
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
    return $codigo;
}

    public function geraStyle()
    {
        $codigo = "/*Arquivo css*/";
        return $codigo;
    }
    public function geraMain(){
        $codigo  = 'package br.com.escritordesoftware.'.strtolower($this->software->getNome()).'.main;

public class Main {

	public static void main(String[] args) {
		System.out.println("Ola mundo");
	}

}

';
        return $codigo;
        
    }
    
    public function geraPOMXML(){
        $codigo = '';
        $codigo .= '
<project xmlns="http://maven.apache.org/POM/4.0.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://maven.apache.org/POM/4.0.0 http://maven.apache.org/xsd/maven-4.0.0.xsd">

    <modelVersion>4.0.0</modelVersion>
    <groupId>br.com.escritordesoftware.'.strtolower($this->software->getNome()).'</groupId>
    <artifactId>'.strtolower($this->software->getNome()).'</artifactId>
    <packaging>jar</packaging>
    <version>1.0</version>

    <name>'.$this->software->getNome().'</name>
    <description>'.$this->software->getNome().'</description>
    <url>https://escritordesoftware.com.br</url>

    <properties>
        <project.build.sourceEncoding>UTF-8</project.build.sourceEncoding>
    </properties>

    <dependencies>
        <dependency>
            <groupId>postgresql</groupId>
            <artifactId>postgresql</artifactId>
            <version>8.3-606.jdbc4</version>
        </dependency>
        <dependency>
            <groupId>mysql</groupId>
            <artifactId>mysql-connector-java</artifactId>
            <version>5.0.4</version>
        </dependency>
        <dependency>
            <groupId>org.xerial</groupId>
            <artifactId>sqlite-jdbc</artifactId>
            <version>3.8.7</version>
        </dependency>
    </dependencies>

    <build>
        <plugins>
            <plugin>
                <groupId>org.apache.maven.plugins</groupId>
                <artifactId>maven-compiler-plugin</artifactId>
                <version>3.0</version>
                <configuration>
                    <source>1.7</source>
                    <target>1.7</target>
                </configuration>
            </plugin>
            <!-- com libs internas no jar -->
            <plugin>
                <artifactId>maven-assembly-plugin</artifactId>
                <version>2.5.3</version>
                <executions>
                    <execution>
                        <id>build-servidor</id>
                        <configuration>
                            <appendAssemblyId>false</appendAssemblyId>
                            <archive>
                                <manifest>
                                    <mainClass>br.com.escritordesoftware.'.strtolower($this->software->getNome()).'.main.Main</mainClass>
                                    <addClasspath>true</addClasspath>
                                </manifest>
                                <addMavenDescriptor>false</addMavenDescriptor>
                            </archive>
                            <descriptorRefs>
                                <descriptorRef>jar-with-dependencies</descriptorRef>
                            </descriptorRefs>
                            <finalName>${project.artifactId}</finalName>
                        </configuration>
                        <phase>package</phase>
                        <goals>
                            <goal>single</goal>
                        </goals>
                    </execution>
                </executions>
            </plugin>
        </plugins>
    </build>
    <organization>
        <name>Escritor de Software</name>
        <url>https://escritordesoftware.com.br</url>
    </organization>
</project>';
        return $codigo;
    }
    
}

?>