<?php
define("DB_INI", "../escritordesoftware_bd.ini");


use GetCrudByUML\controller\MainController;
use GetCrudByUML\controller\NavBarController;
use GetCrudByUML\controller\SideBarController;
use GetCrudByUML\custom\controller\UsuarioCustomController;
use GetCrudByUML\dao\AuditoriaDAO;
use GetCrudByUML\model\Auditoria;
use GetCrudByUML\util\Sessao;

$sessao = new Sessao();

$ipVisitante = $_SERVER["REMOTE_ADDR"];

$auditoria = new Auditoria();
$auditoria->setInfoSessao($sessao->__toString());
$auditoria->setIpVisitante($ipVisitante);
$auditoria->setData(date('Y-m-d G:I:s'));
$strGet = '';
if(isset($_GET)){
    foreach($_GET as $chave => $valor){
        $strGet .= '\''.$chave.'\' : \''.$valor.'\', ';
    }
}
if($sessao->getNivelAcesso() != Sessao::NIVEL_DESLOGADO){
    $auditoria->setPagina($strGet);
    $auditoriaDao = new AuditoriaDAO();
    $auditoriaDao->inserir($auditoria);
    
}



if (isset($_GET["sair"])) {
    $sessao->mataSessao();
    echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php">';
    
}
if (isset($_GET['ajax'])) {
    
    switch ($_GET['ajax']){
        case 'usuario':
            $controller = new UsuarioCustomController();
            $controller->mainAjax();
            break;
        default:
            echo '<p>Página solicitada não encontrada.</p>';
            break;
    }
    


    return;
}
?>

<!doctype html>
<html lang="pt-br">
<head>
<meta name="lomadee-verification" content="22619239" />
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link href="css/simple-sidebar.css" rel="stylesheet">
<link href="css/selectize.bootstrap3.css" rel="stylesheet">
<link rel="stylesheet" href="css/chat.css">
<title>getCrudByUml</title>
<script data-ad-client="ca-pub-1493485857193768" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>
<body>
	<div class="d-flex" id="wrapper">

<?php

SideBarController::main($sessao);

?>


		<div id="page-content-wrapper">
<?php

NavBarController::main($sessao);

?>

			<div class="container-fluid m-3">    
<?php

MainController::main($sessao);

?> 
			</div>
		</div>
		
<?php 


// MainChat::main();

?>


	</div>
	<script src="js/jquery-3.4.1.min.js"></script>
	<script
		src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
		integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
		crossorigin="anonymous"></script>
	<script
		src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
		integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
		crossorigin="anonymous"></script>
	<script src="js/selectize.js"></script>
	<script src="js/escritor.js"></script>
	<script src="js/usuario.js"></script>
	<script src="js/chat.js"></script>
	

</body>
</html>