<?php

function __autoload($classe)
{
    if (file_exists('classes/dao/' . $classe . '.php')) {
        include_once 'classes/dao/' . $classe . '.php';
    } else if (file_exists('classes/model/' . $classe . '.php')) {
        include_once 'classes/model/' . $classe . '.php';
    } else if (file_exists('classes/controller/' . $classe . '.php')) {
        include_once 'classes/controller/' . $classe . '.php';
    } else if (file_exists('classes/util/' . $classe . '.php')) {
        include_once 'classes/util/' . $classe . '.php';
    } else if (file_exists('classes/view/' . $classe . '.php')) {
        include_once 'classes/view/' . $classe . '.php';
    }
}

?>

<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport"
	content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Bootstrap CSS -->
<link rel="stylesheet"
	href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<title>EscritorDeSoftware</title>
</head>
<body>
<!-- 	<nav class="navbar navbar-expand-lg navbar-light bg-light"> -->
<!-- 		<a class="navbar-brand" href="#">EscritorDeSoftware</a> -->
<!-- 		<button class="navbar-toggler" type="button" data-toggle="collapse" -->
<!-- 			data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" -->
<!-- 			aria-expanded="false" aria-label="Alterna navegação"> -->
<!-- 			<span class="navbar-toggler-icon"></span> -->
<!-- 		</button> -->
<!-- 		<div class="collapse navbar-collapse" id="navbarNavAltMarkup"> -->
<!-- 			<div class="navbar-nav"> -->
<!-- 				<a class="nav-item nav-link" href="?pagina=software">Software</a><a -->
<!-- 					class="nav-item nav-link" href="?pagina=objeto">Objeto</a><a -->
<!-- 					class="nav-item nav-link" href="?pagina=atributo">Atributo</a><a -->
<!-- 					class="nav-item nav-link" href="?pagina=usuario">Usuario</a> -->

<!-- 			</div> -->
<!-- 		</div> -->
<!-- 	</nav> -->
	
	<main role="main">

      <section class="jumbotron text-center">
        <div class="container">
          <h1 class="jumbotron-heading">Escritor de Software</h1>
          <p class="lead text-muted">Algo curto e direto, sobre a coleção abaixo (conteúdo, criador e etc). Faça com que seja curto e legal, mas não tão curto ao ponto de as pessoas pularem ele.</p>

        </div>
      </section>
      
        <div class="album py-5 bg-light">
            <div class="container">
              
              <?php
if (isset($_GET['pagina'])) {
    switch ($_GET['pagina']) {
        case 'software':
            SoftwareController::main();
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
        default:
            SoftwareController::main();
            break;
    }
} else {
    SoftwareController::main();
}

?>
<!--               		<div class="col-md-4"> -->
<!--                       <div class="card mb-4 shadow-sm"> -->
<!--                         <div class="card-body"> -->
<!--                           <p class="card-text">Este é um card maior e que suporta texto abaixo, como uma introdução mais natural ao conteúdo adicional.</p> -->
<!--                           <div class="d-flex justify-content-between align-items-center"> -->
<!--                             <div class="btn-group"> -->
<!--                               <button type="button" class="btn btn-sm btn-outline-secondary">Ver</button> -->
<!--                               <button type="button" class="btn btn-sm btn-outline-secondary">Editar</button> -->
<!--                             </div> -->
<!--                             <small class="text-muted">9 mins</small> -->
<!--                           </div> -->
<!--                         </div> -->
<!--                       </div> -->
<!--                     </div> -->
              
              </div>
                
            </div>
      
     </main>            
            
            
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>