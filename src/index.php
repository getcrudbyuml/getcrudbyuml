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

?>

<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link href="css/simple-sidebar.css" rel="stylesheet">
<title>EscritorDeSoftware</title>
</head>
<body>
  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading">writing.jefponte.com.br</div>
<?php 

$softwareController = new SoftwareController();
$softwareController->cadastrar();
$softwareController->listar();


?>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="btn btn-primary" id="menu-toggle">Toggle Menu</button>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item active">
              <a class="nav-link" href="./">Início</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Exportar/Importar</a>
            </li>
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Configurações
              </a>

              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Meus Dados</a>
                <a class="dropdown-item" href="#">Mudar Senha</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Sair</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>

      <div class="container-fluid">
        <h1 class="mt-4">Escritor De Software</h1>
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
        
    }
} else {
    $controller = new SoftwareController();
    $controller->selecionar();
}

?> 
      </div>
    </div>
    <!-- /#page-content-wrapper -->

  </div>
  <!-- /#wrapper -->

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
  <!-- Menu Toggle Script -->
  <script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });

    $(".campmo-selecionado").focus();

  </script>
</body>
</html>