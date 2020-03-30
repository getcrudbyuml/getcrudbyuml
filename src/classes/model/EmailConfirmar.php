
<?php
            
/**
 * Classe feita para manipulação do objeto EmailConfirmar
 * feita automaticamente com programa gerador de software inventado por
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 */
class EmailConfirmar {
	private $id;
	private $email;
	private $codigo;
	private $confirmado;
    public function __construct(){

    }
	public function setId($id) {
		$this->id = $id;
	}
		    
	public function getId() {
		return $this->id;
	}
	public function setEmail($email) {
		$this->email = $email;
	}
		    
	public function getEmail() {
		return $this->email;
	}
	public function setCodigo($codigo) {
		$this->codigo = $codigo;
	}
		    
	public function getCodigo() {
		return $this->codigo;
	}
	public function setConfirmado($confirmado) {
		$this->confirmado = $confirmado;
	}
		    
	public function getConfirmado() {
		return $this->confirmado;
	}
	public function __toString(){
	    return $this->id.' - '.$this->email.' - '.$this->codigo.' - '.$this->confirmado;
	}
                

}
?>