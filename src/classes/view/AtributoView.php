<?php
				
/**
 * Classe de visao para Atributo
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */				
class AtributoView {
	public function mostraFormInserir() {	
		echo '<form action="" method="post">
					<fieldset>
						<legend>
							Adicionar Atributo
						</legend>
						<label for="nome">nome:</label>
						<input type="text" name="nome" id="nome" />
						<label for="tipo">tipo:</label>
						<input type="text" name="tipo" id="tipo" />
						<label for="relacionamento">relacionamento:</label>
						<input type="text" name="relacionamento" id="relacionamento" />
						<label for="idobjeto">idobjeto:</label>
						<input type="text" name="idobjeto" id="idobjeto" />
						<input type="submit" name="cadastrar" value="Cadastrar">
					</fieldset>
				</form>';
	}	
}