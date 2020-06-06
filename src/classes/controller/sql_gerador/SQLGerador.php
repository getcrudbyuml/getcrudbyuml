<?php


class SQLGerador {
    private $software;
    private $listaDeArquivos;
    private $codigo;
    private $nivelRecursividade = 0;
    public const RECURSIVIDADE_MAXIMA = 0;
    public function getListaDeArquivos(){
        return $this->listaDeArquivos;
    }
    public function __construct(Software $software){
        $this->software = $software;
        $this->codigo = '';
    }
    public function getSQLSelect(Objeto $objeto){
        $strSqlSelect = "\n		SELECT\n        ";
        $campos = $this->campos($objeto);
        $strSqlSelect .= implode(", \n        ", $campos);
        $this->nivelRecursividade = 0;
        $from = $this->getFROM($objeto);
        
        array_unshift($from, $objeto->getNomeSnakeCase());
        $strSqlSelect .= "\n		FROM ".implode("\n		INNER JOIN ", $from);
        return $strSqlSelect;
        
    }
    public function getFROM(Objeto $objeto){
        
        $this->nivelRecursividade++;
        $from = array();
       
        
        $atributosObjetos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if ($atributo->isObjeto()) {
                $atributosObjetos[] = $atributo;
            }
        }
        
        foreach($atributosObjetos as $atributoObjeto){
            
            foreach($this->software->getObjetos() as $objeto2){
                if($objeto2->getNome() == $atributoObjeto->getTipo())
                {
                    foreach($objeto2->getAtributos() as $atributo3){
                        if($atributo3->getIndice() == Atributo::INDICE_PRIMARY){
                            $filtro = $atributoObjeto->getTipoSnakeCase().' ON '.
                                $atributoObjeto->getTipoSnakeCase().'.'.
                                $atributo3->getNome().' = '.
                                $objeto->getNomeSnakeCase().'.'.$atributo3->getNome().
                                '_'.$atributoObjeto->getNomeSnakeCase();
                            
                            $from[$filtro] = $filtro;
                            break;
                        }
                        
                    }
                    if($this->nivelRecursividade < self::RECURSIVIDADE_MAXIMA){
                        $from = array_merge($from, $this->getFROM($objeto2));

                    }
                    
                }
            }
            
        }
        return $from;
        
    }
    public function campos(Objeto $objeto){
        $lista = $this->getCamposComuns($objeto);
        $lista = array_merge($lista, $this->getCamposObjetos($objeto));
        return $lista;
    }
    
    public function getCamposComuns(Objeto $objeto)
    {
        $atributosComuns = array();
        $campos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if ($atributo->tipoListado()) {
                $atributosComuns[] = $atributo;
            }
        }
        foreach ($atributosComuns as $atributoComum) {
            $campos[$atributoComum->getNomeSnakeCase()] = $objeto->getNomeSnakeCase() . '.' . $atributoComum->getNomeSnakeCase() . '';
            
        }
        return $campos;
    }

    
    public function getCamposObjetos(Objeto $objeto){
        $this->nivelRecursividade++;
        
        
        $campos = array();
        $atributosObjetos = array();
        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->isObjeto()){
                $atributosObjetos[] = $atributo;
            }
        }
        foreach($atributosObjetos as $atributoObjeto){
            
            foreach($this->software->getObjetos() as $objetoTipo)
            {
                if($objetoTipo->getNome() == $atributoObjeto->getTipo())
                {
                    
                    foreach($objetoTipo->getAtributos() as $atributo3){
                        
                        if($atributo3->tipoListado()){
                            $campos[$atributo3->getNomeSnakeCase().'_'.$atributoObjeto->getTipoSnakeCase().'_'.$atributoObjeto->getNomeSnakeCase()] = $atributoObjeto->getTipoSnakeCase().'.'.$atributo3->getNomeSnakeCase().' as '.$atributo3->getNomeSnakeCase().'_'.$atributoObjeto->getTipoSnakeCase().'_'.$atributoObjeto->getNomeSnakeCase();
                        }else if($atributo3->isObjeto()){
                            
                            
                            foreach($this->software->getObjetos() as $objetoTipoDoTipo){
                                
                                if($atributo3->getTipo() == $objetoTipoDoTipo->getNome()){
                                    if($this->nivelRecursividade < self::RECURSIVIDADE_MAXIMA){
                                        
                                        $camposComuns = $this->getCamposComuns($objetoTipoDoTipo);
                                        
                                        foreach($camposComuns as $campoComum){
                                            $campos[explode(".",$campoComum)[1].'_'.$objetoTipoDoTipo->getNomeSnakeCase().'_'.$atributo3->getNomeSnakeCase()] = $campoComum.' as '.explode(".",$campoComum)[1].'_'.$objetoTipoDoTipo->getNomeSnakeCase().'_'.$atributo3->getNomeSnakeCase();

                                        }
                                        $campos = array_merge($campos,
                                            $this->getCamposObjetos($objetoTipoDoTipo));
                                    }
                                    
                                }
                            }
                            
                        }
                        
                    }
                }
                
            }
        }
        return $campos;
        
    }
    
}




?>