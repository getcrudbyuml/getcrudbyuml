<?php

function autoload($classe) {
    
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
spl_autoload_register('autoload');

$sessao = new Sessao();
if (isset($_GET["sair"])) {
    $sessao->mataSessao();
    header("Location:./");
}

?>

<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link href="css/simple-sidebar.css" rel="stylesheet">
<link href="css/selectize.bootstrap3.css" rel="stylesheet">
<title>EscritorDeSoftware</title>
</head>
<body>
  <div class="d-flex" id="wrapper">

<?php 

SideBarController::main($sessao);

?>

    <!-- Page Content -->
    <div id="page-content-wrapper">
<?php 

NavBarController::main($sessao);


?>

      <div class="container-fluid">    
<?php
              
if (isset($_GET['pagina'])) {
    switch ($_GET['pagina']) {
        case 'software':
            $controller = new SoftwareController();
            $controller->selecionar();
            $controller->deletar();
            $controller->editar();
            
            break;
        case 'objeto':
            ObjetoController::main();
            break;
        case 'atributo':
            AtributoController::main();
            break;
        case 'usuario':
            UsuarioController::main();
            break;
        case 'login':
            $controller = new UsuarioController();
            $controller->login();
            break;
        
    }
} else {

    HomeController::main();
}

?> 
      </div>
    </div>
    <!-- /#page-content-wrapper -->

  </div>
  <!-- /#wrapper -->

	<script src="js/jquery-3.4.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="js/selectize.js"></script>
	<script src="js/escritor.js"></script>
  
</body>
</html>