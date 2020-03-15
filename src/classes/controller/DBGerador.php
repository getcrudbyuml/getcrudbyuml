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
    public function gerarCodigo(){
        $codigo = $this->geraINI();
        $path = 'sistemas/'.$this->software->getNome().'/'.strtolower($this->software->getNome()). '_bd.ini';
        
        $this->listaDeArquivos[$path] = $codigo;
        
        $path = 'sistemas/'.$this->software->getNome().'/'.strtolower($this->software->getNome()) . '_banco_pg.sql';
        $codigo = $this->geraBancoPG($this->software);
        $this->listaDeArquivos[$path] = $codigo;
        
        $path = 'sistemas/'.$this->software->getNome().'/'.strtolower($this->software->getNome()) . '_banco_sqlite.sql';
        $codigo = $this->geraBancoSqlite($this->software);
        $this->listaDeArquivos[$path] = $codigo;
        
        
        
    }

    public function geraINI()
    {
        $codigo = '
;configurações do banco de dados.
;Banco de regras de negócio do sistema.
            
sgdb = sqlite
host = localhost
porta = 5432
bd_nome = ../../' . strtolower($this->software->getNome()) . '.db
usuario = root
senha = 123
';
        
        return $codigo;
        
    }
    
    
    public function geraBancoPG()
    {
        $objetosNN = array();
        $codigo = '';
        foreach ($this->software->getObjetos() as $objeto) {
            $codigo .= 'CREATE TABLE ' . $objeto->getNomeSnakeCase();
            $codigo .= " (\n";
            $i = 0;
            foreach ($objeto->getAtributos() as $atributo) {
                $i ++;
                $flagPulei = false;
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY && $atributo->tipoListado()) 
                {
                    $codigo .= '    '.$atributo->getNomeSnakeCase().' '.$atributo->getTipoPostgres(). ' serial NOT NULL';
                    
                }else if($atributo->tipoListado())
                {
                    $codigo .= '    '.$atributo->getNomeSnakeCase() . ' '.$atributo->getTipoPostgres();
                }
                else if($atributo->isArrayNN()){
                    $objetosNN[] = $objeto;
                    $flagPulei = true;
                }else if($atributo->isObjeto())
                {
                    $codigo .= '    id_'.$atributo->getTipoSnakeCase().'_'.$atributo->getNomeSnakeCase() . ' integer NOT NULL';
                }else{
                    //Tipo Array Comum não implementado
                    $flagPulei = true;
                }
                if ($i == count($objeto->getAtributos())) {
                    foreach ($objeto->getAtributos() as $atributo) {
                        if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                            if(!$flagPulei){
                                $codigo .= ",\n";
                            }
                            $codigo .= '    CONSTRAINT pk_'.strtolower($objeto->getNome()).'_'.$atributo->getNomeSnakeCase()."\n".'    PRIMARY KEY ('.$atributo->getNomeSnakeCase().')';
                            break;
                        }
                    }
                    $codigo .= "\n";
                    continue;
                }
                if(!$flagPulei){
                    $codigo .= ",\n";
                }
                
            }
            
            $codigo .= ");\n";
            
        }
        foreach($objetosNN as $objeto){
            
            //explode(' ', $string);
            foreach($objeto->getAtributos() as $atributo){
                if(substr($atributo->getTipo(),0,6) == 'Array '){
                    $codigo .= '
CREATE TABLE ' . $objeto->getNomeSnakeCase().'_'.strtolower(explode(" ", $atributo->getTipoSnakeCase())[2]);
                    $codigo .= '(
    id serial NOT NULL,
    id_'.$objeto->getNomeSnakeCase().' integer NOT NULL,
    id_'.strtolower(explode(" ", $atributo->getTipoSnakeCase())[2]).' integer NOT NULL,
    CONSTRAINT pk_'.$objeto->getNomeSnakeCase().'_'.explode(" ", $atributo->getTipoSnakeCase())[2].'_id PRIMARY KEY (id),
    CONSTRAINT fk_'.$objeto->getNomeSnakeCase().'_id FOREIGN KEY (id_'.$objeto->getNomeSnakeCase().') REFERENCES '.strtolower($objeto->getNome()).' (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION,
    CONSTRAINT fk_'.strtolower(explode(" ", $atributo->getTipoSnakeCase())[2]).'_id FOREIGN KEY (id'.strtolower(explode(" ", $atributo->getTipo())[2]).') REFERENCES '.strtolower(explode(" ", $atributo->getTipo())[2]).' (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION
);';
                    
                }
                
            }
            
        }
        //Adicionar outras chaves estrangeiras.
        
        foreach ($this->software->getObjetos() as $objeto) {
            foreach($objeto->getAtributos() as $atributo){
                if($atributo->getTipo() == Atributo::TIPO_INT || $atributo->getTipo() == Atributo::TIPO_STRING || $atributo->getTipo() == Atributo::TIPO_FLOAT)
                {
                    continue;
                }else if(substr($atributo->getTipo(),0,6) == 'Array '){
                    continue;
                }else{
                    foreach($this->software->getObjetos() as $objeto2){
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
                    $codigo .= '
ALTER TABLE ' . strtolower($objeto->getNome()).'
ADD CONSTRAINT
fk_'.strtolower($objeto->getNome()).'_'.strtolower($atributo->getTipo()).'_'.$atributo->getNomeSnakeCase() . ' FOREIGN KEY (id_'.strtolower($atributo->getTipo()).'_'.$atributo->getNomeSnakeCase() . ')
REFERENCES '.strtolower($atributo->getTipo()).'('.$atributoPrimary->getNome().');
';
                }
                
            }
        }

        return $codigo;
        
    }
    public function geraBancoSqlite()
    {
        $objetosNN = array();
        
        $bdNome = 'sistemas/' . $this->software->getNome() . '/' . strtolower($this->software->getNome()) . '.db';
        if(file_exists($bdNome)){
            unlink($bdNome);
        }
        $pdo = new PDO('sqlite:' . $bdNome);
        $codigo = '';
        foreach ($this->software->getObjetos() as $objeto) 
        {
            $codigo .= 'CREATE TABLE ' . $objeto->getNomeSnakeCase();
            $codigo .= " (\n";
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
                if($atributo->tipoListado()){
                    $codigo .= '    '.$atributo->getNomeSnakeCase().' '.$atributo->getTipoSqlite().' ';
                }
                else if($atributo->isObjeto()){
                    $codigo .= '    id_'.$atributo->getTipoSnakeCase().'_'.$atributo->getNomeSnakeCase() . ' INTEGER NOT NULL';
                }
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    $codigo .= '    PRIMARY KEY AUTOINCREMENT';
                }
                if ($i >= count($atributosComuns)) {
                    $codigo .= "\n";
                    continue;
                }
                $codigo .= ",\n";
                
            }
            $codigo .= ");\n";
        }
        
        foreach($objetosNN as $objeto){
            
            //explode(' ', $string);
            foreach($objeto->getAtributos() as $atributo){
                if(substr($atributo->getTipo(),0,6) == 'Array '){
                    $codigo .= 'CREATE TABLE ' . $objeto->getNomeSnakeCase().'_'.strtolower(explode(" ", $atributo->getTipo())[2]);
                    $codigo .= '(
    id 	INTEGER PRIMARY KEY AUTOINCREMENT,
    id_'.$objeto->getNomeSnakeCase().' INTEGER,
    id_'.strtolower(explode(" ", $atributo->getTipo())[2]).' INTEGER
);';
                    
                }
                
            }
            
        }
        $pdo->exec($codigo);
        return $codigo;
    }
    
}

?>