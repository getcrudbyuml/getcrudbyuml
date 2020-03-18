<?php


class SQLGerador {
    private $software;
    private $listaDeArquivos;
    private $codigo;
    private $nivelRecursividade = 0;
    
    public function getListaDeArquivos(){
        return $this->listaDeArquivos;
    }
    public function __construct(Software $software){
        $this->software = $software;
        $this->codigo = '';
    }
    public function getFROM(){
        $from = array();
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
                                    if($this->nivelRecursividade < 10){
                                        
                                        $camposComuns = $this->getCamposComuns($objetoTipoDoTipo);
                                        
                                        foreach($camposComuns as $campoComum){
                                            $campos[explode(".",$campoComum)[1].'_'.$objetoTipoDoTipo->getNomeSnakeCase().'_'.$atributo3->getNomeSnakeCase()] = $campoComum.' as '.explode(".",$campoComum)[1].'_'.$objetoTipoDoTipo->getNomeSnakeCase().'_'.$atributo3->getNomeSnakeCase();
                                        }
                                        $campos = array_merge($campos,
                                            $this->getCamposObjetos($objetoTipoDoTipo));
                                    }else{
                                        echo "Nível de Recursividade Excedido";
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