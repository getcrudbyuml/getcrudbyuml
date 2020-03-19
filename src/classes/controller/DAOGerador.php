<?php


class DAOGerador {
    private $software;
    private $listaDeArquivos;
    
    
    public function getListaDeArquivos(){
        return $this->listaDeArquivos;
    }
    public function __construct(Software $software){
        $this->software = $software;
    }
    
    public function gerarCodigo(){
        $this->geraCodigoPHP();
        $this->geraCodigoJava();
        
    }
    private function geraCodigoJava(){
        
        
        
        $path = 'sistemas/'.$this->software->getNome().'/java/'.$this->software->getNome().'/src/main/java';
        
        $codigo = $this->geraDAOJava();
        $caminho = $path.'/br/com/escritordesoftware/'.strtolower($this->software->getNome()).'/dao/DAO.java';
        $this->listaDeArquivos[$caminho] = $codigo;
        
        foreach($this->software->getObjetos() as $objeto){
            $codigo = $this->geraDAOsJava($objeto, $this->software);
            $caminho = $path.'/br/com/escritordesoftware/'.strtolower($this->software->getNome()).'/dao/' . ucfirst($objeto->getNome()) . 'DAO.java';
            $this->listaDeArquivos[$caminho] = $codigo;
        }
    }
    private function geraCodigoPHP(){
            $path = 'sistemas/'.$this->software->getNome().'/php/src';
            $caminho = $path.'/classes/dao/DAO.php';
            $codigo = $this->geraDAOPHP();
            $this->listaDeArquivos[$caminho] = $codigo;
            
            foreach($this->software->getObjetos() as $objeto){
                $codigo = $this->geraDAOsPHP($objeto, $this->software);
                $caminho = $path.'/classes/dao/' . strtoupper(substr($objeto->getNome(), 0, 1)) . substr($objeto->getNome(), 1, 100) . 'DAO.php';
                $this->listaDeArquivos[$caminho] = $codigo;
            }
        }
        private function geraDAOJAVA(){ 
            $codigo = '';
            $codigo .= '
package br.com.escritordesoftware.'.strtolower($this->software->getNome()).'.dao;


import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.Properties;

/**
 * Faz conexão com banco de dados e gerencia persistências. 
 * @author Jefferson Uchôa Ponte
 *
 */
public class DAO {


	/**
	 * Sistema gerenciador de banco de dados. 
	 */
	private String sgdb;

	/**
	 * Conexão com banco. 
	 */
	private Connection conexao;


	/**
	 * Constroi objeto DAO com conexão com banco de dados.
	 */
	public DAO() {
		fazerConexao();
	}

	/**
	 * Constroi objeto DAO com conexão com banco de dados.
	 */
	public DAO(Connection conexao) {
		this.conexao = conexao;
	}


	/**
	 * Faz uma conexão com banco de dados de acordo com as informações do arquivo de configuração. 
	 */
	public void fazerConexao() {
		this.conexao = null;
		try {
			Properties config = new Properties();
			FileInputStream file;
			file = new FileInputStream(ARQUIVO_CONFIGURACAO);
			config.load(file);
			String sgdb, host, porta, bdNome, usuario, senha;

			sgdb = config.getProperty("sgdb");
			host = config.getProperty("host");
			porta = config.getProperty("porta");
			bdNome = config.getProperty("bd_nome");
			usuario = config.getProperty("usuario");
			senha = config.getProperty("senha");
			
			file.close();
			if (sgdb.equals("postgres")) {
				Class.forName(DRIVER_POSTGRES);
				this.conexao = DriverManager.getConnection(JDBC_BANCO_POSTGRES+ "//" + host + "/" + bdNome, usuario, senha);
				
			} else if (sgdb.equals("sqlite")) {
				Class.forName(DRIVER_SQLITE);
				this.conexao = DriverManager.getConnection(JDBC_BANCO_SQLITE+bdNome);
			} else if (sgdb.equals("mysql")) {
				Class.forName(DRIVER_MYSQL);
				this.conexao = DriverManager.getConnection(JDBC_BANCO_MYSQL + "//" + host +":"+ porta + "/" + bdNome, usuario, senha);
			}

		} catch (ClassNotFoundException e1) {
			e1.printStackTrace();
		} catch (SQLException e) {
			e.printStackTrace();
		} catch (FileNotFoundException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}

	/**
	 * Retorna a conexão com banco de dados. 
	 * @return
	 */
	public Connection getConexao() {
		return conexao;
	}
	/**
	 * Atribui a conexão com banco de dados. 
	 * @param conexao
	 */
	public void setConexao(Connection conexao) {
		this.conexao = conexao;
	}



	/**
	 * @return the sgdb
	 */
	public String getSgdb() {
		return sgdb;
	}

	/**
	 * @param sgdb
	 */
	public void setSgdb(String sgdb) {
		this.sgdb = sgdb;
	}

	/**
	 * Arquivo de configuração do banco de dados. 
	 */
	public static final String ARQUIVO_CONFIGURACAO = "../../'. strtolower($this->software->getNome()) . '_bd.ini";

	/**
	 * Drive jdbc para Sqlite. 
	 */
	public static final String DRIVER_SQLITE = "org.sqlite.JDBC";
	/**
	 * Banco de dados squlite
	 */
	
	public static final String JDBC_BANCO_SQLITE = "jdbc:sqlite:";

	/**
	 * JDBC para postgres. 
	 */
	public static final String JDBC_BANCO_POSTGRES = "jdbc:postgresql:";
	/**
	 * Driver JDBC postgres
	 */
	public static final String DRIVER_POSTGRES = "org.postgresql.Driver";
	/**
	 * JDBC Mysql
	 */
	public static final String JDBC_BANCO_MYSQL = "jdbc:mysql:";
	/**
	 * Driver JDBC Mysql
	 */
	public static final String DRIVER_MYSQL = "com.mysql.jdbc.Driver";

}';
            return $codigo;
        }
        private function geraDAOPHP(){            
            $codigo = '<?php
                
                
class DAO {
	const ARQUIVO_CONFIGURACAO = "../../' . strtolower($this->software->getNome()) . '_bd.ini";
	    
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
		$bd = array();
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
			$this->conexao = new PDO( \'mysql:host=\' . $bd [\'host\'] . \';dbname=\' .  $bd [\'bd_nome\'], $bd [\'usuario\'], $bd [\'senha\']);
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
            return $codigo;
            
        }
        private function geraDAOsPHP(Objeto $objeto)
        {
            $codigo = '';
            $nomeDoObjeto = lcfirst($objeto->getNome());
            $nomeDoObjetoMA = ucfirst($objeto->getNome());
            
            $atributosComuns = array();
            $atributosNN = array();
            $atributosObjetos = array();
            foreach ($objeto->getAtributos() as $atributo) {
                if($atributo->tipoListado()){
                    $atributosComuns[] = $atributo;
                }else if($atributo->isArrayNN()){
                    $atributosNN[] = $atributo;
                }else if($atributo->isObjeto()){
                    $atributosObjetos[] = $atributo;
                }
            }
            
            
            $codigo .= '<?php
                
/**
 * Classe feita para manipulação do objeto ' . ucfirst($objeto->getNome()) . '
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte
 *
 *
 */
class ' . ucfirst($objeto->getNome()) . 'DAO extends DAO {
    
    
    public function atualizar('.ucfirst($objeto->getNome()).' $'.lcfirst($objeto->getNome()).')
    {';
            $atributoPrimary = null;
            foreach($objeto->getAtributos() as $atributo){
                if($atributo->isPrimary()){
                    $atributoPrimary = $atributo;
                    break;
                }
            }
            if($atributoPrimary != null){
                $codigo .= '
        $id = $'.lcfirst($objeto->getNome()).'->get'.ucfirst ($atributoPrimary->getNome()).'();';
                
            
            }
            $codigo .= '
        
        
        $sql = "UPDATE '.$nomeDoObjeto.'
                SET
                ';
            $listaAtributo = array();
            foreach ($atributosComuns as $atributo) {
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    continue;
                }
                if(substr($atributo->getTipo(), 0, 6) == 'Array '){
                    continue;
                }
                $listaAtributo[] = $atributo;
            }
            $i = 0;
            foreach ($listaAtributo as $atributo) {
                $i ++;
                $codigo .= $atributo->getNome().' = :'.$atributo->getNome();
                if ($i != count($listaAtributo)) {
                    $codigo .= ',
                ';
                }
            }
            if($atributoPrimary != null){
                $codigo .= '
                WHERE '.$nomeDoObjeto.'.id = :id;';
            }
            $codigo .= '";';
            
            
            foreach ($listaAtributo as $atributo) {
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) 
                {
                    continue;
                }
                
                $codigo .= '
			$' . lcfirst($atributo->getNome()) . ' = $' . $nomeDoObjeto . '->get' . ucfirst($atributo->getNome()) . '();';
            }
            $codigo .= '
                
        try {
                
            $stmt = $this->getConexao()->prepare($sql);';
            foreach ($atributosComuns as $atributo) {
                if(substr($atributo->getTipo(), 0, 6) == 'Array '){
                    continue;
                }
                $codigo .= '
			$stmt->bindParam("' . $atributo->getNome() . '", $' . $atributo->getNome() . ', PDO::PARAM_STR);';
            }
            
            $codigo .= '
                
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
                
    }
                
	public function inserir(' . ucfirst($objeto->getNome()) . ' $' . lcfirst($objeto->getNome()) . '){
	    
		$sql = "INSERT INTO ' . $objeto->getNomeSnakeCase(). '(';
            $i = 0;
            $nAtributosCCSemPk = 0;
            foreach ($atributosComuns as $atributo) {
                $i ++;
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    continue;
                }
                $nAtributosCCSemPk++;
                $codigo .= $atributo->getNomeSnakeCase();
                if ($i != count($atributosComuns)) {
                    $codigo .= ', ';
                }
            }
            $i = 0;
            foreach ($atributosObjetos as $atributo) {
                $i ++;
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    continue;
                }
                if($nAtributosCCSemPk != 0 && $i == 1){
                    $codigo .= ', ';
                }
                $codigo .= 'id_'.$atributo->getTipoSnakeCase().'_'.$atributo->getNomeSnakeCase();
                if ($i != count($atributosObjetos)) {
                    $codigo .= ', ';
                }
            }
            $codigo .= ')
				VALUES(';
            $i = 0;
            foreach ($atributosComuns as $atributo) {
                $i ++;
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    continue;
                }
                $codigo .= ':' . $atributo->getNome();
                if ($i != count($atributosComuns)) {
                    $codigo .= ', ';
                }
            }
            $i = 0;
            foreach ($atributosObjetos as $atributo) {
                $i ++;
                if($nAtributosCCSemPk != 0 && $i == 1){
                    $codigo .= ', ';
                }
                $codigo .= ':' . $atributo->getNome();
                if ($i != count($atributosObjetos)) {
                    $codigo .= ', ';
                }
            }
            
            $codigo .= ')";';
            foreach ($atributosComuns as $atributo) {
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    continue;
                }
                $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
                $codigo .= '
			$' . $atributo->getNome() . ' = $' . $nomeDoObjeto . '->get' . $nomeDoAtributoMA . '();';
            }
            foreach ($atributosObjetos as $atributo) {
                $codigo .= '
			$' . $atributo->getNome() . ' = $' . $nomeDoObjeto . '->get' . ucfirst($atributo->getNome()) . '()->getId();';
            }
            $codigo .= '
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);';
            foreach ($atributosComuns as $atributo) {
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    continue;
                }
                if($atributo->getTipo() == Atributo::TIPO_INT){
                    
                    $codigo .= '
			$stmt->bindParam("' . $atributo->getNome() . '", $' . $atributo->getNome() . ', PDO::PARAM_INT);';
                }else if($atributo->getTipo() == Atributo::TIPO_FLOAT){
                    $codigo .= '
			$stmt->bindParam("' . $atributo->getNome() . '", $' . $atributo->getNome() . ', PDO::PARAM_INT);';
                }else if($atributo->getTipo() == Atributo::TIPO_STRING){
                    $codigo .= '
			$stmt->bindParam("' . $atributo->getNome() . '", $' . $atributo->getNome() . ', PDO::PARAM_STR);';
                }
            }
            foreach ($atributosObjetos as $atributo) {
                
                $codigo .= '
			$stmt->bindParam("' . $atributo->getNome() . '", $' . $atributo->getNome() . ', PDO::PARAM_INT);';
                
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
			    
			    
	public function retornaLista() {
		$lista = array ();
		$sql = "';
            
            $sqlGerador = new SQLGerador($this->software);
            $codigo .= $sqlGerador->getSQLSelect($objeto);
            
            
            $codigo .= '
                 LIMIT 1000";
		$result = $this->getConexao ()->query ( $sql );
                
		foreach ( $result as $linha ) {
                
			$' . lcfirst($objeto->getNome()) . ' = new ' . ucfirst($objeto->getNome()) . '();
        ';
            
            foreach ($atributosComuns as $atributo) {
                $codigo .= '
			$' . lcfirst($objeto->getNomeSnakeCase()). '->set' . ucfirst($atributo->getNome()) . '( $linha [\'' . $atributo->getNomeSnakeCase() . '\'] );';
            }
            foreach($atributosObjetos as $atributoObjeto){
                
                foreach($this->software->getObjetos() as $objeto2){
                    if($objeto2->getNome() == $atributoObjeto->getTipo()){
                        foreach($objeto2->getAtributos() as $atributo3){
                            if($atributo3->getIndice() == Atributo::INDICE_PRIMARY){
                                $codigo .= '
			$' . $nomeDoObjeto . '->get' . ucfirst($atributoObjeto->getNome()) . '()->set'.ucfirst($atributo3->getNome()).'( $linha [\'' . $atributo3->getNomeSnakeCase().'_'.$atributoObjeto->getTipoSnakeCase().'_'.$atributoObjeto->getNomeSnakeCase() . '\'] );';
                            }
                            else if($atributo3->tipoListado())
                            {
                                $codigo .= '
			$' . $nomeDoObjeto . '->get' . ucfirst($atributoObjeto->getNome()) . '()->set'.ucfirst($atributo3->getNome()).'( $linha [\'' . $atributo3->getNomeSnakeCase().'_'.$atributoObjeto->getTipoSnakeCase().'_'.$atributoObjeto->getNomeSnakeCase() . '\'] );';
                            }
                            
                        }
                        break;
                    }
                }
                
            }
            $codigo .= '
			$lista [] = $' . $nomeDoObjeto . ';
		}
		return $lista;
	}';
            
            foreach ($atributosComuns as $atributo) {
                
                $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
                $id = $atributo->getNome();
                
                $codigo .= '
                    
    public function pesquisaPor'.$nomeDoAtributoMA.'(' . $nomeDoObjetoMA . ' $' . $nomeDoObjeto . ') {
        $lista = array();
	    $'.$id.' = $'.$nomeDoObjeto.'->get'.$nomeDoAtributoMA.'();';
                $codigo .= '
	    $sql = "SELECT ';
                $i = 0;
                foreach($atributosComuns as $atributoComum){
                    
                    $i++;
                    $codigo .= '
                '.strtolower($objeto->getNome().'.'.$atributoComum->getNome()).'';
                    
                    if($i != count($atributosComuns))
                    {
                        $codigo .= ', ';
                    }
                }
                
                foreach($atributosObjetos as $atributoObjeto){
                    
                    foreach($this->software->getObjetos() as $objeto2){
                        if($objeto2->getNome() == $atributoObjeto->getTipo()){
                            $i = 0;
                            foreach($objeto2->getAtributos() as $atributo3){
                                $i++;
                                if(count($atributosComuns) != 0 && $i == 1){
                                    $codigo .= ',';
                                }
                                if($atributo3->getIndice() == Atributo::INDICE_PRIMARY){
                                    
                                    $codigo .= '
                '.strtolower($objeto->getNome().'.'.$atributo3->getNome().'_'.$atributoObjeto->getTipo().'_'.$atributoObjeto->getNome());
                                }else{
                                    $codigo .= '
                '.strtolower($atributoObjeto->getTipo().'.'.$atributo3->getNome().' as '.$atributo3->getNome().'_'.$atributoObjeto->getTipo().'_'.$atributoObjeto->getNome());
                                }
                                if($i != count($objeto2->getAtributos()))
                                {
                                    $codigo .= ', ';
                                }
                                
                            }
                            break;
                        }
                    }
                    
                }
                $codigo .= '
                FROM ' . $nomeDoObjeto;
                foreach($atributosObjetos as $atributoObjeto){
                    
                    foreach($this->software->getObjetos() as $objeto2){
                        if($objeto2->getNome() == $atributoObjeto->getTipo()){
                            foreach($objeto2->getAtributos() as $atributo3){
                                if($atributo3->getIndice() == Atributo::INDICE_PRIMARY){
                                    $codigo .= '
                INNER JOIN '.strtolower($atributoObjeto->getTipo()).'
                ON '.strtolower($atributoObjeto->getTipo()).'.'.$atributo3->getNome().' = '.$nomeDoObjeto.'.'.strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome());
                                    break;
                                }
                                
                            }
                            break;
                        }
                    }
                    
                }
                
                if($atributo->getTipo() == Atributo::TIPO_STRING 
                    || $atributo->getTipo() == Atributo::TIPO_DATE 
                    || $atributo->getTipo() == Atributo::TIPO_DATE_TIME
                    ){
                    $codigo .=  '
                WHERE '.strtolower($objeto->getNome()).'.'.$id.' like \'%$'.$id.'%\'";';
                    
                }else
                {
                    $codigo .= '
                WHERE '.strtolower($objeto->getNome()).'.'.$id.' = $'.$id.'";';
                
                }
                
                $codigo .= '
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {
            $'.$nomeDoObjeto.' = new '.ucfirst($nomeDoObjeto).'();';
                foreach ($atributosComuns as $atributo2) {
                    
                    $nomeDoAtributoMA = strtoupper(substr($atributo2->getNome(), 0, 1)) . substr($atributo2->getNome(), 1, 100);
                    $codigo .= '
	        $'.$nomeDoObjeto.'->set'.$nomeDoAtributoMA.'( $linha [\''.$atributo2->getNome().'\'] );';
                    
                }
                foreach($atributosObjetos as $atributoObjeto){
                    
                    foreach($this->software->getObjetos() as $objeto2){
                        if($objeto2->getNome() == $atributoObjeto->getTipo()){
                            foreach($objeto2->getAtributos() as $atributo3){
                                if($atributo3->getIndice() == Atributo::INDICE_PRIMARY){
                                    $codigo .= '
			$' . $nomeDoObjeto . '->get' . ucfirst($atributoObjeto->getNome()) . '()->set'.ucfirst($atributo3->getNome()).'( $linha [\'' . strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome()) . '\'] );';
                                }
                                else
                                {
                                    $codigo .= '
			$' . $nomeDoObjeto . '->get' . ucfirst($atributoObjeto->getNome()) . '()->set'.ucfirst($atributo3->getNome()).'( $linha [\'' . strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome()) . '\'] );';
                                }
                                
                            }
                            break;
                        }
                    }
                    
                }
                $codigo .= '
			$lista [] = $' . $nomeDoObjeto . ';
		}
		return $lista;
    }';
                
                
            }
            
            foreach ($atributosComuns as $atributo) {
                
                $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
                $id = $atributo->getNome();
                
                $codigo .= '
                    
    public function preenchePor'.$nomeDoAtributoMA.'(' . $nomeDoObjetoMA . ' $' . $nomeDoObjeto . ') {

	    $'.$id.' = $'.$nomeDoObjeto.'->get'.$nomeDoAtributoMA.'();';
                $codigo .= '
	    $sql = "SELECT ';
                $i = 0;
                foreach($atributosComuns as $atributoComum){
                    
                    $i++;
                    $codigo .= '
                '.strtolower($objeto->getNome().'.'.$atributoComum->getNome()).'';
                    
                    if($i != count($atributosComuns))
                    {
                        $codigo .= ', ';
                    }
                }
                
                foreach($atributosObjetos as $atributoObjeto){
                    
                    foreach($this->software->getObjetos() as $objeto2){
                        if($objeto2->getNome() == $atributoObjeto->getTipo()){
                            $i = 0;
                            foreach($objeto2->getAtributos() as $atributo3){
                                $i++;
                                if(count($atributosComuns) != 0 && $i == 1){
                                    $codigo .= ',';
                                }
                                if($atributo3->getIndice() == Atributo::INDICE_PRIMARY){
                                    
                                    $codigo .= '
                '.strtolower($objeto->getNome().'.'.$atributo3->getNome().'_'.$atributoObjeto->getTipo().'_'.$atributoObjeto->getNome());
                                }else{
                                    $codigo .= '
                '.strtolower($atributoObjeto->getTipo().'.'.$atributo3->getNome().' as '.$atributo3->getNome().'_'.$atributoObjeto->getTipo().'_'.$atributoObjeto->getNome());
                                }
                                if($i != count($objeto2->getAtributos()))
                                {
                                    $codigo .= ', ';
                                }
                                
                            }
                            break;
                        }
                    }
                    
                }
                $codigo .= '
                FROM ' . $nomeDoObjeto;
                foreach($atributosObjetos as $atributoObjeto){
                    
                    foreach($this->software->getObjetos() as $objeto2){
                        if($objeto2->getNome() == $atributoObjeto->getTipo()){
                            foreach($objeto2->getAtributos() as $atributo3){
                                if($atributo3->getIndice() == Atributo::INDICE_PRIMARY){
                                    $codigo .= '
                INNER JOIN '.strtolower($atributoObjeto->getTipo()).'
                ON '.strtolower($atributoObjeto->getTipo()).'.'.$atributo3->getNome().' = '.$nomeDoObjeto.'.'.strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome());
                                    break;
                                }
                                
                            }
                            break;
                        }
                    }
                    
                }
                
                if($atributo->getTipo() == Atributo::TIPO_STRING || $atributo->getTipo() == Atributo::TIPO_DATE || $atributo->getTipo() == Atributo::TIPO_DATE_TIME){
                    $codigo .=  '
                WHERE '.strtolower($objeto->getNome()).'.'.$id.' like \'%$'.$id.'%\'";';
                    
                }else{
                    $codigo .= '
                WHERE '.strtolower($objeto->getNome()).'.'.$id.' = $'.$id.'";';
                }
                
                $codigo .= '
	    $result = $this->getConexao ()->query ( $sql );
                    
	    foreach ( $result as $linha ) {';
                foreach ($atributosComuns as $atributo2) {
                    
                    $nomeDoAtributoMA = strtoupper(substr($atributo2->getNome(), 0, 1)) . substr($atributo2->getNome(), 1, 100);
                    $codigo .= '
	        $'.$nomeDoObjeto.'->set'.$nomeDoAtributoMA.'( $linha [\''.$atributo2->getNome().'\'] );';
                    
                }
                foreach($atributosObjetos as $atributoObjeto){
                    
                    foreach($this->software->getObjetos() as $objeto2){
                        if($objeto2->getNome() == $atributoObjeto->getTipo()){
                            foreach($objeto2->getAtributos() as $atributo3){
                                if($atributo3->getIndice() == Atributo::INDICE_PRIMARY){
                                    $codigo .= '
			$' . $nomeDoObjeto . '->get' . ucfirst($atributoObjeto->getNome()) . '()->set'.ucfirst($atributo3->getNome()).'( $linha [\'' . strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome()) . '\'] );';
                                }
                                else
                                {
                                    $codigo .= '
			$' . $nomeDoObjeto . '->get' . ucfirst($atributoObjeto->getNome()) . '()->set'.ucfirst($atributo3->getNome()).'( $linha [\'' . strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome()) . '\'] );';
                                }
                                
                            }
                            break;
                        }
                    }
                    
                }
                $codigo .= '

		}
		return $' . $nomeDoObjeto . ';
    }';
                
                
            }
            foreach($atributosNN as $atributo){
                $codigo .= '
    public function buscar'.ucfirst($atributo->getNome()).'('.ucfirst($objeto->getNome()).' $'.strtolower($objeto->getNome()).')
    {
        $id = $'.strtolower($objeto->getNome()).'->getId();
        $sql = "SELECT * FROM
                '.strtolower($objeto->getNome()).'_'.strtolower(explode(' ', $atributo->getTipo())[2]).'
                INNER JOIN '.strtolower(explode(' ', $atributo->getTipo())[2]).'
                ON  '.strtolower($objeto->getNome()).'_'.strtolower(explode(' ', $atributo->getTipo())[2]).'.id'.strtolower(explode(' ', $atributo->getTipo())[2]).' = '.strtolower(explode(' ', $atributo->getTipo())[2]).'.id
                 WHERE '.strtolower($objeto->getNome()).'_'.strtolower(explode(' ', $atributo->getTipo())[2]).'.id'.strtolower($objeto->getNome()).' = $id";
        $result = $this->getConexao ()->query ( $sql );
                     
        foreach ($result as $linha) {
            $'.strtolower(explode(' ', $atributo->getTipo())[2]).' = new '.ucfirst(explode(' ', $atributo->getTipo())[2]).'();';
                
                foreach($this->software->getObjetos() as $obj){
                    if(strtolower($obj->getNome()) == strtolower(explode(' ', $atributo->getTipo())[2]))
                    {
                        foreach($obj->getAtributos() as $atr){
                            
                            $nomeDoAtributoMA = ucfirst($atr->getNome());
                            
                            if($atr->getTipo() == Atributo::TIPO_INT || $atr->getTipo() == Atributo::TIPO_STRING || $atr->getTipo() == Atributo::TIPO_FLOAT)
                            {
                                $codigo .= '
	        $'.strtolower(explode(' ', $atributo->getTipo())[2]).'->set'.$nomeDoAtributoMA.'( $linha [\''.strtolower($atr->getNome()).'\'] );';
                            }else if(substr($atr->getTipo(), 0, 6) == 'Array '){
                                //
                                $codigo .= '
            $'.strtolower(explode(' ', $atributo->getTipo())[2]).'Dao = new '.ucfirst(explode(' ', $atributo->getTipo())[2]).'DAO($this->getConexao());
            $'.strtolower(explode(' ', $atributo->getTipo())[2]).'Dao->buscar'.ucfirst($atr->getNome()).'($'.strtolower(explode(' ', $atributo->getTipo())[2]).');';
                                //$objetoDao->buscar
                            }
                            
                        }
                        $codigo .= '';
                        break;
                    }
                }
                
                
                $codigo .= '
            $'.strtolower($objeto->getNome()).'->add'.ucfirst(explode(' ', $atributo->getTipo())[2]).'($'.strtolower(explode(' ', $atributo->getTipo())[2]).');
                
        }
        return $'.strtolower($objeto->getNome()).';
    }
            
            
	public function inserir'.ucfirst(explode(" ", $atributo->getTipo())[2]).'('. $nomeDoObjetoMA . ' $' . $nomeDoObjeto . ', '.ucfirst(explode(" ", $atributo->getTipo())[2]).' $'.strtolower(explode(" ", $atributo->getTipo())[2]).'){
        $id'.strtolower($objeto->getNome()).' =  $' . $nomeDoObjeto.'->getId();
        $id'.strtolower(explode(' ', $atributo->getTipo())[2]).' = $'.strtolower(explode(" ", $atributo->getTipo())[2]).'->getId();
		$sql = "INSERT INTO '.strtolower($objeto->getNome()).'_'.strtolower(explode(' ', $atributo->getTipo())[2]).'(';
                $codigo .= '
                    id'.strtolower($objeto->getNome()).',
                    id'.strtolower(explode(' ', $atributo->getTipo())[2]).')
				VALUES(';
                $codigo .= '
                :id'.strtolower($objeto->getNome()).',
                :id'.strtolower(explode(' ', $atributo->getTipo())[2]);
                $codigo .= ')";';
                $codigo .= '
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);';
                
                $codigo .= '
		    $stmt->bindParam("id'.strtolower($objeto->getNome()).'", $id'.strtolower($objeto->getNome()).', PDO::PARAM_INT);
            $stmt->bindParam("id'.strtolower(explode(' ', $atributo->getTipo())[2]). '", $id'.strtolower(explode(' ', $atributo->getTipo())[2]) . ', PDO::PARAM_INT);
                
';
                
                $codigo .= '
			return $stmt->execute();
		} catch(PDOException $e) {
			echo \'{"error":{"text":\'. $e->getMessage() .\'}}\';
		}
	}
                    
                    
                    
                    
	public function remover'.ucfirst(explode(" ", $atributo->getTipo())[2]).'('. $nomeDoObjetoMA . ' $' . $nomeDoObjeto . ', '.ucfirst(explode(" ", $atributo->getTipo())[2]).' $'.strtolower(explode(" ", $atributo->getTipo())[2]).'){
        $id'.strtolower($objeto->getNome()).' =  $' . $nomeDoObjeto.'->getId();
        $id'.strtolower(explode(' ', $atributo->getTipo())[2]).' = $'.strtolower(explode(" ", $atributo->getTipo())[2]).'->getId();
		$sql = "DELETE FROM  '.strtolower($objeto->getNome()).'_'.strtolower(explode(' ', $atributo->getTipo())[2]).' WHERE ';
                $codigo .= '
                    id'.strtolower($objeto->getNome()).' = :id'.strtolower($objeto->getNome()).'
                    AND
                    id'.strtolower(explode(' ', $atributo->getTipo())[2]).' = :id'.strtolower(explode(' ', $atributo->getTipo())[2]).'";';
                
                $codigo .= '
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);';
                
                $codigo .= '
		    $stmt->bindParam("id'.strtolower($objeto->getNome()).'", $id'.strtolower($objeto->getNome()).', PDO::PARAM_INT);
            $stmt->bindParam("id'.strtolower(explode(' ', $atributo->getTipo())[2]). '", $id'.strtolower(explode(' ', $atributo->getTipo())[2]) . ', PDO::PARAM_INT);
';
                
                $codigo .= '
			return $stmt->execute();
		} catch(PDOException $e) {
			echo \'{"error":{"text":\'. $e->getMessage() .\'}}\';
		}
	}
                    
                    
';
            }
            
            $codigo .= '
                
                
}';
            
            
            return $codigo;
        }
        private function geraSQLConsulta(Objeto $objeto){
            $codigo = '';
            
            $nomeDoObjeto = strtolower($objeto->getNome());
            $atributosComuns = array();
            $atributosObjetos = array();
            foreach ($objeto->getAtributos() as $atributo) {
                if(substr($atributo->getTipo(),0,6) == 'Array '){
                    continue;
                }else if($atributo->getTipo() == Atributo::TIPO_INT || $atributo->getTipo() == Atributo::TIPO_STRING || $atributo->getTipo() == Atributo::TIPO_FLOAT)
                {
                    $atributosComuns[] = $atributo;
                }else{
                    $atributosObjetos[] = $atributo;
                }
            }
            
            $codigo .= 'SELECT ';
            $i = 0;
            foreach($atributosComuns as $atributoComum){
                
                $i++;
                $codigo .= strtolower($objeto->getNome().'.'.$atributoComum->getNome()).'';
                
                if($i != count($atributosComuns))
                {
                    $codigo .= ', ';
                }
            }
            
            foreach($atributosObjetos as $atributoObjeto){
                
                foreach($this->software->getObjetos() as $objeto2){
                    if($objeto2->getNome() == $atributoObjeto->getTipo()){
                        $i = 0;
                        foreach($objeto2->getAtributos() as $atributo3){
                            $i++;
                            if(count($atributosComuns) != 0 && $i == 1){
                                $codigo .= ', ';
                            }
                            if($atributo3->getIndice() == Atributo::INDICE_PRIMARY){
                                
                                $codigo .= strtolower($objeto->getNome().'.'.$atributo3->getNome().'_'.$atributoObjeto->getTipo().'_'.$atributoObjeto->getNome()).'';
                            }else{
                                $codigo .= strtolower($atributoObjeto->getTipo().'.'.$atributo3->getNome().' as '.$atributo3->getNome().'_'.$atributoObjeto->getTipo().'_'.$atributoObjeto->getNome()).'';
                            }
                            if($i != count($objeto2->getAtributos()))
                            {
                                $codigo .= ', ';
                            }
                            
                        }
                        break;
                    }
                }
                
            }
            $codigo .= ' FROM ' . $nomeDoObjeto;
            foreach($atributosObjetos as $atributoObjeto){
                
                foreach($this->software->getObjetos() as $objeto2){
                    if($objeto2->getNome() == $atributoObjeto->getTipo()){
                        foreach($objeto2->getAtributos() as $atributo3){
                            if($atributo3->getIndice() == Atributo::INDICE_PRIMARY){
                                $codigo .= ' INNER JOIN '.strtolower($atributoObjeto->getTipo()).' ON '.strtolower($atributoObjeto->getTipo()).'.'.$atributo3->getNome().' = '.$nomeDoObjeto.'.'.strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome());
                                break;
                            }
                            
                        }
                        break;
                    }
                }
                
            }
            return $codigo;
            
        }
        private function geraDAOsJava(Objeto $objeto)
        {
            $codigo = '';
            
            $nomeDoObjeto = strtolower($objeto->getNome());
            $nomeDoObjetoMA = strtoupper(substr($objeto->getNome(), 0, 1)) . substr($objeto->getNome(), 1, 100);
            $atributosComuns = array();
            $atributosNN = array();
            $atributosObjetos = array();
            foreach ($objeto->getAtributos() as $atributo) {
                if(substr($atributo->getTipo(),0,6) == 'Array '){
                    if(explode(' ', $atributo->getTipo())[1]  == 'n:n'){
                        $atributosNN[] = $atributo;
                    }
                }else if($atributo->getTipo() == Atributo::TIPO_INT || $atributo->getTipo() == Atributo::TIPO_STRING || $atributo->getTipo() == Atributo::TIPO_FLOAT)
                {
                    $atributosComuns[] = $atributo;
                }else{
                    $atributosObjetos[] = $atributo;
                }
            }
            
            
            $codigo = '
package br.com.escritordesoftware.'.strtolower($this->software->getNome()).'.dao;

import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import br.com.escritordesoftware.'.strtolower($this->software->getNome()).'.model.*;

/**
 * Classe feita para manipulação do objeto '.ucfirst($objeto->getNome()).'
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte
 *
 *
 */
public class ' . ucfirst($objeto->getNome()) . 'DAO extends DAO{';
            
            
            $codigo .= '

    

    public boolean atualizar('.ucfirst($objeto->getNome()).' '.strtolower($objeto->getNome()).'){
		PreparedStatement ps;';
            foreach($objeto->getAtributos() as $atributo){
                if($atributo->getIndice() == Atributo::INDICE_PRIMARY){
                    $codigo .= '
            int id = '.strtolower($objeto->getNome()).'.get'.ucfirst ($atributo->getNome()).'();';
                    
                }else if($atributo->tipoListado()){
                    $codigo .= '
            '.$atributo->getTipoJava().' '.strtolower($atributo->getNome()).' = '.strtolower($objeto->getNome()).'.get'.ucfirst($atributo->getNome()).'();';                    
                }
            }
            $codigo .= '

        String sql = "UPDATE '.$nomeDoObjeto.'"
                +"SET"
                ';
            $listaAtributo = array();
            foreach ($atributosComuns as $atributo) {
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    continue;
                }
                if(substr($atributo->getTipo(), 0, 6) == 'Array '){
                    continue;
                }
                $listaAtributo[] = $atributo;
            }
            $i = 0;
            foreach ($listaAtributo as $atributo) {
                $i ++;
                $codigo .= '+"'.$atributo->getNome().' = ?';
                if ($i != count($listaAtributo)) {
                    $codigo .= ',"
                ';
                }else{
                    $codigo .= '"';
                }
            }
            $codigo .= '
                +"WHERE '.$nomeDoObjeto.'.id ="+id+";";';
            
            $codigo .= '
		try {
			ps = this.getConexao().prepareStatement(sql);';
            $i = 1;
            foreach ($listaAtributo as $atributo) {
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    continue;
                }
                $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
                if($atributo->getTipo() == Atributo::TIPO_INT){
                    $codigo .= '
                ps.setInt('.$i.', ' . $atributo->getNome() . ');';
                }else if($atributo->getTipo() == Atributo::TIPO_FLOAT){
                    $codigo .= '
                ps.setFloat('.$i.', ' . $atributo->getNome() . ');';
                }else if($atributo->getTipo() == Atributo::TIPO_STRING){
                    $codigo .= '
                ps.setString('.$i.', ' . $atributo->getNome() . ');';
                }
                
                $i++;
            }
            $codigo .= '
            	
			ps.executeUpdate();
            return true;
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
            return false;
		}			
		
		
	}
    

                ';

            
            
            $codigo .= '
                                
	public boolean inserir(' . ucfirst($objeto->getNome()). ' ' . strtolower($objeto->getNome()). '){

        String sql = "INSERT into '.strtolower($objeto->getNome()).'(';
            $i = 0;
            foreach ($atributosComuns as $atributo) {
                $i ++;
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    continue;
                }
                $codigo .= strtolower($atributo->getNome());
                if ($i != count($atributosComuns)) {
                    $codigo .= ', ';
                }
            }
            $i = 0;
            foreach ($atributosObjetos as $atributo) {
                $i ++;
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    continue;
                }
                if(count($atributosComuns) != 0 && $i == 1){
                    $codigo .= ', ';
                }
                $codigo .= 'id_'.strtolower($atributo->getTipo()).'_'.strtolower($atributo->getNome());
                if ($i != count($atributosObjetos)) {
                    $codigo .= ', ';
                }
            }
            $codigo .= ') VALUES(';
            $i = 0;
            foreach ($atributosComuns as $atributo) {
                $i ++;
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    continue;
                }
                $codigo .= '?';
                if ($i != count($atributosComuns)) {
                    $codigo .= ', ';
                }
            }
            $i = 0;
            foreach ($atributosObjetos as $atributo) {
                $i ++;
                if(count($atributosComuns) && $i == 1){
                    $codigo .= ', ';
                }
                $codigo .= '?';
                if ($i != count($atributosObjetos)) {
                    $codigo .= ', ';
                }
            }
        $codigo .= ')";';
        $codigo .= '

		try {

			PreparedStatement ps = this.getConexao().prepareStatement(sql);';
        
        $i = 0;
        foreach ($atributosComuns as $atributo) {
            
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }else{
                $i++;
            }
            if($atributo->getTipo() == Atributo::TIPO_INT){
                $codigo .= '
            ps.setInt('.$i.', '.strtolower($objeto->getNome()).'.get'.ucfirst($atributo->getNome()).'());';
            }else if($atributo->getTipo() == Atributo::TIPO_STRING){
                $codigo .= '
            ps.setString('.$i.', '.strtolower($objeto->getNome()).'.get'.ucfirst($atributo->getNome()).'());';
            }else if($atributo->getTipo() == Atributo::TIPO_FLOAT){
                $codigo .= '
            ps.setFloat('.$i.', '.strtolower($objeto->getNome()).'.get'.ucfirst($atributo->getNome()).'());';
                
            }
            
        }
        
        foreach ($atributosObjetos as $atributo) {
            $i ++;
            if(count($atributosComuns) && $i == 1){
                $codigo .= ', ';
            }
            $codigo .= '
            ps.setInt('.$i.', '.strtolower($objeto->getNome()).'.get'.ucfirst($atributo->getNome()).'().getId());';
            
        }
        $codigo .= '
			
			ps.executeUpdate();
			return true;
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			return false;
		}
    }

	public boolean excluir(' . ucfirst($objeto->getNome()). ' ' . strtolower($objeto->getNome()). '){
		String sql = "DELETE FROM ' . $nomeDoObjeto . ' WHERE ' . $objeto->getAtributos()[0]->getNome() . ' = ?";
		try{
        	PreparedStatement stmt = this.getConexao().prepareStatement(sql);
        	stmt.setInt(1, '.$nomeDoObjeto.'.get' . ucfirst($objeto->getAtributos()[0]->getNome()). '());
        	stmt.execute();
        	stmt.close();
        	return true;
    	} catch (SQLException e) {
			e.printStackTrace();
			return false;
		}
		
	}
			    
			    
	public ArrayList<'.ucfirst($objeto->getNome()).'> retornaLista() {
		ArrayList<'.ucfirst($objeto->getNome()).'>lista = new ArrayList<'.ucfirst($objeto->getNome()).'>();
		String sql = "';
        $codigo .= $this->geraSQLConsulta($objeto, $this->software);
        
		$codigo .= ' LIMIT 1000";
    		
    		PreparedStatement ps;
    		try {
    			ps = this.getConexao().prepareStatement(sql);
    			ResultSet resultSet = ps.executeQuery();
    			while(resultSet.next()){
    				' . $nomeDoObjetoMA . ' ' . $nomeDoObjeto . ' = new ' . $nomeDoObjetoMA . '();';
            foreach ($atributosComuns as $atributo) {
                $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
                if($atributo->getTipo() == Atributo::TIPO_INT){
                    $codigo .= '
                    ' . $nomeDoObjeto . '.set' . $nomeDoAtributoMA . '( resultSet.getInt("' . $atributo->getNome() . '"));';
                }else if($atributo->getTipo() == Atributo::TIPO_FLOAT){
                    $codigo .= '
                    ' . $nomeDoObjeto . '.set' . $nomeDoAtributoMA . '( resultSet.getFloat("' . $atributo->getNome() . '"));';
                }else if($atributo->getTipo() == Atributo::TIPO_STRING){
                    $codigo .= '
                    ' . $nomeDoObjeto . '.set' . $nomeDoAtributoMA . '( resultSet.getString("' . $atributo->getNome() . '"));';
                }
                
            }
            foreach($atributosObjetos as $atributoObjeto){
                
                foreach($this->software->getObjetos() as $objeto2){
                    if($objeto2->getNome() == $atributoObjeto->getTipo()){
                        foreach($objeto2->getAtributos() as $atributo3){
                            if($atributo3->getIndice() == Atributo::INDICE_PRIMARY){
                            
                                if($atributo3->getTipo() == Atributo::TIPO_INT){
                                    $codigo .= '
                    ' . $nomeDoObjeto . '.get' . ucfirst($atributoObjeto->getNome()) . '().set'.ucfirst($atributo3->getNome()).'( resultSet.getInt("' . strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome()) . '" ));';
                                }else if($atributo3->getTipo() == Atributo::TIPO_FLOAT){
                                    $codigo .= '
                    ' . $nomeDoObjeto . '.get' . ucfirst($atributoObjeto->getNome()) . '().set'.ucfirst($atributo3->getNome()).'( resultSet.getFloat("' . strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome()) . '" ));';
                                }else if($atributo3->getTipo() == Atributo::TIPO_STRING){
                                    $codigo .= '
                    ' . $nomeDoObjeto . '.get' . ucfirst($atributoObjeto->getNome()) . '().set'.ucfirst($atributo3->getNome()).'( resultSet.getString("' . strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome()) . '" ));';
                                }

                            
                            }
                            else
                            {
                                
                                if($atributo3->getTipo() == Atributo::TIPO_INT){
                                    $codigo .= '
                    ' . $nomeDoObjeto . '.get' . ucfirst($atributoObjeto->getNome()) . '().set'.ucfirst($atributo3->getNome()).'( resultSet.getInt("' . strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome()) . '" ));';
                                    
                                }else if($atributo3->getTipo() == Atributo::TIPO_FLOAT){
                                    $codigo .= '
                    ' . $nomeDoObjeto . '.get' . ucfirst($atributoObjeto->getNome()) . '().set'.ucfirst($atributo3->getNome()).'( resultSet.getFloat("' . strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome()) . '" ));';
                                }else if($atributo3->getTipo() == Atributo::TIPO_STRING){
                                    $codigo .= '
                    ' . $nomeDoObjeto . '.get' . ucfirst($atributoObjeto->getNome()) . '().set'.ucfirst($atributo3->getNome()).'( resultSet.getString("' . strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome()) . '" ));';
                                }
                                
                            }
                            
                        }
                        break;
                    }
                }
                
            }
            $codigo .= '
    				
    				
    				lista.add(' . $nomeDoObjeto . ');
    			}
                return lista;
    		} catch (SQLException e) {
    			// TODO Auto-generated catch block
    			e.printStackTrace();
                return null;
    		}

	}';
            
            foreach ($atributosComuns as $atributo) {
                
                $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
                
                
                $codigo .= '
                    
    public ArrayList<'.$nomeDoObjetoMA.'> pesquisaPor'.$nomeDoAtributoMA.'(' . $nomeDoObjetoMA . ' ' . $nomeDoObjeto . ') {
        ArrayList<'.$nomeDoObjetoMA.'>lista = new ArrayList<'.$nomeDoObjetoMA.'>();';
                
                $id = $atributo->getNome();
                $codigo .= '
	    String sql = "';
                $codigo .= $this->geraSQLConsulta($objeto, $this->software);
                $codigo .= '"';
                if($atributo->getTipo() == Atributo::TIPO_STRING){
                    $codigo .=  '
                +" WHERE '.strtolower($objeto->getNome()).'.'.$id.' like \'%?%\'';
                    
                }else if($atributo->getTipo() == Atributo::TIPO_INT || $atributo->getTipo() == Atributo::TIPO_FLOAT){
                    $codigo .= '
                +" WHERE '.strtolower($objeto->getNome()).'.'.$id.' = ?';
                }
                $codigo .= ' LIMIT 1000";
    		PreparedStatement ps;
    		try {
    			ps = this.getConexao().prepareStatement(sql);';
                
                if($atributo->getTipo() == Atributo::TIPO_INT){
                    $codigo .= '
                ps.setInt(1, '.$nomeDoObjeto.'.get'.$nomeDoAtributoMA.'());';
                    
                }else if($atributo->getTipo() == Atributo::TIPO_FLOAT){
                    $codigo .= '
                ps.setFloat(1, '.$nomeDoObjeto.'.get'.$nomeDoAtributoMA.'());';
                    
                }
                else if($atributo->getTipo() == Atributo::TIPO_STRING){
                    $codigo .= '
                ps.setString(1, '.$nomeDoObjeto.'.get'.$nomeDoAtributoMA.'());';
                    
                }
                
                
                $codigo .= '
    			ResultSet resultSet = ps.executeQuery();
    			while(resultSet.next()){
                    ' . $nomeDoObjeto . ' = new ' . $nomeDoObjetoMA . '();';

                foreach ($atributosComuns as $atributo2) {
                    
                    $nomeDoAtributoMA = strtoupper(substr($atributo2->getNome(), 0, 1)) . substr($atributo2->getNome(), 1, 100);
                    if($atributo2->getTipo() == Atributo::TIPO_INT){
                        $codigo .= '
                    '.$nomeDoObjeto.'.set'.$nomeDoAtributoMA.'( resultSet.getInt("'.$atributo2->getNome().'"));';
                    }else if($atributo2->getTipo() == Atributo::TIPO_FLOAT)
                    {
                        $codigo .= '
                    '.$nomeDoObjeto.'.set'.$nomeDoAtributoMA.'( resultSet.getFloat("'.$atributo2->getNome().'"));';
                    }
                    else if($atributo2->getTipo() == Atributo::TIPO_STRING)
                    {
                        $codigo .= '
	               '.$nomeDoObjeto.'.set'.$nomeDoAtributoMA.'( resultSet.getString("'.$atributo2->getNome().'"));';
                    }
                    
                    
                }
                foreach($atributosObjetos as $atributoObjeto){
                    
                    foreach($this->software->getObjetos() as $objeto2){
                        if($objeto2->getNome() == $atributoObjeto->getTipo()){
                            foreach($objeto2->getAtributos() as $atributo3){

                                if($atributo3->getTipo() == Atributo::TIPO_INT){
                                    $codigo .= '
                    ' . $nomeDoObjeto . '.get' . ucfirst($atributoObjeto->getNome()) . '().set'.ucfirst($atributo3->getNome()).'( resultSet.getInt("' . strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome()) . '"));';
                                    
                                }else if($atributo3->getTipo() == Atributo::TIPO_FLOAT){
                                    $codigo .= '
                    ' . $nomeDoObjeto . '.get' . ucfirst($atributoObjeto->getNome()) . '().set'.ucfirst($atributo3->getNome()).'( resultSet.getFloat("' . strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome()) . '"));';
                                    
                                }
                                else if($atributo3->getTipo() == Atributo::TIPO_STRING){
                                    $codigo .= '
                    ' . $nomeDoObjeto . '.get' . ucfirst($atributoObjeto->getNome()) . '().set'.ucfirst($atributo3->getNome()).'( resultSet.getString("' . strtolower($atributo3->getNome()).'_'.strtolower($atributoObjeto->getTipo()).'_'.strtolower($atributoObjeto->getNome()) . '"));';
                                    
                                }
                                
                                
                            }
                            break;
                        }
                    }
                    
                }
                $codigo .= '
    				
    				lista.add(' . $nomeDoObjeto . ');
    			}
                return lista;
    		} catch (SQLException e) {
    			// TODO Auto-generated catch block
    			e.printStackTrace();
                return null;
    		}

	}';
                
                
            }
            
            foreach($atributosNN as $atributo){
                $codigo .= '
    public '.ucfirst($objeto->getNome()).' buscar'.ucfirst($atributo->getNome()).'('.ucfirst($objeto->getNome()).' '.strtolower($objeto->getNome()).')
    {
        int id = '.strtolower($objeto->getNome()).'.getId();
        String sql = "SELECT ';
                
                foreach($this->software->getObjetos() as $obj){
                    if(strtolower($obj->getNome()) == strtolower(explode(' ', $atributo->getTipo())[2]))
                    {
                        $i = 0; 
                        foreach($obj->getAtributos() as $atr){
                            
                            $i++;
                            $codigo .= strtolower($atributo->getTipoDeArray().'.'.$atr->getNome().' as '. $atr->getNome().'_'.$atributo->getTipoDeArray());
                            if($i < count($obj->getAtributos())){
                                $codigo .= ', ';
                            }
                        }
                    }
                }
        $codigo .= ' FROM '.strtolower($objeto->getNome()).'_'.strtolower(explode(' ', $atributo->getTipo())[2]).' INNER JOIN '.strtolower(explode(' ', $atributo->getTipo())[2]).' ON  '.strtolower($objeto->getNome()).'_'.strtolower(explode(' ', $atributo->getTipo())[2]).'.id'.strtolower(explode(' ', $atributo->getTipo())[2]).' = '.strtolower(explode(' ', $atributo->getTipo())[2]).'.id WHERE '.strtolower($objeto->getNome()).'_'.strtolower(explode(' ', $atributo->getTipo())[2]).'.id'.strtolower($objeto->getNome()).' = "+id;';

                $codigo .= '
        PreparedStatement ps;
        try {
            ps = this.getConexao().prepareStatement(sql);
			ResultSet resultSet = ps.executeQuery();
    		while(resultSet.next()){
                '.ucfirst(explode(' ', $atributo->getTipo())[2]).' '.strtolower(explode(' ', $atributo->getTipo())[2]).' = new '.ucfirst(explode(' ', $atributo->getTipo())[2]).'();';
                foreach($this->software->getObjetos() as $obj){
                    if(strtolower($obj->getNome()) == strtolower(explode(' ', $atributo->getTipo())[2]))
                    {
                        foreach($obj->getAtributos() as $atr){
                            
                            $nomeDoAtributoMA = ucfirst($atr->getNome());
                            
                            if($atr->tipoListado())
                            {
                                
                                if($atr->getTipo() == Atributo::TIPO_INT)
                                {
                                    $codigo .= '
                '.strtolower(explode(' ', $atributo->getTipo())[2]).'.set'.$nomeDoAtributoMA.'( resultSet.getInt("'. $atr->getNome().'_'.strtolower($atributo->getTipoDeArray()).'"));';
                                }
                                else if($atr->getTipo() == Atributo::TIPO_FLOAT)
                                {
                                    $codigo .= '
                '.strtolower(explode(' ', $atributo->getTipo())[2]).'.set'.$nomeDoAtributoMA.'( resultSet.getFloat("'. $atr->getNome().'_'.strtolower($atributo->getTipoDeArray()).'"));';
                                }
                                else if($atr->getTipo() == Atributo::TIPO_STRING)
                                {
                                    
                                    $codigo .= '
                '.strtolower(explode(' ', $atributo->getTipo())[2]).'.set'.$nomeDoAtributoMA.'( resultSet.getString("'. $atr->getNome().'_'.strtolower($atributo->getTipoDeArray()).'"));';
                                
                                }
                                
                            }else if(substr($atr->getTipo(), 0, 6) == 'Array '){
                                
                                $codigo .= '
                '.ucfirst(explode(' ', $atributo->getTipo())[2]).'DAO '.strtolower(explode(' ', $atributo->getTipo())[2]).'Dao = new '.ucfirst(explode(' ', $atributo->getTipo())[2]).'DAO($this->getConexao());
                '.strtolower(explode(' ', $atributo->getTipo())[2]).'Dao.buscar'.ucfirst($atr->getNome()).'($'.strtolower(explode(' ', $atributo->getTipo())[2]).');';
                                //$objetoDao->buscar
                            }
                            
                        }
                        $codigo .= '';
                        break;
                    }
                }
                
                $codigo .= '
                '.strtolower($objeto->getNome()).'.add'.ucfirst(explode(' ', $atributo->getTipo())[2]).'('.strtolower(explode(' ', $atributo->getTipo())[2]).');



            }
            return '.strtolower($objeto->getNome()).';
		} catch (SQLException e) {
			e.printStackTrace();
            return null;
		}
    }
';
                
        
            
                
                
                
                
                $codigo .= '
           
	public boolean inserir'.ucfirst(explode(" ", $atributo->getTipo())[2]).'('. $nomeDoObjetoMA . ' ' . $nomeDoObjeto . ', '.ucfirst(explode(" ", $atributo->getTipo())[2]).' '.strtolower(explode(" ", $atributo->getTipo())[2]).'){
        int id'.ucfirst($objeto->getNome()).' =  ' . $nomeDoObjeto.'.getId();
        int id'.ucfirst(explode(' ', $atributo->getTipo())[2]).' = '.strtolower(explode(" ", $atributo->getTipo())[2]).'.getId();
		String sql = "INSERT INTO '.strtolower($objeto->getNome()).'_'.strtolower(explode(' ', $atributo->getTipo())[2]).'(';
                $codigo .= ' id'.strtolower($objeto->getNome()).', id'.strtolower(explode(' ', $atributo->getTipo())[2]).')';
                $codigo .= ' VALUES (?, ?)";';
                $codigo .= '

		try {

			PreparedStatement ps = this.getConexao().prepareStatement(sql);
            ps.setInt(1, id'.ucfirst($objeto->getNome()).');
            ps.setInt(2, id'.ucfirst(explode(' ', $atributo->getTipo())[2]) . ');
			ps.executeUpdate();
			return true;
		} catch (SQLException e) {
			e.printStackTrace();
			return false;
		}
	}	               
                    
                    
                    
	public boolean remover'.ucfirst(explode(" ", $atributo->getTipo())[2]).'('. $nomeDoObjetoMA . ' ' . $nomeDoObjeto . ', '.ucfirst(explode(" ", $atributo->getTipo())[2]).' '.strtolower(explode(" ", $atributo->getTipo())[2]).'){
        int id'.ucfirst($objeto->getNome()).' =  ' . $nomeDoObjeto.'.getId();
        int id'.ucfirst(explode(' ', $atributo->getTipo())[2]).' = '.strtolower(explode(" ", $atributo->getTipo())[2]).'.getId();
		String sql = "DELETE FROM  '.strtolower($objeto->getNome()).'_'.strtolower(explode(' ', $atributo->getTipo())[2]).' WHERE ';
                $codigo .= ' id'.strtolower($objeto->getNome()).' = ?';
                $codigo .= ' AND id'.strtolower(explode(' ', $atributo->getTipo())[2]).' = ? ";';
                
                $codigo .= '
		
		try {

			PreparedStatement ps = this.getConexao().prepareStatement(sql);
            ps.setInt(1, id'.ucfirst($objeto->getNome()).');
            ps.setInt(2, id'.ucfirst(explode(' ', $atributo->getTipo())[2]) . ');
			ps.executeUpdate();
			return true;
		} catch (SQLException e) {
			e.printStackTrace();
			return false;
		}
	}	               
                    
                    
';
            }
            
            $codigo .= '
                
                
}';
            
            
            
            return $codigo;
        }
        
}


?>