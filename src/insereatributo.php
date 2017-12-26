<?php
session_start();
 
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
			
			if ($_SESSION['meuSoftwareId'] && $_POST['nomedoatributo'])
			{
				
				$atributo = new Atributo();
				$atributo->setNome($_POST['nomedoatributo']);
				$atributo->setTipo($_POST['tipodeatributo']);
				$atributo->setIndice($_POST['indice']);
				$atributo->setTipoDeRelacionamentoComObjeto($_POST['relacionamento_com_outro_tipo']);
				
				
				$objeto = new Objeto();
				$objeto->setId($_POST['objeto']);
				$objeto->addAtributo($atributo);
				
				$software = new Software();
				$software->setId($_SESSION['meuSoftwareId']);
				$software->addObjetoNaLista($objeto);

				$atributodao = new AtributoDAO();

				if($atributodao->inserir($objeto, $atributo)){
					echo "Atributo Inserido Com sucesso!";
					echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=software.php?idsoftware='.$_SESSION['meuSoftwareId'].'">';
					
				}else{
					echo "Atributo Não Inserido";
					echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=software.php?idsoftware='.$_SESSION['meuSoftwareId'].'">';
				}
				
				
				
				

				
			}
			
			
			?>

			
			
		</div>
		<div id="direita">
			
		
		</div>


	</div>

</body>
</html>