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
		<?php
		if ($_SESSION['meuSoftwareId'] && $_POST['nomedoobjeto']) 
		{ // vou fechar isso lá no final

			$objetodao = new ObjetoDAO();
			
			$software = new Software ();
			$software->setId ($_SESSION['meuSoftwareId']);
			
		?>	
				
		<div id="esquerda">
			<?php
				$objeto = new Objeto();
				$objeto->setNome($_POST['nomedoobjeto']) ;
			
				if($objetodao->inserir($objeto, $software)){
					echo "Objeto inserido com sucesso!";
					echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=software.php?idsoftware='.$_SESSION['meuSoftwareId'].'">';
				}else{
					echo "Objeto não foi inserido";
					
					
				}
				
				
			
			
			
			?>
		</div>
		<div id="direita">
			
		</div>
		<?php
		}
		// fechando o if($_GET['idsoftware'])
		else{
			echo "Preencha o formulário Corretamente!";			
			echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php">';
		}
		?>
			


	</div>

</body>
</html>