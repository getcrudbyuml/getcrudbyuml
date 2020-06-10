<?php

class DAOGerador
{

    private $software;

    private $listaDeArquivos;

    private $diretorio;

    public static function main(Software $software, $diretorio)
    {
        $gerador = new DAOGerador($software, $diretorio);
        $gerador->geraCodigo();
    }

    public function __construct(Software $software, $diretorio)
    {
        $this->software = $software;
        $this->diretorio = $diretorio;
    }

    private function geraCodigo()
    {
        $this->geraDAOGeral();
        foreach($this->software->getObjetos() as $objeto){
            $this->geraDAOs($objeto);
        }
        
        $this->criarArquivos();
        
    }
    private function criarArquivos(){
        
        $caminho = $this->diretorio.'/AppWebPHP/'.$this->software->getNomeSimples().'/src/classes/dao';
        if(!file_exists($caminho)) {
            mkdir($caminho, 0777, true);
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
    private function geraDAOGeral()
    {
        $codigo = '<?php
                
                
class DAO {
	const ARQUIVO_CONFIGURACAO = "../../../' . $this->software->getNomeSnakeCase() . '_bd.ini";
	    
	protected $conexao;
	private $tipoDeConexao;
	private $sgdb;
	    
	public function getSgdb(){
		return $this->sgdb;
	}
	public function __construct(PDO $conexao = null) {
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
        $caminho = $this->diretorio.'/AppWebPHP/'.$this->software->getNomeSimples().'/src/classes/dao/DAO.php';
        $this->listaDeArquivos[$caminho] = $codigo;
    }
    private function atualizar(Objeto $objeto){
        $codigo = '';
        $nomeDoObjeto = lcfirst($objeto->getNome());
        $atributosComuns = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if ($atributo->tipoListado()) {
                $atributosComuns[] = $atributo;
            }
        }
        $codigo = '
            
            
    public function atualizar(' . ucfirst($objeto->getNome()) . ' $' . lcfirst($objeto->getNome()) . ')
    {';
        $atributoPrimary = null;
        foreach ($objeto->getAtributos() as $atributo) {
            if ($atributo->isPrimary()) {
                $atributoPrimary = $atributo;
                break;
            }
        }
        if ($atributoPrimary != null) {
            $codigo .= '
        $id = $' . lcfirst($objeto->getNome()) . '->get' . ucfirst($atributoPrimary->getNome()) . '();';
        }
        $codigo .= '
            
            
        $sql = "UPDATE ' . $objeto->getNomeSnakeCase() . '
                SET
                ';
        $listaAtributo = array();
        foreach ($atributosComuns as $atributo) {
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            if (substr($atributo->getTipo(), 0, 6) == 'Array ') {
                continue;
            }
            $listaAtributo[] = $atributo;
        }
        $i = 0;
        foreach ($listaAtributo as $atributo) {
            $i ++;
            $codigo .= $atributo->getNomeSnakeCase() . ' = :' . $atributo->getNome();
            if ($i != count($listaAtributo)) {
                $codigo .= ',
                ';
            }
        }
        if ($atributoPrimary != null) {
            $codigo .= '
                WHERE ' . $objeto->getNomeSnakeCase() . '.id = :id;';
        }
        $codigo .= '";';
        
        foreach ($listaAtributo as $atributo) {
            if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                continue;
            }
            
            $codigo .= '
			$' . lcfirst($atributo->getNome()) . ' = $' . $nomeDoObjeto . '->get' . ucfirst($atributo->getNome()) . '();';
        }
        $codigo .= '
            
        try {
            
            $stmt = $this->getConexao()->prepare($sql);';
        foreach ($atributosComuns as $atributo) {
            if (substr($atributo->getTipo(), 0, 6) == 'Array ') {
                continue;
            }
            $codigo .= '
			$stmt->bindParam(":' . $atributo->getNome() . '", $' . $atributo->getNome() . ', PDO::'.$atributo->getTipoParametroPDO().');';
        }
        
        $codigo .= '
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
            
    }
            
            
';
        return $codigo;
    }
    private function inserir(Objeto $objeto)
    {
        $codigo = '
    public function inserir(' . ucfirst($objeto->getNome()) . ' $' . lcfirst($objeto->getNome()) . '){';
        
        $codigo .= '
        $sql = "INSERT INTO ' . $objeto->getNomeSnakeCase() . '(';
        $listaAtributos = array();
        $listaAtributosVar = array();
        foreach ($objeto->getAtributos() as $atributo)
        {
            if($atributo->isPrimary()){
                continue;
            }
            if($atributo->tipoListado()){
                $listaAtributos[] = $atributo->getNomeSnakeCase();
                $listaAtributosVar[] = ':' .lcfirst($atributo->getNome());
                
            }else if($atributo->isObjeto()){
                $listaAtributos[] = 'id_' . $atributo->getNomeSnakeCase();
                $listaAtributosVar[] = ':' .lcfirst($atributo->getNome());
                
            }else{
                continue;
            }
        }
        
        $codigo .= implode(", ", $listaAtributos);
        $codigo .= ') VALUES (';
        $codigo .= implode(", ", $listaAtributosVar);
        $codigo .= ');";';
        
        
        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->isPrimary()){
                continue;
            }
            if($atributo->tipoListado()){
                $codigo .= '
		$' . lcfirst($atributo->getNome()) . ' = $' . lcfirst($objeto->getNOme()) . '->get' . ucfirst($atributo->getNome()) . '();';
            }else if($atributo->isObjeto())
            {
                $codigo .= '
		$' . lcfirst($atributo->getNome()). ' = $' . lcfirst($objeto->getNome()) . '->get' . ucfirst($atributo->getNome()) . '()->getId();';
                
            }
            
        }
        $codigo .= '
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);';
        foreach ($objeto->getAtributos() as $atributo)
        {
            if($atributo->isPrimary()){
                continue;
            }
            if($atributo->tipoListado() || $atributo->isObjeto())
            {
                $codigo .= '
			$stmt->bindParam(":' . $atributo->getNome() . '", $' . $atributo->getNome() . ', PDO::'.$atributo->getTipoParametroPDO().');';
                
            }
        }
        
        
        $codigo .= '
			return $stmt->execute();
		} catch(PDOException $e) {
			echo \'{"error":{"text":\'. $e->getMessage() .\'}}\';
		}';
        
        
        
        
        $codigo .= '
            
    }';
        
        return $codigo;
        
    }

    private function inserirComPK(Objeto $objeto)
    {
        $codigo = '
    public function inserirComPK(' . ucfirst($objeto->getNome()) . ' $' . lcfirst($objeto->getNome()) . '){';
        
        $codigo .= '
        $sql = "INSERT INTO ' . $objeto->getNomeSnakeCase() . '(';
        $listaAtributos = array();
        $listaAtributosVar = array();
        foreach ($objeto->getAtributos() as $atributo) 
        {
            if($atributo->tipoListado()){
                $listaAtributos[] = $atributo->getNomeSnakeCase();
                $listaAtributosVar[] = ':' .lcfirst($atributo->getNome());
                
            }else if($atributo->isObjeto()){
                $listaAtributos[] = 'id_' . $atributo->getTipoSnakeCase() . '_' . $atributo->getNomeSnakeCase();
                $listaAtributosVar[] = ':' .lcfirst($atributo->getNome());
                
            }else{
                continue;
            }
        }

        $codigo .= implode(", ", $listaAtributos);
        $codigo .= ') VALUES (';        
        $codigo .= implode(", ", $listaAtributosVar);
        $codigo .= ');";';
        
        
        foreach ($objeto->getAtributos() as $atributo) {
            
            if($atributo->tipoListado()){
                $codigo .= '
		$' . lcfirst($atributo->getNome()) . ' = $' . lcfirst($objeto->getNOme()) . '->get' . ucfirst($atributo->getNome()) . '();';
            }else if($atributo->isObjeto())
            {
                $codigo .= '
		$' . lcfirst($atributo->getNome()). ' = $' . lcfirst($objeto->getNome()) . '->get' . ucfirst($atributo->getNome()) . '()->getId();';
                
            }
            
        }
        $codigo .= '
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);';
        foreach ($objeto->getAtributos() as $atributo) 
        {
            if($atributo->tipoListado() || $atributo->isObjeto())
            {
                $codigo .= '
			$stmt->bindParam(":' . $atributo->getNome() . '", $' . $atributo->getNome() . ', PDO::'.$atributo->getTipoParametroPDO().');';
            
            }
        }
            

        $codigo .= '
			return $stmt->execute();
		} catch(PDOException $e) {
			echo \'{"error":{"text":\'. $e->getMessage() .\'}}\';
		}';
        

        
        
        $codigo .= '

    }';
        
        return $codigo;
        
    }
    private function excluir(Objeto $objeto) : string {
        $codigo = '

	public function excluir(' . $objeto->getNome() . ' $' . lcfirst($objeto->getNome()). '){
		$' . $objeto->getAtributos()[0]->getNome() . ' = $' . lcfirst($objeto->getNome()) . '->get' . ucfirst($objeto->getAtributos()[0]->getNome()) . '();
		$sql = "DELETE FROM ' . $objeto->getNomeSnakeCase() . ' WHERE ' . $objeto->getAtributos()[0]->getNomeSnakeCase() . ' = :' . $objeto->getAtributos()[0]->getNomeSnakeCase() . '";
		    
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);
			$stmt->bindParam(":' . $objeto->getAtributos()[0]->getNomeSnakeCase() . '", $' . $objeto->getAtributos()[0]->getNomeSnakeCase() . ', PDO::PARAM_INT);
			return $stmt->execute();
			    
		} catch(PDOException $e) {
			echo \'{"error":{"text":\'. $e->getMessage() .\'}}\';
		}
	}

';
        return $codigo;
        
    }
    private function retornaLista($objeto) : string {
        $codigo = '';
        $nomeDoObjeto = lcfirst($objeto->getNome());
        
        $atributosComuns = array();
        $atributosObjetos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if ($atributo->tipoListado()) {
                $atributosComuns[] = $atributo;
            } else if ($atributo->isObjeto()) {
                $atributosObjetos[] = $atributo;
            }
        }
        $codigo .= '
	public function retornaLista() {
		$lista = array ();
		$sql = "';
        
        $sqlGerador = new SQLGerador($this->software);
        $codigo .= $sqlGerador->getSQLSelect($objeto);
        
        
        $codigo .= '
                 LIMIT 1000";

        try {
            $stmt = $this->conexao->prepare($sql);
            
		    if(!$stmt){   
                echo "<br>Mensagem de erro retornada: ".$this->conexao->errorInfo()[2]."<br>";
		        return $lista;
		    }
            $stmt->execute();
		    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach ( $result as $linha ) 
            {
		        $' . lcfirst($objeto->getNome()) . ' = new ' . ucfirst($objeto->getNome()) . '();';
        foreach ($atributosComuns as $atributo) {
            $codigo .= '
                $' . lcfirst($objeto->getNome()) . '->set' . ucfirst($atributo->getNome()) . '( $linha [\'' . $atributo->getNomeSnakeCase() . '\'] );';
        }
        foreach ($atributosObjetos as $atributoObjeto) {
            
            foreach ($this->software->getObjetos() as $objeto2) {
                if ($objeto2->getNome() == $atributoObjeto->getTipo()) {
                    foreach ($objeto2->getAtributos() as $atributo3) {
                        if ($atributo3->getIndice() == Atributo::INDICE_PRIMARY) {
                            $codigo .= '
                $' . $nomeDoObjeto . '->get' . ucfirst($atributoObjeto->getNome()) . '()->set' . ucfirst($atributo3->getNome()) . '( $linha [\'' . $atributo3->getNomeSnakeCase() . '_' . $atributoObjeto->getTipoSnakeCase() . '_' . $atributoObjeto->getNomeSnakeCase() . '\'] );';
                        } else if ($atributo3->tipoListado()) {
                            $codigo .= '
                $' . $nomeDoObjeto . '->get' . ucfirst($atributoObjeto->getNome()) . '()->set' . ucfirst($atributo3->getNome()) . '( $linha [\'' . $atributo3->getNomeSnakeCase() . '_' . $atributoObjeto->getTipoSnakeCase() . '_' . $atributoObjeto->getNomeSnakeCase() . '\'] );';
                        }
                    }
                    break;
                }
            }
        }
        $codigo .= '
                $lista [] = $' . $nomeDoObjeto . ';';
        $codigo .= '

	
		    }
		} catch(PDOException $e) {
		    echo $e->getMessage();
 		}
        return $lista;	
    }
        ';
        
        
        return $codigo;
    }
    private function pesaquisarPor($objeto) : string {
        
        $codigo = '';
        $nomeDoObjeto = lcfirst($objeto->getNome());
        $nomeDoObjetoMA = ucfirst($objeto->getNome());
        
        $atributosComuns = array();
        $atributosObjetos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if ($atributo->tipoListado()) {
                $atributosComuns[] = $atributo;
            } else if ($atributo->isObjeto()) {
                $atributosObjetos[] = $atributo;
            }
        }
        
        foreach ($atributosComuns as $atributo) {
            
            
            
            $codigo .= '
                
    public function pesquisaPor' . ucfirst($atributo->getNome()) . '(' . $nomeDoObjetoMA . ' $' . $nomeDoObjeto . ') {
        $lista = array();
	    $' . $atributo->getNome() . ' = $' . lcfirst($objeto->getNome()). '->get' . ucfirst($atributo->getNome()) . '();';
            $codigo .= '
                
        $sql = "';
            
            $sqlGerador = new SQLGerador($this->software);
            $codigo .= $sqlGerador->getSQLSelect($objeto);
            
            if ($atributo->getTipo() == Atributo::TIPO_STRING || $atributo->getTipo() == Atributo::TIPO_DATE || $atributo->getTipo() == Atributo::TIPO_DATE_TIME) {
                $codigo .= '
            WHERE ' . $objeto->getNomeSnakeCase() . '.' . $atributo->getNomeSnakeCase() . ' like :' . $atributo->getNome() . '";';
            } else {
                $codigo .= '
            WHERE ' . $objeto->getNomeSnakeCase() . '.' . $atributo->getNomeSnakeCase() . ' = :' . $atributo->getNome() . '";';
            }
            
            $codigo .= '
                
        try {
                
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(":'.$atributo->getNome().'", $'.$atributo->getNome().', PDO::'.$atributo->getTipoParametroPDO().');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ( $result as $linha ) {
                $' . $nomeDoObjeto . ' = new ' . ucfirst($nomeDoObjeto) . '();';
            foreach ($atributosComuns as $atributo2) {
                
                $codigo .= '
    	        $' . $nomeDoObjeto . '->set' . ucfirst($atributo2->getNome()) . '( $linha [\'' . $atributo2->getNomeSnakeCase() . '\'] );';
            }
            foreach ($atributosObjetos as $atributoObjeto) {
                
                foreach ($this->software->getObjetos() as $objeto2) {
                    if ($objeto2->getNome() == $atributoObjeto->getTipo()) {
                        foreach ($objeto2->getAtributos() as $atributo3) {
                            if ($atributo3->getIndice() == Atributo::INDICE_PRIMARY) {
                                $codigo .= '
    			$' . lcfirst($objeto->getNome()) . '->get' . ucfirst($atributoObjeto->getNome()) . '()->set' . ucfirst($atributo3->getNome()) . '( $linha [\'' . $atributo3->getNomeSnakeCase() . '_' . $atributoObjeto->getTipoSnakeCase() . '_' . $atributoObjeto->getNomeSnakeCase() . '\'] );';
                            } else {
                                $codigo .= '
    			$' . lcfirst($objeto->getNome()). '->get' . ucfirst($atributoObjeto->getNome()) . '()->set' . ucfirst($atributo3->getNome()) . '( $linha [\'' . $atributo3->getNomeSnakeCase() . '_' . $atributoObjeto->getTipoSnakeCase(). '_' . $atributoObjeto->getNomeSnakeCase() . '\'] );';
                            }
                        }
                        break;
                    }
                }
            }
            $codigo .= '
    			$lista [] = $' . lcfirst($objeto->getNome()) . ';
    			    
            }
    			    
        } catch(PDOException $e) {
            echo $e->getMessage();
    			    
        }
		return $lista;
    }';
        }
        return $codigo;
    }
    private function preencherPor($objeto) : string {
        $nomeDoObjeto = lcfirst($objeto->getNome());
        $nomeDoObjetoMA = ucfirst($objeto->getNome());
        
        $atributosComuns = array();
        $atributosObjetos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if ($atributo->tipoListado()) {
                $atributosComuns[] = $atributo;
            } else if ($atributo->isObjeto()) {
                $atributosObjetos[] = $atributo;
            }
        }
        $codigo = '';
        
        foreach ($atributosComuns as $atributo) {
            
            $nomeDoAtributoMA = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
            
            
            $codigo .= '
                
    public function preenchePor' . $nomeDoAtributoMA . '(' . $nomeDoObjetoMA . ' $' . $nomeDoObjeto . ') {
        
	    $' . $atributo->getNome() . ' = $' . $nomeDoObjeto . '->get' . ucfirst($atributo->getNome()) . '();';
            $codigo .= '
	    $sql = "SELECT ';
            $i = 0;
            foreach ($atributosComuns as $atributoComum) {
                
                $i ++;
                $codigo .= '
                ' . $objeto->getNomeSnakeCase() . '.' . $atributoComum->getNomeSnakeCase() . '';
                
                if ($i != count($atributosComuns)) {
                    $codigo .= ', ';
                }
            }
            
            foreach ($atributosObjetos as $atributoObjeto) {
                
                foreach ($this->software->getObjetos() as $objeto2) {
                    if ($objeto2->getNome() == $atributoObjeto->getTipo()) {
                        $i = 0;
                        foreach ($objeto2->getAtributos() as $atributo3) {
                            $i ++;
                            if (count($atributosComuns) != 0 && $i == 1) {
                                $codigo .= ',';
                            }
                            if ($atributo3->getIndice() == Atributo::INDICE_PRIMARY) {
                                
                                $codigo .= '
                ' . $objeto->getNomeSnakeCase() . '.' . $atributo3->getNomeSnakeCase() . '_' . $atributoObjeto->getTipoSnakeCase() . '_' . $atributoObjeto->getNomeSnakeCase();
                            } else {
                                $codigo .= '
                ' . $atributoObjeto->getTipoSnakeCase() . '.' . $atributo3->getNomeSnakeCase() . ' as ' . $atributo3->getNomeSnakeCase() . '_' . $atributoObjeto->getTipoSnakeCase() . '_' . $atributoObjeto->getNomeSnakeCase();
                            }
                            if ($i != count($objeto2->getAtributos())) {
                                $codigo .= ', ';
                            }
                        }
                        break;
                    }
                }
            }
            $codigo .= '
                FROM ' . $objeto->getNomeSnakeCase();
            foreach ($atributosObjetos as $atributoObjeto) {
                
                foreach ($this->software->getObjetos() as $objeto2) {
                    if ($objeto2->getNome() == $atributoObjeto->getTipo()) {
                        foreach ($objeto2->getAtributos() as $atributo3) {
                            if ($atributo3->getIndice() == Atributo::INDICE_PRIMARY) {
                                $codigo .= '
                INNER JOIN ' . strtolower($atributoObjeto->getTipo()) . '
                ON ' . $atributoObjeto->getTipoSnakeCase() . '.' . $atributo3->getNomeSnakeCase() . ' = ' . $nomeDoObjeto . '.' . $atributo3->getNomeSnakeCase() . '_' . $atributoObjeto->getTipoSnakeCase() . '_' . $atributoObjeto->getNomeSnakeCase();
                                break;
                            }
                        }
                        break;
                    }
                }
            }
            
            if ($atributo->getTipo() == Atributo::TIPO_STRING || $atributo->getTipo() == Atributo::TIPO_DATE || $atributo->getTipo() == Atributo::TIPO_DATE_TIME) {
                $codigo .= '
                WHERE ' . $objeto->getNomeSnakeCase() . '.' . $atributo->getNomeSnakeCase() . ' like \'%$' . $atributo->getNome() . '%\'";';
            } else {
                $codigo .= '
                WHERE ' . $objeto->getNomeSnakeCase() . '.' . $atributo->getNomeSnakeCase() . ' = $' . $atributo->getNome() . '";';
            }
            
            $codigo .= '
	    $result = $this->getConexao ()->query ( $sql );
                
	    foreach ( $result as $linha ) {';
            foreach ($atributosComuns as $atributo2) {
                
                $nomeDoAtributoMA = strtoupper(substr($atributo2->getNome(), 0, 1)) . substr($atributo2->getNome(), 1, 100);
                $codigo .= '
	        $' . $nomeDoObjeto . '->set' . $nomeDoAtributoMA . '( $linha [\'' . $atributo2->getNomeSnakeCase() . '\'] );';
            }
            foreach ($atributosObjetos as $atributoObjeto) {
                
                foreach ($this->software->getObjetos() as $objeto2) {
                    if ($objeto2->getNome() == $atributoObjeto->getTipo()) {
                        foreach ($objeto2->getAtributos() as $atributo3) {
                            if ($atributo3->getIndice() == Atributo::INDICE_PRIMARY) {
                                $codigo .= '
			$' . $nomeDoObjeto . '->get' . ucfirst($atributoObjeto->getNome()) . '()->set' . ucfirst($atributo3->getNome()) . '( $linha [\'' . $atributo3->getNomeSnakeCase() . '_' . $atributoObjeto->getTipoSnakeCase() . '_' . $atributoObjeto->getNomeSnakeCase() . '\'] );';
                            } else {
                                $codigo .= '
			$' . $nomeDoObjeto . '->get' . ucfirst($atributoObjeto->getNome()) . '()->set' . ucfirst($atributo3->getNome()) . '( $linha [\'' . $atributo3->getNomeSnakeCase() . '_' . $atributoObjeto->getTipoSnakeCase() . '_' . $atributoObjeto->getNomeSnakeCase() . '\'] );';
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
        
        return $codigo;
    }
    private function buscarAtributoNN($objeto) : string {
        $codigo = '';
        $atributosNN = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if ($atributo->isArrayNN()) {
                $atributosNN[] = $atributo;
            }
        }
        foreach ($atributosNN as $atributo) {
            $codigo .= '
    public function buscar' . ucfirst($atributo->getNome()) . '(' . ucfirst($objeto->getNome()) . ' $' . strtolower($objeto->getNome()) . ')
    {
        $id = $' . strtolower($objeto->getNome()) . '->getId();
        $sql = "SELECT * FROM
                ' . strtolower($objeto->getNome()) . '_' . strtolower(explode(' ', $atributo->getTipo())[2]) . '
                INNER JOIN ' . strtolower(explode(' ', $atributo->getTipo())[2]) . '
                ON  ' . strtolower($objeto->getNome()) . '_' . strtolower(explode(' ', $atributo->getTipo())[2]) . '.id_' . strtolower(explode(' ', $atributo->getTipo())[2]) . ' = ' . strtolower(explode(' ', $atributo->getTipo())[2]) . '.id
                 WHERE ' . strtolower($objeto->getNome()) . '_' . strtolower(explode(' ', $atributo->getTipo())[2]) . '.id_' . $objeto->getNomeSnakeCase() . ' = $id";
        $result = $this->getConexao ()->query ( $sql );
                     
        foreach ($result as $linha) {
            $' . strtolower(explode(' ', $atributo->getTipo())[2]) . ' = new ' . ucfirst(explode(' ', $atributo->getTipo())[2]) . '();';
            
            foreach ($this->software->getObjetos() as $obj) {
                if (strtolower($obj->getNome()) == strtolower(explode(' ', $atributo->getTipo())[2])) {
                    foreach ($obj->getAtributos() as $atr) {
                        
                        $nomeDoAtributoMA = ucfirst($atr->getNome());
                        
                        if ($atr->getTipo() == Atributo::TIPO_INT || $atr->getTipo() == Atributo::TIPO_STRING || $atr->getTipo() == Atributo::TIPO_FLOAT) {
                            $codigo .= '
	        $' . strtolower(explode(' ', $atributo->getTipo())[2]) . '->set' . $nomeDoAtributoMA . '( $linha [\'' . strtolower($atr->getNome()) . '\'] );';
                        } else if (substr($atr->getTipo(), 0, 6) == 'Array ') {
                            //
                            $codigo .= '
            $' . strtolower(explode(' ', $atributo->getTipo())[2]) . 'Dao = new ' . ucfirst(explode(' ', $atributo->getTipo())[2]) . 'DAO($this->getConexao());
            $' . strtolower(explode(' ', $atributo->getTipo())[2]) . 'Dao->buscar' . ucfirst($atr->getNome()) . '($' . strtolower(explode(' ', $atributo->getTipo())[2]) . ');';
                            // $objetoDao->buscar
                        }
                    }
                    $codigo .= '';
                    break;
                }
            }
            
            $codigo .= '
            $' . strtolower($objeto->getNome()) . '->add' . ucfirst(explode(' ', $atributo->getTipo())[2]) . '($' . strtolower(explode(' ', $atributo->getTipo())[2]) . ');
                
        }
        return $' . strtolower($objeto->getNome()) . ';
    }';
        }
            
        return $codigo;
    }
    private function inserirAtributoNN($objeto) : string {
        $codigo = '';
        $atributosNN = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if ($atributo->isArrayNN()) {
                $atributosNN[] = $atributo;
            }
        }
        foreach ($atributosNN as $atributo) {
            $codigo .= '
                
                
	public function inserir' . ucfirst($atributo->getTipoDeArray()) . '(' . ucfirst($objeto->getNome()) . ' $' . lcfirst($objeto->getNome()). ', ' . ucfirst($atributo->getTipoDeArray()) . ' $' . lcfirst($atributo->getTipoDeArray()) . ')
    {
        $id' . ucfirst($objeto->getNome()) . ' =  $' .lcfirst( $objeto->getNome()). '->getId();
        $id' . ucfirst($atributo->getTipoDeArray()) . ' = $' . lcfirst($atributo->getTipoDeArray()) . '->getId();
		$sql = "INSERT INTO ' . $objeto->getNomeSnakeCase() . '_' . $atributo->getArrayTipoSnakeCase() . '(';
            $codigo .= '
                    id_' . $objeto->getNomeSnakeCase() . ',
                    id_' . $atributo->getArrayTipoSnakeCase(). ')
				VALUES(';
            $codigo .= '
                :id' . ucfirst($objeto->getNome()) . ',
                :id' . ucfirst($atributo->getTipoDeArray());
            $codigo .= ')";';
            $codigo .= '
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);';
            
            $codigo .= '
		    $stmt->bindParam(":id' . ucfirst($objeto->getNome()) . '", $id' . ucfirst($objeto->getNome()) . ', PDO::PARAM_INT);
            $stmt->bindParam(":id' . ucfirst($atributo->getTipoDeArray()) . '", $id' . ucfirst($atributo->getTipoDeArray()) . ', PDO::PARAM_INT);
                
';
            
            $codigo .= '
			return $stmt->execute();
		} catch(PDOException $e) {
			echo \'{"error":{"text":\'. $e->getMessage() .\'}}\';
		}
	}';
        }
        return $codigo;
    }
    private function removerAtributoNN($objeto) : string {
        $codigo = '';
        $nomeDoObjeto = lcfirst($objeto->getNome());
        $nomeDoObjetoMA = ucfirst($objeto->getNome());
        
        $atributosNN = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if ($atributo->isArrayNN()) {
                $atributosNN[] = $atributo;
            }
        }
        foreach ($atributosNN as $atributo) {
            $codigo .= '
	public function remover' . ucfirst(explode(" ", $atributo->getTipo())[2]) . '(' . $nomeDoObjetoMA . ' $' . $nomeDoObjeto . ', ' . ucfirst(explode(" ", $atributo->getTipo())[2]) . ' $' . strtolower(explode(" ", $atributo->getTipo())[2]) . '){
        $id' . strtolower($objeto->getNome()) . ' =  $' . $nomeDoObjeto . '->getId();
        $id' . strtolower(explode(' ', $atributo->getTipo())[2]) . ' = $' . strtolower(explode(" ", $atributo->getTipo())[2]) . '->getId();
		$sql = "DELETE FROM  ' . strtolower($objeto->getNome()) . '_' . strtolower(explode(' ', $atributo->getTipo())[2]) . ' WHERE ';
            $codigo .= '
                    id' . strtolower($objeto->getNome()) . ' = :id' . strtolower($objeto->getNome()) . '
                    AND
                    id' . strtolower(explode(' ', $atributo->getTipo())[2]) . ' = :id' . strtolower(explode(' ', $atributo->getTipo())[2]) . '";';
            
            $codigo .= '
		try {
			$db = $this->getConexao();
			$stmt = $db->prepare($sql);';
            
            $codigo .= '
		    $stmt->bindParam(":id' . strtolower($objeto->getNome()) . '", $id' . strtolower($objeto->getNome()) . ', PDO::PARAM_INT);
            $stmt->bindParam(":id' . strtolower(explode(' ', $atributo->getTipo())[2]) . '", $id' . strtolower(explode(' ', $atributo->getTipo())[2]) . ', PDO::PARAM_INT);
';
            
            $codigo .= '
			return $stmt->execute();
		} catch(PDOException $e) {
			echo \'{"error":{"text":\'. $e->getMessage() .\'}}\';
		}
	}
                
                
';
        }
        return $codigo; 
    }
    private function geraDAOs(Objeto $objeto)
    {
        $codigo = '';
        $codigo .= '<?php
                
/**
 * Classe feita para manipulação do objeto ' . ucfirst($objeto->getNome()) . '
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte
 *
 *
 */
class ' . ucfirst($objeto->getNome()) . 'DAO extends DAO {
    

';
        $codigo .= $this->atualizar($objeto);
        $codigo .= $this->inserir($objeto);
        $codigo .= $this->inserirComPK($objeto);
        $codigo .= $this->excluir($objeto);
        $codigo .= $this->retornaLista($objeto);
        $codigo .= $this->pesaquisarPor($objeto);
        $codigo .= $this->preencherPor($objeto);
        $codigo .= $this->buscarAtributoNN($objeto);
        $codigo .= $this->inserirAtributoNN($objeto);
        $codigo .= $this->removerAtributoNN($objeto);
        $codigo .= '
}';

        $caminho = $this->diretorio.'/AppWebPHP/'.$this->software->getNomeSimples().'/src/classes/dao/'.ucfirst($objeto->getNome()).'DAO.php';
        $this->listaDeArquivos[$caminho] = $codigo;
    }
}

?>