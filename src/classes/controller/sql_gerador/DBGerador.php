<?php

class DBGerador
{

    private $listaDeArquivos;

    private $software;

    private $diretorio;

    public static function main(Software $software, $diretorio)
    {
        $gerador = new DBGerador($software, $diretorio);
        $gerador->gerarCodigo();
    }

    public function __construct(Software $software, $diretorio)
    {
        $this->diretorio = $diretorio;
        $this->software = $software;
    }

    public function getListaDeArquivos()
    {
        return $this->listaDeArquivos;
    }

    public function gerarCodigo()
    {
        $this->geraINI();
        $this->geraBancoPG();
        $this->geraBancoSqlite();
        $this->criarArquivos();
    }

    private function criarArquivos()
    {
        $path = $this->diretorio;
        if(!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        
        foreach ($this->listaDeArquivos as $path => $codigo) {
            if (file_exists($path)) {
                unlink($path);
            }
            $file = fopen($path, "w+");
            fwrite($file, stripslashes($codigo));
            fclose($file);
        }
        
        $bdNome = $this->diretorio . '/' . $this->software->getNomeSnakeCase() . '.db';
        if (file_exists($bdNome)) {
            unlink($bdNome);
        }
        
        $pdo = new PDO('sqlite:' . $bdNome);
        $pdo->exec($codigo);
        
    }

    public function geraINI()
    {
        $codigo = '
;configurações do banco de dados.
;Banco de regras de negócio do sistema.
            
sgdb = sqlite
host = localhost
porta = 5432
bd_nome = ../../../' . $this->software->getNomeSnakeCase() . '.db
usuario = root
senha = 123
';
        $path = $this->diretorio . '/' . $this->software->getNomeSnakeCase() . '_bd.ini';
        $this->listaDeArquivos[$path] = $codigo;
        return $codigo;
    }

    public function geraBancoPG()
    {
        $objetosNN = array();
        $objetos1N = array();
        $codigo = '';
        foreach ($this->software->getObjetos() as $objeto) {
            $codigo .= '
CREATE TABLE ' . $objeto->getNomeSnakeCase();
            $codigo .= ' (';
            $i = 0;
            foreach ($objeto->getAtributos() as $atributo) {
                $i ++;
                $flagPulei = false;
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY && $atributo->tipoListado()) {
                    $codigo .= '
    ' . $atributo->getNomeSnakeCase() . ' serial NOT NULL';
                } else if ($atributo->tipoListado()) {
                    $codigo .= '
    ' . $atributo->getNomeSnakeCase() . ' ' . $atributo->getTipoPostgres() . ' ';
                } else if ($atributo->isArrayNN()) {
                    $objetosNN[] = $objeto;
                    $flagPulei = true;
                } else if ($atributo->isArray1N()) {
                    $objetos1N[] = $objeto;
                    $flagPulei = true;
                } else if ($atributo->isObjeto()) {
                    $codigo .= '
    id_' . $atributo->getTipoSnakeCase() . '_' . $atributo->getNomeSnakeCase() . ' integer NOT NULL';
                } else {
                    // Tipo Array Comum não implementado
                    $flagPulei = true;
                }
                if ($i == count($objeto->getAtributos())) {
                    foreach ($objeto->getAtributos() as $atributo) {
                        if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                            if (! $flagPulei) {
                                $codigo .= ', ';
                            }
                            $codigo .= '
    CONSTRAINT pk_' . strtolower($objeto->getNome()) . '_' . $atributo->getNomeSnakeCase() . '
    PRIMARY KEY (' . $atributo->getNomeSnakeCase() . ')';
                            break;
                        }
                    }
                    $codigo .= "";
                    continue;
                }
                if (! $flagPulei) {
                    $codigo .= ", ";
                }
            }

            $codigo .= ");\n";
        }
        foreach ($objetosNN as $objeto) {

            // explode(' ', $string);
            foreach ($objeto->getAtributos() as $atributo) {
                if ($atributo->isArrayNN()) {
                    $codigo .= '
CREATE TABLE ' . $objeto->getNomeSnakeCase() . '_' . strtolower(explode(" ", $atributo->getTipoSnakeCase())[2]);
                    $codigo .= '(
    id serial NOT NULL,
    id_' . $objeto->getNomeSnakeCase() . ' integer NOT NULL,
    id_' . $atributo->getArrayTipoSnakeCase() . ' integer NOT NULL,
    CONSTRAINT pk_' . $objeto->getNomeSnakeCase() . '_' . $atributo->getArrayTipoSnakeCase() . '_id 
    PRIMARY KEY (id),
    CONSTRAINT fk_' . $objeto->getNomeSnakeCase() . '_id FOREIGN KEY (id_' . $objeto->getNomeSnakeCase() . ') 
    REFERENCES ' . $objeto->getNomeSnakeCase() . ' (id) 
    MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION,
    CONSTRAINT fk_' . $atributo->getArrayTipoSnakeCase() . '_id 
    FOREIGN KEY (id_' . $atributo->getArrayTipoSnakeCase() . ') REFERENCES ' . $atributo->getArrayTipoSnakeCase() . ' (id) 
    MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION
);';
                }
            }
        }
        // Adicionar outras chaves estrangeiras.
        foreach ($this->software->getObjetos() as $objeto) {
            foreach ($objeto->getAtributos() as $atributo) {
                if ($atributo->isObjeto()) {
                    foreach ($this->software->getObjetos() as $objeto2) {
                        if ($atributo->getTipo() == $objeto2->getNome()) {
                            $objetoDoAtributo = $objeto2;
                            break;
                        }
                    }
                    foreach ($objetoDoAtributo->getAtributos() as $atributo3) {
                        if ($atributo3->getIndice() == Atributo::INDICE_PRIMARY) {
                            $atributoPrimary = $atributo3;
                            break;
                        }
                    }
                    $codigo .= '

ALTER TABLE ' . $objeto->getNomeSnakeCase() . ' 
    ADD CONSTRAINT fk_' . strtolower($objeto->getNome()) . '_' . strtolower($atributo->getTipo()) . '_' . $atributo->getNomeSnakeCase() . ' FOREIGN KEY (id_' . strtolower($atributo->getTipo()) . '_' . $atributo->getNomeSnakeCase() . ')
    REFERENCES ' . strtolower($atributo->getTipo()) . ' (' . $atributoPrimary->getNome() . ');
';
                }
            }
        }

        foreach ($objetos1N as $objeto) {
            $atributoPK = null;
            foreach ($objeto->getAtributos() as $atributo) {
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    $atributoPK = $atributo;
                }
            }
            foreach ($objeto->getAtributos() as $atributo) {

                if ($atributo->isArray1N()) {
                    if ($atributoPK != null) {

                        $codigo .= '
ALTER TABLE ' . $atributo->getArrayTipoSnakeCase() . ' ADD COLUMN  ' . $atributoPK->getNomeSnakeCase() . '_' . $objeto->getNomeSnakeCase() . '_' . $atributo->getNomeSnakeCase() . '  integer ;';

                        $codigo .= '

ALTER TABLE ' . $atributo->getArrayTipoSnakeCase() . ' 
    ADD CONSTRAINT
    fk_' . $atributoPK->getNomeSnakeCase() . '_' . $objeto->getNomeSnakeCase() . '_' . $atributo->getNomeSnakeCase() . ' FOREIGN KEY (' . $atributoPK->getNomeSnakeCase() . '_' . $objeto->getNomeSnakeCase() . '_' . $atributo->getNomeSnakeCase() . ')
    REFERENCES ' . $objeto->getNomeSnakeCase() . ' (' . $atributoPK->getNomeSnakeCase() . ');
';
                    }
                }
            }
        }
        $path = $this->diretorio . '/' . $this->software->getNomeSnakeCase() . '_banco_pg.sql';
        $this->listaDeArquivos[$path] = $codigo;
        return $codigo;
    }

    public function geraBancoSqlite()
    {
        $objetosNN = array();
        $objetos1N = array();

        
        $codigo = '';
        foreach ($this->software->getObjetos() as $objeto) {
            $codigo .= '
CREATE TABLE ' . $objeto->getNomeSnakeCase();
            $codigo .= " (\n";
            $i = 0;
            $atributosComuns = array();

            foreach ($objeto->getAtributos() as $atributo) {
                if ($atributo->isArrayNN()) {

                    $objetosNN[] = $objeto;
                } else if ($atributo->isArray1N()) {
                    $objetos1N[] = $objeto;
                } else {

                    $atributosComuns[] = $atributo;
                }
            }
            foreach ($atributosComuns as $atributo) {
                $i ++;
                if ($atributo->tipoListado()) {
                    $codigo .= '    ' . $atributo->getNomeSnakeCase() . ' ' . $atributo->getTipoSqlite() . ' ';
                } else if ($atributo->isObjeto()) {
                    $codigo .= '    id_' . $atributo->getTipoSnakeCase() . '_' . $atributo->getNomeSnakeCase() . ' INTEGER NOT NULL';
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

        foreach ($objetosNN as $objeto) {

            // explode(' ', $string);
            foreach ($objeto->getAtributos() as $atributo) {
                if (substr($atributo->getTipo(), 0, 6) == 'Array ') {
                    $codigo .= '
CREATE TABLE ' . $objeto->getNomeSnakeCase() . '_' . strtolower(explode(" ", $atributo->getTipo())[2]);
                    $codigo .= '(
    id 	INTEGER PRIMARY KEY AUTOINCREMENT,
    id_' . $objeto->getNomeSnakeCase() . ' INTEGER,
    id_' . strtolower(explode(" ", $atributo->getTipo())[2]) . ' INTEGER
);';
                }
            }
        }
        foreach ($objetos1N as $objeto) {
            $atributoPK = null;
            foreach ($objeto->getAtributos() as $atributo) {
                if ($atributo->getIndice() == Atributo::INDICE_PRIMARY) {
                    $atributoPK = $atributo;
                }
            }
            foreach ($objeto->getAtributos() as $atributo) {

                if ($atributo->isArray1N()) {
                    if ($atributoPK != null) {

                        $codigo .= '
ALTER TABLE ' . $atributo->getArrayTipoSnakeCase() . ' ADD COLUMN  ' . $atributoPK->getNomeSnakeCase() . '_' . $objeto->getNomeSnakeCase() . '_' . $atributo->getNomeSnakeCase() . '  INTEGER ;';
                    }
                }
            }
        }
        
        
        $path = $this->diretorio . '/' . $this->software->getNomeSnakeCase() . '_banco_sqlite.sql';
        $this->listaDeArquivos[$path] = $codigo;
        return $codigo;
        
    }
}

?>