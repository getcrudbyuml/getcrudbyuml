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
        	if(isset($_GET['pagina'])){
        	    switch ($_GET['pagina']){
        	        case 'software':
        	            $controller = new SoftwareController();
        	            break;
        	        case 'objeto':
        	            $controller = new ObjetoController();
        	            break;
        	        case 'atributo':
        	            $controller = new AtributoController();
        	            break;
        	        default:
        	            $controller = new SoftwareController();
        	            break;
        	    }
        	}else{
        	    $controller = new SoftwareController();
        	}
        	
        	$controller->listar();

        	?>


        </div>
       	<div id="direita">
        	<?php 
        	   
        	   $controller->cadastrar();
        	
        	?>

	    </div>

        
    </div>

</body>
</html>