<?php
				
/**
 * Classe de visao para Objeto
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */				
class ObjetoView {
	public function mostraFormInserir(Software $software) {	
		echo '<form action="" method="post">
					<fieldset>
						<legend>
							Adicionar Objeto
						</legend>
						<input type="text" placeholder="Nome" name="nome" id="nome" />
						<input type="hidden" name="id_software" value="'.$software->getId().'" id="id_software" />
						<input type="submit" name="enviar_objeto" value="Cadastrar">
					</fieldset>
				</form>';
	}
}