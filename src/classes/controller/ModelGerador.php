<?php



class ModelGerador{
    private $software;
    private $listaDeArquivos;

    
    public function getListaDeArquivos(){
        return $this->listaDeArquivos;
    }
    public function __construct(Software $software){
        $this->software = $software;
    }
    /**
     * Selecione uma linguagem
     * @param int $linguagem
     */
    public function gerarCodigo(){
        $this->geraCodigoPHP();
        $this->geraCodigoJava();
        
    }
    private function geraCodigoJava(){
        
        $path = 'sistemas/'.$this->software->getNome().'/java/'.$this->software->getNome().'/src/main/java';
        foreach($this->software->getObjetos() as $objeto){
            $codigo = $this->geraModelJava($objeto, $this->software);
            $caminho = $path.'/br/com/escritordesoftware/'.strtolower($this->software->getNome()).'/model/' . ucfirst($objeto->getNome()) . '.java';
            $this->listaDeArquivos[$caminho] = $codigo;
        }
    }
    private function geraCodigoPHP(){
        $path = 'sistemas/'.$this->software->getNome().'/php/src';
        foreach($this->software->getObjetos() as $objeto){
            $codigo = $this->geraModelPHP($objeto, $this->software);
            $caminho = $path.'/classes/model/' . strtoupper(substr($objeto->getNome(), 0, 1)) . substr($objeto->getNome(), 1, 100) . '.php';
            $this->listaDeArquivos[$caminho] = $codigo;
        }
    }
    private function geraModelPHP(Objeto $objeto, Software $software)
    {
        $nomeDoObjetoMa = strtoupper(substr($objeto->getNome(), 0, 1)) . substr($objeto->getNome(), 1, 100);
        
        $codigo = '
<?php
            
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
            $codigo .= '
    public function __construct(){
';
            foreach ($objeto->getAtributos() as $atributo) {
                if(substr(trim($atributo->getTipo()), 0, 6) == 'Array '){
                    $atrb = explode(' ', $atributo->getTipo())[2];
                    $codigo .= '
        $this->'.$atributo->getNome().' = array();';
                    
                }else if($atributo->getTipo() == Atributo::TIPO_FLOAT || $atributo->getTipo() == Atributo::TIPO_INT || $atributo->getTipo() == Atributo::TIPO_STRING){
                    continue;
                }
                else
                {
                    $codigo .= '
        $this->'.strtolower($atributo->getNome()).' = new '.ucfirst($atributo->getTipo()).'();';
                    
                }
            }
            $codigo .= '
    }';
            foreach ($objeto->getAtributos() as $atributo) {
                
                $nome = strtolower($atributo->getNome());
                $nome2 = strtoupper(substr($atributo->getNome(), 0, 1)) . substr($atributo->getNome(), 1, 100);
                
                if ($atributo->getTipo() == Atributo::TIPO_INT || $atributo->getTipo() == Atributo::TIPO_FLOAT || $atributo->getTipo() == Atributo::TIPO_STRING)
                {
                    
                    $codigo .= '
	public function set' . $nome2 . '($' . $nome . ') {';
                    $codigo .= '
		$this->' . $nome . ' = $' . $nome . ';
	}
		    
	public function get' . $nome2 . '() {
		return $this->' . $nome . ';
	}';
                    
                }
                else {
                    
                    if(substr(trim($atributo->getTipo()), 0, 6) == 'Array '){
                        $atrb = explode(' ', $atributo->getTipo())[2];
                        $codigo .= '
                            
    public function add'.ucfirst($atrb).'('.ucfirst($atrb).' $'.strtolower($atrb).'){
        $this->'.$nome.'[] = $'.strtolower($atrb).';
            
    }
	public function get' . $nome2 . '() {
		return $this->' . $nome . ';
	}';
                        
                        
                    }else{
                        $codigo .= '
	public function set' . $nome2 . '(' . $atributo->getTipo() . ' $' . $nome . ') {';
                        
                        $codigo .= '
		$this->' . $nome . ' = $' . $nome . ';
	}
		    
	public function get' . $nome2 . '() {
		return $this->' . $nome . ';
	}';
                    }
                    
                    
                }
                
            }
            $codigo .= '
	public function __toString(){
	    return ';
            $i = count($objeto->getAtributos());
            foreach ($objeto->getAtributos() as $atributo) {
                $i--;
                $codigo .= '$this->'.$atributo->getNome();
                if($i != 0){
                    $codigo .= '.\' - \'.';
                }
                
            }
            $codigo .= ';
	}
                
';
            
        }
        
        $codigo .= '
}
?>';
        
        
        return $codigo;
    }
    private function geraModelJava(Objeto $objeto, Software $software)
    {
        $codigo = 'package br.com.escritordesoftware.'.strtolower($software->getNome()).'.model;
';
        if($objeto->possuiArray()){
        $codigo .= '
import java.util.ArrayList;
';
        }
        $codigo .= '

/**
 * Classe feita para manipulação do objeto ' . ucfirst ($objeto->getNome()) . '
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
public class ' . ucfirst ($objeto->getNome()) . ' {';
        foreach ($objeto->getAtributos() as $atributo) {
            
                $codigo .= '
	private '.$atributo->getTipoJava() . ' '. $atributo->getNome() . ';';
                
        }
        
        
        $codigo .= '
    public ' . ucfirst ($objeto->getNome()) . '(){
';
        foreach ($objeto->getAtributos() as $atributo) {
            if($atributo->isArray()){
                    $codigo .= '
        this.'.$atributo->getNome().' = new ArrayList<'.$atributo->getTipoDeArray().'>();';
                    
            }else if($atributo->tipoListado()){
                
                continue;
            }
            else
            {
                    $codigo .= '
        this.'.strtolower($atributo->getNome()).' = new '.ucfirst($atributo->getTipo()).'();';
                    
            }
            
        }
            $codigo .= '
    }';
        foreach ($objeto->getAtributos() as $atributo) {
                
            
            if ($atributo->tipoListado())
            {
                
                    $codigo .= '
	public void set' . ucfirst ($atributo->getNome()) .'(' .$atributo->getTipoJava().' '. strtolower($atributo->getNome()) . ') {';
                    $codigo .= '
		this.' . strtolower($atributo->getNome()) . ' = ' . strtolower($atributo->getNome()) . ';
	}
		    
	public '.$atributo->getTipoJava().' get' . ucfirst ($atributo->getNome()) . '() {
		return this.' . strtolower($atributo->getNome()) . ';
	}';
                    
            }
            else if($atributo->isArray()) {
                    
                    
                    $codigo .= '
                            
    public void add'.ucfirst($atributo->getTipoDeArray()).'('.ucfirst($atributo->getTipoDeArray()).' '.strtolower($atributo->getTipoDeArray()).'){
        this.'.strtolower($atributo->getNome()).'.add('.strtolower($atributo->getTipoDeArray()).');
            
    }
	public ArrayList<'.$atributo->getTipoDeArray().'> get' . ucfirst($atributo->getNome()) . '() {
		return this.' . strtolower($atributo->getNome()) . ';
	}';
                        
                        
                    
                }else{
                        $codigo .= '
	public void set'. ucfirst ($atributo->getNome()) .'(' . $atributo->getTipo() . ' ' . strtolower($atributo->getNome()) . ') {';
                        
                        $codigo .= '
		this.' . strtolower($atributo->getNome()) . ' = ' . strtolower($atributo->getNome()) . ';
	}
		    
	public '.$atributo->getTipo().' get' . ucfirst($atributo->getNome()) . '() {
		return this.' . strtolower($atributo->getNome()) . ';
	}';
                    
                }
                    
                    
                
            }
            $codigo .= '
	@Override
	public String toString() {
		return ';
            $i = count($objeto->getAtributos());
            foreach ($objeto->getAtributos() as $atributo) {
                $i--;
                $codigo .= 'this.'.$atributo->getNome();
                if($i != 0){
                    $codigo .= '+" - "+';
                }
                
            }
            $codigo .= ';
	}
                
';
            
        
        $codigo .= '
}
';
        
        
        return $codigo;
    }
    
}


?>