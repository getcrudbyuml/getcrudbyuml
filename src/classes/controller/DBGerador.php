<?php


class DBGerador{
    private $listaDeArquivos;
    private $software;
    public function __construct(Software $software){
        $this->software = $software;
    }
    public function getListaDeArquivos(){
        return $this->listaDeArquivos;
    }
    public function gerarCodigo($linguagem = self::PHP_LINGUAGEM){
        switch ($linguagem){
            case EscritorDeSoftware::PHP_LINGUAGEM:
                $this->geraCodigoPHP();
                break;
            case EscritorDeSoftware::JAVA_LINGUAGEM:
                $this->geraCodigoJava();
                break;
            default:
                $this->geraCodigoPHP();
                break;
        }
        
    }
    private function geraCodigoJava(){
        $codigo = $this->geraINI($this->software);
        $caminho = "sistemas/sistemasjava/" . $this->software->getNome() . '/' . strtolower($this->software->getNome() . '_bd.ini');
        $this->listaDeArquivos[$caminho] = $codigo;
    }
    private function geraCodigoPHP(){
        $codigo = $this->geraINI($this->software);
        $caminho = "sistemas/sistemasphp/" . $this->software->getNome() . '/' . strtolower($this->software->getNome()) . '_bd.ini';
        $this->listaDeArquivos[$caminho] = $codigo;
    }
    
    public function geraINI()
    {
        $codigo = '
;configurações do banco de dados.
;Banco de regras de negócio do sistema.
            
sgdb = sqlite
host = localhost
porta = 5432
bd_nome = ../' . strtolower($this->software->getNome()) . '.db
usuario = root
senha = 123
';
        
        return $codigo;
        
    }
    
    
    public function geraBancoPG(Software $software)
    {
        $objetosNN = array();
        $this->codigo = '';
        foreach ($software->getObjetos() as $objeto) {
            $this->codigo .= 'CREATE TABLE ' . strtolower($objeto->getNome());
            $this->codigo .= " (\n";
            $i = 0;
            foreach ($objeto->getAtributos() as $atributo) {
                $i ++;
                $flagPulei = false;
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    $this->codigo .=  strtolower($atributo->getNome()) . ' serial NOT NULL';
                } else if($atributo->getTipo() == Atributo::TIPO_STRING){
                    $this->codigo .= strtolower($atributo->getNome()) . ' character varying(150)';
                }else if($atributo->getTipo() == Atributo::TIPO_INT){
                    $this->codigo .= strtolower($atributo->getNome()) . '  integer';
                }else if($atributo->getTipo() == Atributo::TIPO_FLOAT){
                    $this->codigo .= strtolower($atributo->getNome()) . ' character  numeric(8,2)';
                }else if(substr($atributo->getTipo(),0,6) == 'Array '){
                    if(explode(' ', $atributo->getTipo())[1]  == 'n:n'){
                        $objetosNN[] = $objeto;
                    }
                    $flagPulei = true;
                    
                }else{
                    $this->codigo .= 'id_'.strtolower($atributo->getTipo()).'_'.strtolower($atributo->getNome()) . ' integer NOT NULL';
                }
                if ($i == count($objeto->getAtributos())) {
                    foreach ($objeto->getAtributos() as $atributo) {
                        if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                            if(!$flagPulei){
                                $this->codigo .= ",\n";
                            }
                            $this->codigo .= ' CONSTRAINT pk_'.strtolower($objeto->getNome()).'_'.strtolower($atributo->getNome()).' PRIMARY KEY ('.strtolower($atributo->getNome()).')';
                            break;
                        }
                    }
                    $this->codigo .= "\n";
                    continue;
                }
                if(!$flagPulei){
                    $this->codigo .= ",\n";
                }
                
            }
            
            $this->codigo .= ");\n";
            
        }
        foreach($objetosNN as $objeto){
            
            //explode(' ', $string);
            foreach($objeto->getAtributos() as $atributo){
                if(substr($atributo->getTipo(),0,6) == 'Array '){
                    $this->codigo .= 'CREATE TABLE ' . strtolower($objeto->getNome()).'_'.strtolower(explode(" ", $atributo->getTipo())[2]);
                    $this->codigo .= '(
    id serial NOT NULL,
    id'.strtolower($objeto->getNome()).' integer NOT NULL,
    id'.strtolower(explode(" ", $atributo->getTipo())[2]).' integer NOT NULL,
    CONSTRAINT pk_'.strtolower($objeto->getNome()).'_'.strtolower(explode(" ", $atributo->getTipo())[2]).'_id PRIMARY KEY (id),
    CONSTRAINT fk_'.strtolower($objeto->getNome()).'_id FOREIGN KEY (id'.strtolower($objeto->getNome()).') REFERENCES '.strtolower($objeto->getNome()).' (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION,
    CONSTRAINT fk_'.strtolower(explode(" ", $atributo->getTipo())[2]).'_id FOREIGN KEY (id'.strtolower(explode(" ", $atributo->getTipo())[2]).') REFERENCES '.strtolower(explode(" ", $atributo->getTipo())[2]).' (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION
);';
                    
                }
                
            }
            
        }
        //Adicionar outras chaves estrangeiras.
        
        foreach ($software->getObjetos() as $objeto) {
            foreach($objeto->getAtributos() as $atributo){
                if($atributo->getTipo() == Atributo::TIPO_INT || $atributo->getTipo() == Atributo::TIPO_STRING || $atributo->getTipo() == Atributo::TIPO_FLOAT)
                {
                    continue;
                }else if(substr($atributo->getTipo(),0,6) == 'Array '){
                    continue;
                }else{
                    foreach($software->getObjetos() as $objeto2){
                        if($atributo->getTipo() == $objeto2->getNome()){
                            $objetoDoAtributo = $objeto2;
                            break;
                        }
                        
                    }
                    foreach($objetoDoAtributo->getAtributos() as $atributo3){
                        if($atributo3->getIndice() == Atributo::INDICE_PRIMARY){
                            $atributoPrimary = $atributo3;
                            break;
                        }
                    }
                    $this->codigo .= '
ALTER TABLE ' . strtolower($objeto->getNome()).'
ADD CONSTRAINT
fk_'.strtolower($objeto->getNome()).'_'.strtolower($atributo->getTipo()).'_'.strtolower($atributo->getNome()) . ' FOREIGN KEY (id_'.strtolower($atributo->getTipo()).'_'.strtolower($atributo->getNome()) . ')
REFERENCES '.strtolower($atributo->getTipo()).'('.$atributoPrimary->getNome().');
';
                }
                
            }
        }

        $this->codigo .= '';
        $this->caminho = 'sistemasphp/' . $software->getNome() . '/' . strtolower($software->getNome()) . '_banco_pg.sql';
    }
    public function geraBancoSqlite(Software $software)
    {
        $objetosNN = array();
        
        $bdNome = 'sistemasphp/' . $software->getNome() . '/' . strtolower($software->getNome()) . '.db';
        if(file_exists($bdNome)){
            unlink($bdNome);
        }
        $pdo = new PDO('sqlite:' . $bdNome);
        $this->codigo = '';
        foreach ($software->getObjetos() as $objeto) {
            $this->codigo .= 'CREATE TABLE ' . strtolower($objeto->getNome());
            $this->codigo .= " (\n";
            $i = 0;
            $atributosComuns = array();
            
            
            foreach ($objeto->getAtributos() as $atributo) {
                if(substr($atributo->getTipo(),0,6) == 'Array '){
                    if(explode(' ', $atributo->getTipo())[1]  == 'n:n'){
                        $objetosNN[] = $objeto;
                    }
                }else{
                    $atributosComuns[] = $atributo;
                }
            }
            foreach($atributosComuns as $atributo){
                $i ++;
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    $this->codigo .= strtolower($atributo->getNome()) . '	INTEGER PRIMARY KEY AUTOINCREMENT';
                } else if($atributo->getTipo() == Atributo::TIPO_STRING){
                    $this->codigo .= strtolower($atributo->getNome()) . '	TEXT';
                }else if($atributo->getTipo() == Atributo::TIPO_INT){
                    $this->codigo .= strtolower($atributo->getNome()) . '  INTEGER';
                }else if($atributo->getTipo() == Atributo::TIPO_FLOAT){
                    $this->codigo .= strtolower($atributo->getNome()) . ' NUMERIC';
                }
                else{
                    $this->codigo .= 'id_'.strtolower($atributo->getTipo()).'_'.strtolower($atributo->getNome()) . ' integer NOT NULL';
                }
                if ($i >= count($atributosComuns)) {
                    $this->codigo .= "\n";
                    continue;
                }
                
                $this->codigo .= ",\n";
                
            }
            $this->codigo .= ");\n";
        }
        
        foreach($objetosNN as $objeto){
            
            //explode(' ', $string);
            foreach($objeto->getAtributos() as $atributo){
                if(substr($atributo->getTipo(),0,6) == 'Array '){
                    $this->codigo .= 'CREATE TABLE ' . strtolower($objeto->getNome()).'_'.strtolower(explode(" ", $atributo->getTipo())[2]);
                    $this->codigo .= '(
    id 	INTEGER PRIMARY KEY AUTOINCREMENT,
    id'.strtolower($objeto->getNome()).' INTEGER,
    id'.strtolower(explode(" ", $atributo->getTipo())[2]).' INTEGER
);';
                    
                }
                
            }
            
        }
        $pdo->exec($this->codigo);
        $this->caminho = 'sistemasphp/' . $software->getNome() . '/' . strtolower($software->getNome()) . '_banco_sqlite.sql';
    }
    
}

?>