<?php
				
/**
 * Classe de visao para Software
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */				
class SoftwareView {
	public function mostraFormInserir() {	
		echo '<h1>Criar Novo Software</h1>
            <form action="" method="post">
            	<fieldset>
                	<legend>Criar Novo Software</legend>
    	            <input type="text" placeholder="Nome" id="nome" name="nome" />
                    <input type="submit" name="enviar" value="Criar Software"  />
                    
                </fieldset>
            </form>';
	}	
}