<?php
				
/**
 * Classe de visao para Objeto
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */				
class ObjetoView {
	public function mostraFormInserir() {	
		echo '<form action="" method="post">
					<fieldset>
						<legend>
							Adicionar Objeto
						</legend>
						<label for="nome">nome:</label>
						<input type="text" name="nome" id="nome" />
						<label for="idsoftware">idsoftware:</label>
						<input type="text" name="idsoftware" id="idsoftware" />
						<input type="submit" name="cadastrar" value="Cadastrar">
					</fieldset>
				</form>';
	}	
}