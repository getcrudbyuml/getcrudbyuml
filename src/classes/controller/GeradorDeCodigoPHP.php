<?php

/**
 * 
 * @author jefferson
 *
 */
class GeradorDeCodigoPHP extends GeradorDeCodigo
{

    /**
     * retorna um array de objetos do tipo GeradorDeCodigoPHP
     * Cada estrutura representa um arquivo de uma classe do software em questao.
     * 
     * @param Software $software
     * @return multitype:GeradorDeCodigoPHP |NULL
     */
    public static function geraClasses(Software $software)
    {
        $listaDeObjetos = $software->getObjetos();
        if ($listaDeObjetos) {
            foreach ($listaDeObjetos as $objeto) {
                
                $nomedosite = $software->getNome();
                $gerador = GeradorDeCodigoPHP::geraCodigoDeObjeto($objeto, $nomedosite);
                $geradores[] = $gerador;
            }
        }
        if (isset($geradores)) {
            return $geradores;
        } else {
            return null;
        }
    }

    /**
     * retorna um array de objetos do tipo GeradorDeCodigoPHP
     * Cada estrutura representa um arquivo de uma classe do software em questao.
     * 
     * @param Software $software
     * @return multitype:GeradorDeCodigoPHP |NULL
     */
    public static function geraClassesController(Software $software)
    {
        $listaDeObjetos = $software->getObjetos();
        if ($listaDeObjetos) {
            foreach ($listaDeObjetos as $objeto) {
                
                $nomedosite = $software->getNome();
                $gerador = GeradorDeCodigoPHP::geraCodigoDeController($objeto, $nomedosite);
                $geradores[] = $gerador;
            }
        }
        if (isset($geradores)) {
            return $geradores;
        } else {
            return null;
        }
    }

    public static function geraFormularios(Software $software)
    {
        $listaDeObjetos = $software->getObjetos();
        if ($listaDeObjetos) {
            foreach ($listaDeObjetos as $objeto) {
                $nomedosite = $software->getNome();
                $gerador = GeradorDeCodigoPHP::geraForm($objeto, $software);
                $geradores[] = $gerador;
            }
        }
        if (isset($geradores)) {
            return $geradores;
        } else {
            return null;
        }
    }

    /**
     * Retorna uma estrutura que representa o codigo e o caminho de cada
     * Objeto responsÃ¡vel por insersao de objetos no banco de dados.
     * 
     * @param Software $software
     * @return GeradorDeCodigoPHP|NULL
     */
    public static function geraDaos(Software $software)
    {
        $listaDeObjetos = $software->getObjetos();
        if ($listaDeObjetos) {
            foreach ($listaDeObjetos as $objeto) {
                
                // Gera o codigo de cada objeto
                // Gera o nome do arquivo
                $nomedosite = $software->getNome();
                
                // instancia no geradorDePHP
                // Armazena em Um vetor.
                $gerador = GeradorDeCodigoPHP::geraCodigoDeObjetoDAO($objeto, $nomedosite);
                
                $geradores[] = $gerador;
            }
        }
        if (isset($geradores)) {
            return $geradores;
        } else {
            return null;
        }
    }

    public static function geraClasseDao(Software $software)
    {
        $nomeDoSite = $software->getNome();
        
        $codigo = '<?php


class DAO {
	const ARQUIVO_CONFIGURACAO = "../' . strtolower($software->getNome()) . '_bd.ini";
	
	protected $conexao;
	private $tipoDeConexao;
	private $sgdb;

	public function getSgdb(){
		return $this->sgdb;
	}
	public function DAO(PDO $conexao = null) {
		if ($conexao != null) {
			$this->conexao = $conexao;
		} else {
			
			$this->fazerConexao ();
		}
	}
	public function getEntidadeUsuarios(){
		return $this->entidadeUsuarios;
	}
	
	
	public function fazerConexao() {
		$config = parse_ini_file ( self::ARQUIVO_CONFIGURACAO );
		
		$bd [\'sgdb\'] = $config [\'sgdb\'];
		$bd [\'bd_nome\'] = $config [\'bd_nome\'];
		$bd [\'host\'] = $config [\'host\'];
		$bd [\'porta\'] = $config [\'porta\'];
		$bd [\'usuario\'] = $config [\'usuario\'];
		$bd [\'senha\'] = $config [\'senha\'];

		if ($bd [\'sgdb\'] == "postgres") {
			$this->conexao = new PDO ( \'pgsql:host=\' . $bd [\'host\'] . \' dbname=\' . $bd [\'bd_nome\'] . \' user=\' . $bd [\'usuario\'] . \' password=\' . $bd [\'senha\'] );
		} else if ($bd [\'sgdb\'] == "mssql") {
			$this->conexao = new PDO ( \'dblib:host=\' . $bd [\'host\'] . \';dbname=\' . $bd [\'bd_nome\'], $bd [\'usuario\'], $bd [\'senha\'] );
			
		}else if($bd[\'sgdb\'] == "mysql"){
			$this->conexao = $PDO = new PDO( \'mysql:host=\' . $bd [\'host\'] . \';dbname=\' .  $bd [\'bd_nome\'], $bd [\'usuario\'], $bd [\'senha\']);
		}else if($bd[\'sgdb\']== "sqlite"){
			$this->conexao = new PDO(\'sqlite:\'.$bd [\'bd_nome\']);
		}
		$this->sgdb = $bd[\'sgdb\'];
	}
	public function setConexao($conexao) {
		$this->conexao = $conexao;
	}
	public function getConexao() {
		return $this->conexao;
	}
	public function fechaConexao() {
		$this->conexao = null;
	}
	public function getTipoDeConexao() {
		return $this->tipoDeConexao;
	}
	public function setTipoDeConexao($tipo) {
		$this->tipoDeConexao = $tipo;
	}
}

?>
		';
        
        $gerador = new GeradorDeCodigoPHP();
        $gerador->codigo = $codigo;
        $gerador->caminho = 'sistemasphp/' . $nomeDoSite . '/src/classes/dao/DAO.php';
        return $gerador;
    }

    /**
     *
     * Gera cÃ³digos das classes do pacote DAO
     * 
     * @param Objeto $objeto
     * @param String $nomeDoSite
     * @return GeradorDeCodigoPHP
     */
    public static function geraCodigoDeObjetoDAO(Objeto $objeto, $nomeDoSite)
    {
        $geradorDeCodigo = new GeradorDeCodigoPHP();
        $nomeDoObjeto = strtolower($objeto->getNome());
        $nomeDoObjetoMA = strtoupper(substr($objeto->getNome(), 0, 1)) . substr($objeto->getNome(), 1, 100);
        $nomeDoObjetoDAO = strtoupper(substr($objeto->getNome(), 0, 1)) . substr($objeto->getNome(), 1, 100) . 'DAO';
        
        $codigo = '<?php
		
/**
 * Classe feita para manipulação do objeto ' . $nomeDoObjetoMA . '
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte
 *
 *
 */
class ' . $nomeDoObjetoDAO . ' extends DAO {
	
	
	public function inserir(' . $nomeDoObjetoMA . ' $' . $nomeDoObjeto . '){
		
		$sql = "INSERT INTO ' . $nomeDoObjeto . '(';
        $i = 0;
        foreach ($objeto->getAtributos() as $atributo) {
            $i ++;
            if ($atributo->getIndice() == 'primary_key') {
                continue;
            }
            $codigo .= $atributo->getNome();
            if ($i != count($objeto->getAtributos())) {
                $codigo .= ', ';
            }
        }
        $codigo .= ')
				VALUES(';
        $i = 0;
        foreach ($objeto->getAtributos() as $atributo) {
            $i ++;
            if ($atributo->getIndice() == 'primary_key') {
                continue;
            }
            $codigo .= ':' . $atributo->getNome();
            if ($i != count($objeto->getAtributos())) {
                $codigo .= ', ';
            }
        }
        
        $codigo .= ')";';
        foreach ($objeto->getAtributos() as $atributo) {
            if ($atributo->getIndice() == 'primary_key') {
                continue;
            }
            $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
            $codigo .= '
			$' . $atributo->getNome() . ' = $' . $nomeDoObjeto . '->get' . $nomeDoAtributoMA . '();';
        }
        
        $codigo .= '
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);';
        foreach ($objeto->getAtributos() as $atributo) {
            if ($atributo->getIndice() == 'primary_key') {
                continue;
            }
            $codigo .= '		
			$stmt->bindParam("' . $atributo->getNome() . '", $' . $atributo->getNome() . ', PDO::PARAM_STR);';
        }
        
        $codigo .= '
			return $stmt->execute();
		} catch(PDOException $e) {
			echo \'{"error":{"text":\'. $e->getMessage() .\'}}\';
		}
	}
	public function excluir(' . $nomeDoObjetoMA . ' $' . $nomeDoObjeto . '){
		$' . $objeto->getAtributos()[0]->getNome() . ' = $' . $nomeDoObjeto . '->get' . strtoupper(substr($objeto->getAtributos()[0]->getNome(), 0, 1)) . substr($objeto->getAtributos()[0]->getNome(), 1, 100) . '();
		$sql = "DELETE FROM ' . $nomeDoObjeto . ' WHERE ' . $objeto->getAtributos()[0]->getNome() . ' = :' . $objeto->getAtributos()[0]->getNome() . '";
		
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);
			$stmt->bindParam("' . $objeto->getAtributos()[0]->getNome() . '", $' . $objeto->getAtributos()[0]->getNome() . ', PDO::PARAM_INT);
			return $stmt->execute();
	
		} catch(PDOException $e) {
			echo \'{"error":{"text":\'. $e->getMessage() .\'}}\';
		}
	}
	public function alterar(){
		//Aqui vc escreve o codigo pra alterar ' . $nomeDoObjeto . '
	
	}
	
	public function retornaLista() {
		$lista = array ();
		$sql = "SELECT * FROM ' . $nomeDoObjeto . ' LIMIT 1000";
		$result = $this->getConexao ()->query ( $sql );
	
		foreach ( $result as $linha ) {
				
			$' . $nomeDoObjeto . ' = new ' . $nomeDoObjetoMA . "();\n";
        
        foreach ($objeto->getAtributos() as $atributo) {
            $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
            
            $codigo .= '
			$' . $nomeDoObjeto . '->set' . $nomeDoAtributoMA . '( $linha [\'' . $atributo->getNome() . '\'] );';
        }
        $codigo .= '
			$lista [] = $' . $nomeDoObjeto . ';
		}
		return $lista;
	}			
				
}';
        
        $gerador = new GeradorDeCodigoPHP();
        $gerador->codigo = $codigo;
        $gerador->caminho = 'sistemasphp/' . $nomeDoSite . '/src/classes/dao/' . $nomeDoObjetoDAO . '.php';
        return $gerador;
    }

    /**
     *
     * @param Objeto $objeto
     * @return GeradorDeCodigoPHP
     */
    public static function geraCodigoDeController(Objeto $objeto, $nomeDoSite)
    {
        $geradorDeCodigo = new GeradorDeCodigoPHP();
        $nomeDoObjeto = strtolower($objeto->getNome());
        $nomeDoObjetoMa = strtoupper(substr($objeto->getNome(), 0, 1)) . substr($objeto->getNome(), 1, 100);
        
        $codigo = '<?php	

/**
 * Classe feita para manipulação do objeto ' . $nomeDoObjetoMa . '
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class ' . $nomeDoObjetoMa . 'Controller {
	private $post;
	private $view;
	public function ' . $nomeDoObjetoMa . 'Controller(){		
		$this->view = new ' . $nomeDoObjetoMa . 'View();
		foreach($_POST as $chave => $valor){
			$this->post[$chave] = $valor;
		}
	}
	public function cadastrar() {
		$this->view->mostraFormInserir();
        if(!isset($this->post[\'enviar_' . $nomeDoObjeto . '\'])){
		    return;
		}
		if (! ( ';
        $i = 0;
        foreach ($objeto->getAtributos() as $atributo) {
            $i ++;
            if ($atributo->getIndice() == 'primary_key') {
                continue;
            }
            $codigo .= 'isset ( $this->post [\'' . $atributo->getNome() . '\'] )';
            if ($i != count($objeto->getAtributos())) {
                $codigo .= ' && ';
            }
        }
        
        $codigo .= ')) {
			echo "Incompleto";
			return;
		}
	
		$' . $nomeDoObjeto . ' = new ' . $nomeDoObjetoMa . ' ();';
        foreach ($objeto->getAtributos() as $atributo) {
            $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
            if ($atributo->getIndice() == 'primary_key') {
                continue;
            }
            $codigo .= '		
		$' . $nomeDoObjeto . '->set' . $nomeDoAtributoMA . ' ( $this->post [\'' . $atributo->getNome() . '\'] );';
        }
        
        $codigo .= '	
		$' . $nomeDoObjeto . 'Dao = new ' . $nomeDoObjetoMa . 'DAO ();
		if ($' . $nomeDoObjeto . 'Dao->inserir ( $' . $nomeDoObjeto . ' )) {
			echo "Sucesso";
		} else {
			echo "Fracasso";
		}
        echo \'<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php?pagina=' . $nomeDoObjeto . '">\';
	}
				
	public function listarJSON() {
		$' . $nomeDoObjeto . 'Dao = new ' . $nomeDoObjetoMa . 'DAO ();
		$lista = $' . $nomeDoObjeto . 'Dao->retornaLista ();
		$listagem [\'lista\'] = array ();
		foreach ( $lista as $linha ) {
			$listagem [\'lista\'] [] = array (';
        $i = 0;
        foreach ($objeto->getAtributos() as $atributo) {
            $i ++;
            $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
            $codigo .= '
					\'' . $atributo->getNome() . '\' => $linha->get' . $nomeDoAtributoMA . ' ()';
            if ($i != count($objeto->getAtributos())) {
                $codigo .= ', ';
            }
        }
        
        $codigo .= '
						
						
			);
		}
		echo json_encode ( $listagem );
	}			
	public function listar() {
		$' . $nomeDoObjeto . 'Dao = new ' . $nomeDoObjetoMa . 'DAO ();
		$lista = $' . $nomeDoObjeto . 'Dao->retornaLista ();
		echo \'<table border="1">\';';
        foreach ($objeto->getAtributos() as $atributo) {
            $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
            $codigo .= '
			echo \'<th>' . $nomeDoAtributoMA . '</th>\';';
        }
        
        $codigo .= '
		foreach ( $lista as $' . $nomeDoObjeto . ') {
			echo \'<tr>\';		
		';
        foreach ($objeto->getAtributos() as $atributo) {
            $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
            $codigo .= '
			echo \'<td>\'.$' . $nomeDoObjeto . '->get' . $nomeDoAtributoMA . ' ().\'</td>\';';
        }
        
        $codigo .= '
			echo \'</tr>\';
		}
		echo \'</table>\';
		
		
	}			
	
		';
        
        $codigo .= '
}
?>';
        
        $geradorDeCodigo->codigo = $codigo;
        $geradorDeCodigo->caminho = 'sistemasphp/' . $nomeDoSite . '/src/classes/controller/' . strtoupper(substr($objeto->getNome(), 0, 1)) . substr($objeto->getNome(), 1, 100) . 'Controller.php';
        
        return $geradorDeCodigo;
    }

    public static function geraCodigoDeObjeto(Objeto $objeto, $nomeDoSite)
    {
        $geradorDeCodigo = new GeradorDeCodigoPHP();
        $nomeDoObjeto = strtolower($objeto->getNome());
        $nomeDoObjetoMa = strtoupper(substr($objeto->getNome(), 0, 1)) . substr($objeto->getNome(), 1, 100);
        
        $codigo = '<?php
	
/**
 * Classe feita para manipulação do objeto ' . $nomeDoObjetoMa . '
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class ' . $nomeDoObjetoMa . ' {';
        if ($objeto->getAtributos()) {
            foreach ($objeto->getAtributos() as $atributo) {
                $nome = $atributo->getNome();
                $nome2 = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
                
                $codigo .= '
	private $' . $nome . ';';
            }
            
            foreach ($objeto->getAtributos() as $atributo) {
                
                $nome = strtolower($atributo->getNome());
                $nome2 = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
                $tipo = $atributo->getTipo();
                
                if ($atributo->getTipo() == 'int' || $atributo->getTipo() == 'float' || $atributo->getTipo() == 'string' || $atributo->getTipo() == 'Texto') {
                    
                    $codigo .= '
	public function set' . $nome2 . '($' . $nome . ') {';
                    $codigo .= '
		$this->' . $nome . ' = $' . $nome . ';
	}';
                } else {
                    $codigo .= '
	public function set' . $nome2 . '(' . $atributo->getTipo() . ' $' . $nome . ') {';
                    
                    $codigo .= '
		$this->' . $nome . ' = $' . $nome . ';
	}';
                } // fecha o caso contrario. o atributo sendo objeto
                
                $codigo .= '
	public function get' . $nome2 . '() {
		return $this->' . $nome . ';
	}';
            }
        }
        
        $codigo .= '
}
?>';
        
        $geradorDeCodigo->codigo = $codigo;
        $geradorDeCodigo->caminho = 'sistemasphp/' . $nomeDoSite . '/src/classes/model/' . strtoupper(substr($objeto->getNome(), 0, 1)) . substr($objeto->getNome(), 1, 100) . '.php';
        
        return $geradorDeCodigo;
    }

    public function geraBancoSqlite(Software $software)
    {
        $bdNome = 'sistemasphp/' . $software->getNome() . '/' . strtolower($software->getNome()) . '.db';
        $pdo = new PDO('sqlite:' . $bdNome);
        $this->codigo = '';
        foreach ($software->getObjetos() as $objeto) {
            $this->codigo .= 'CREATE TABLE `' . strtolower($objeto->getNome());
            $this->codigo .= "` (\n";
            $i = 0;
            foreach ($objeto->getAtributos() as $atributo) {
                $i ++;
                if ($atributo->getIndice() == 'primary_key') {
                    $this->codigo .= '`' . strtolower($atributo->getNome()) . '`	INTEGER PRIMARY KEY AUTOINCREMENT';
                } else {
                    $this->codigo .= '`' . strtolower($atributo->getNome()) . '`	TEXT';
                }
                if ($i == count($objeto->getAtributos())) {
                    $this->codigo .= "\n";
                    continue;
                }
                $this->codigo .= ",\n";
            }
            $this->codigo .= ");\n";
        }
        $pdo->exec($this->codigo);
        $this->caminho = 'sistemasphp/' . $software->getNome() . '/' . strtolower($software->getNome()) . '_banco.sql';
    }

    public function geraINI(Software $software)
    {
        $this->codigo = ';configurações do banco de dados. 
;Banco de regras de negócio do sistema. 

sgdb = sqlite
host = localhost
porta = 5432 
bd_nome = ../' . strtolower($software->getNome()) . '.db
usuario = root
senha = 123
';
        
        $this->caminho = "sistemasphp/" . $software->getNome() . '/' . strtolower($software->getNome() . '_bd.ini');
    }

    public function geraIndex(Software $software)
    {
        $this->caminho = "sistemasphp/" . $software->getNome() . '/src/index.php';
        $this->codigo = '';
        if (! count($software->getObjetos())) {
            return;
        }
        $this->codigo = '<?php

function __autoload($classe) {
				
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

?>
<!DOCTYPE html>
<html>
  	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>' . $software->getNome() . '</title>
	</head>
  	<body>
		<div id="topo">
			<h1>' . $software->getNome() . '</h1>
		</div>
		<div id="menu">
			<ul>
				<li><a href="index.php">Inicio</a></li>';
        foreach ($software->getObjetos() as $objeto) {
            
            $this->codigo .= '
				<li><a href="?pagina=' . strtolower($objeto->getNome()) . '">' . $objeto->getNome() . '</a></li>';
        }
        
        $this->codigo .= '
		
			</ul>
		</div>
		<div id="corpo">
			<div id="esquerda">
			<?php
				if(isset($_GET[\'pagina\'])){
					switch ($_GET[\'pagina\']){';
        
        foreach ($software->getObjetos() as $objeto) {
            $this->codigo .= '
						case \'' . strtolower($objeto->getNome()) . '\':
							$controller = new ' . $objeto->getNome() . 'Controller();	
							break;';
        }
        
        $this->codigo .= '
						default:
							$controller = new ' . $software->getObjetos()[0]->getNome() . 'Controller();				
							break;
					}
				}else{
					$controller = new ' . $software->getObjetos()[0]->getNome() . 'Controller();
				}
				
				$controller->listar();
			?>
			</div>
					
			<div id="direita">
			<h1>Listagem</h1>
			<?php

				 $controller->cadastrar();

			?>
						
			</div>		
			
		</div>
		<div id="footer">
			<p>Base do site</p>
		</div>		
	</body>
</html>';
    }

    public function geraStyle(Software $software)
    {
        $this->caminho = "sistemasphp/" . $software->getNome() . '/src/css/style.css';
        $this->codigo = "/*Esse Ã© um arquivo css*/
body{
	background-color:#5DD0C0;	
	font:Arial, Helvetica, sans-serif;
	color:#FFF;
}
#topo{
	width: 1000px;
	height:223px;
	margin: 0px auto;
	padding: 0px 0px 0px 0px;		
}		
#menu{
	background-color:#00685A;
	width: 1000px;
	height:100px;
	margin: 0px auto;
	padding: 0px 0px 0px 0px;		
}
#menu ul
{
	list-style: none;
}		
#menu li
{
	display: inline-block;
	margin-top:5px;
	width:200px;
	height:30px;
}
#menu a{
	font-size:24px;	
}			
#corpo{

	background-color:#00A08A;
	width: 1000px;
	height:1000px;
	margin: 0px auto;
	padding: 0px 0px 0px 0px;
}
#footer{
	background-color:#00A08A;
	width: 1000px;
	height:200px;
	margin: 0px auto;
	padding: 0px 0px 0px 0px;
}				
#esquerda{
	padding-left:10px;
	padding-right:10px;
	margin-left:20px;
	margin-top:40px;
	width:440px;
	float:left;
	background-color:#00685A;
}
#esquerda .classe {
	background-color:#00A08A;
}
#esquerda .classe li{
	list-style: none;
}
#esquerda .classe h1{
	background-color:#1E786C;
}
#direita{
	padding-left:10px;
	padding-right:10px;
	margin-left:20px;
	margin-top:40px;
	width:440px;
	float:left;
	background-color:#00685A;
}
a{
	color:#FFF;	
}
fieldset{
	border:none;	
}
fieldset legend{
	font-size:30px;
}
label{
	font-size:30px;
	display: block;
}
input{
	margin-top:5px;
	margin-left:30px;
	border:none;
	width:300px;	
	height:30px;
	display: block;
	color: #00685A;
	font-size: 13px;
}
select{
	margin-top:5px;
	margin-left:30px;
	width:300px;	
	height:30px;
	border:none;	
	color: #00685A;
	font-size: 13px;
}
#topo img{
	margin-left:200px;
	margin-top:30px;
}			
				
";
    }

    public static function geraForm(Objeto $objeto, Software $software)
    {
        $nomeDoObjeto = strtolower($objeto->getNome());
        $nomeDoObjetoMa = strtoupper(substr($objeto->getNome(), 0, 1)) . substr($objeto->getNome(), 1, 100);
        
        $nomeDoSite = $software->getNome();
        $codigo = '<?php
				
/**
 * Classe de visao para ' . $nomeDoObjetoMa . '
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */				
class ' . $nomeDoObjetoMa . 'View {
	public function mostraFormInserir() {	
		echo \'<form action="" method="post">
					<fieldset>
						<legend>
							Adicionar ' . $nomeDoObjetoMa . '
						</legend>';
        
        $atributos = $objeto->getAtributos();
        
        foreach ($atributos as $atributo) {
            $variavel = $atributo->getNome();
            $tipo = $atributo->getTipo();
            
            $indice = $atributo->getIndice();
            if ($atributo->getIndice() == 'primary_key') {
                continue;
            }
            $codigo .= '
						<label for="' . $variavel . '">' . $variavel . ':</label>' . '
						<input type="text" name="' . $variavel . '" id="' . $variavel . '" />';
        }
        
        $codigo .= '
						<input type="submit" name="enviar_' . $nomeDoObjeto . '" value="Cadastrar">
					</fieldset>
				</form>\';
	}	
}';
        $gerador = new GeradorDeCodigoPHP();
        $gerador->caminho = 'sistemasphp/' . $nomeDoSite . '/src/classes/view/' . $nomeDoObjetoMa . 'View.php';
        $gerador->codigo = $codigo;
        return $gerador;
    }
}

?>