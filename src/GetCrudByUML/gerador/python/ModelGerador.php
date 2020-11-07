<?php

namespace GetCrudByUML\gerador\python;

use GetCrudByUML\model\Objeto;
use GetCrudByUML\model\Software;
use GetCrudByUML\gerador\sqlGerador\SQLGerador;

class ModelGerador
{

    private $software;

    private $listaDeArquivos;

    public static function main(Software $software)
    {
        $gerador = new ModelGerador($software);
        return $gerador->geraCodigo();
    }

    public function __construct(Software $software)
    {
        $this->software = $software;
    }

    /**
     * Selecione uma linguagem
     *
     * @param int $linguagem
     */
    public function geraCodigo()
    {
        $this->listaDeArquivos = array();
        foreach($this->software->getObjetos() as $objeto){
            $this->geraModel($objeto);
        }        
        return $this->listaDeArquivos;
    }
    
    public function geraModel(Objeto $objeto)
    {
        $codigo = '

import sqlite3

class '.ucfirst($objeto->getNome()).':
    #definindo a função de listar
    def listar():
        #conectando com o banco
        conn = sqlite3.connect(\''.$this->software->getNomeSnakeCase().'.db\')
        cursor = conn.cursor()
        #Listando dados
        cursor.execute("""
        ';
        
        $sqlGerador = new SQLGerador($this->software);
        $codigo .= $sqlGerador->getSQLSelect($objeto);
        
        $codigo .= '
        """)
        
        #fazendo a lista
        for linha in cursor.fetchall():
            print(linha)
            
        conn.close()
    def cadastrar():
        conn = sqlite3.connect(\''.$this->software->getNomeSnakeCase().'.db\')
        cursor = conn.cursor()
        p_nome = input(\'Nome: \')
        # inserindo dados na tabela
        cursor.execute("""';
        $codigo .= '
        "INSERT INTO ' . $objeto->getNomeSnakeCase() . '(';
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
        
        $codigo .= '
        """, (p_nome))
        conn.commit()
        conn.close

                
';

        
        
        $this->listaDeArquivos[$objeto->getNomeSnakeCase().'.py'] = $codigo;
        return $codigo;
    }

}

?>