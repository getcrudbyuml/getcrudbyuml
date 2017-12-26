<?php
 
function __autoload($classe) {
	if (file_exists ( 'classes/dao/' . $classe . '.php' )){
		include_once 'classes/dao/' . $classe . '.php';
	}
	else if (file_exists ( 'classes/model/' . $classe . '.php' )){
		include_once 'classes/model/' . $classe . '.php';
	}
	else if (file_exists ( 'classes/controller/' . $classe . '.php' )){
		include_once 'classes/controller/' . $classe . '.php';
	}
	else if (file_exists ( 'classes/util/' . $classe . '.php' )){
		include_once 'classes/util/' . $classe . '.php';
	}
	else if (file_exists ( 'classes/view/' . $classe . '.php' )){
		include_once 'classes/view/' . $classe . '.php';
	}

}

?>
<!DOCTYPE>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Escritor De Software</title>
<link rel="stylesheet" type="text/css" href="style/style.css"/>
</head>

<body>

	<div id="topo">
		   <img src="images/logo.png" alt="" />
	</div>

	<div id="conteiner">
		<div id="esquerda">

			<?php
			
			
				$controller = new ObjetoController();
				$controller->listar();
			
			?>
			
		</div>
		<div id="direita">
			
			<?php 
			
			$controller->cadastrar();
			
			?>
			<hr/>
			<form action="insereatributo.php" method="post">
			<fieldset>
				<legend>Inserir Atributo a um Objeto</legend>
				<label for="nomedoatributo">Nome do Atributo</label>
				<input type="text" id="nomedoatributo" name="nomedoatributo" />
				<label for="objeto">Selecione um objeto</label>
				<select id="objeto" name="objeto" >
					
						<?php 
						if($software->getObjetos())
						{
			
						foreach ($software->getObjetos() as $objeto)
							{
								echo '<option value="'.$objeto->getId().'">'.$objeto->getNome().'</option>';
					
							}
						}
						?>
					
				</select>
				
				<label for="tipodeatributo">Tipo </label>
				<select id="tipodeatributo" name="tipodeatributo">
				
					<option value="string">Texto Nativo</option>
					<option value="int">Inteiro Nativo</option>
					<option value="float">Ponto Flutuante Nativo</option>
					<option value="file">Arquivo Nativo</option>
					<?php 
						if($software->getObjetos())
						{
			
						foreach ($software->getObjetos() as $objeto)
							{
								echo '<option value="'.$objeto->getNome().'">'.$objeto->getNome().' Criado pelo UsuÃ¡rio</option>';
					
							}
						}
					?>
					
					
				</select>
				<label for="indice">Ã�ndice</label>
				
				<select id="indice" name="indice">
					<option value="padrao">PadrÃ£o</option>
					<option value="primary_key">Primary key</option>
				</select>
				<label for="relacionamento_com_outro_tipo">Relacionamento com outro tipo</label>
				<select name="relacionamento_com_outro_tipo" id="relacionamento_com_outro_tipo">
					<option value="padrao">PadrÃ£o</option>
					<option value="n:n">N:N</option>
					<option value="n:1">N:1</option>
					<option value="1:1">1:1</option>
		
				</select>

				<input type="submit" value="Inserir Atributo" />
				
			
			</fieldset>
			
			
			</form>


		</div>
	</div>
</body>
</html>