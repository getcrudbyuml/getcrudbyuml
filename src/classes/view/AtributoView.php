<?php
				
/**
 * Classe de visao para Atributo
 * @author Jefferson Uchôa Ponte <j.pontee@gmail.com>
 *
 */				
class AtributoView {
	public function mostraFormInserir(Objeto $objeto) {	
		echo '<form action="" method="post">
					<fieldset>
					<legend>Inserir Atributo a um Objeto</legend>
					<label for="nome">Nome do Atributo</label>
					<input type="text" id="nome" name="nome" />
					
					
					<label for="tipo">Tipo </label>
					<select id="tipo" name="tipo">
					
						<option value="string">Texto</option>
						<option value="int">Inteiro</option>
					</select>
				
					<label for="indice">Índice</label>
					
					<select id="indice" name="indice">
						<option value="padrao">-</option>
						<option value="primary_key">Primary key</option>
					</select>
				<input type="hidden" name="id_objeto" value="'.$objeto->getId().'" id="id_objeto" />
				<input type="submit" name="envia_atributo" value="Cadastrar">
			</fieldset>
		</form>';
	}	
}