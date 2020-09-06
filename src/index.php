<?php
define("DB_INI", "../escritordesoftware_bd.ini");

function autoload($classe)
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
    } else if (file_exists('classes/controller/web_php_gerador/' . $classe . '.php')) {
        include_once 'classes/controller/web_php_gerador/' . $classe . '.php';
    }else if (file_exists('classes/controller/sql_gerador/' . $classe . '.php')) {
        include_once 'classes/controller/sql_gerador/' . $classe . '.php';
    }else if(file_exists('classes/controller/desktop_java_gerador/' . $classe . '.php')){
        include_once 'classes/controller/desktop_java_gerador/' . $classe . '.php';
    }
    
}
spl_autoload_register('autoload');

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

$auditoria->setPagina($strGet);
$auditoriaDao = new AuditoriaDAO();

$auditoriaDao->inserir($auditoria);



if (isset($_GET["sair"])) {
    $sessao->mataSessao();
    echo '<META HTTP-EQUIV="REFRESH" CONTENT="0; URL=index.php">';
    
}
if (isset($_GET['cadastrar_usuario'])) {

    
    $to = $_GET['enviar_email'];
    $subject = "GetCrudByID - Seu usuário foi cadastrado com sucesso!";
    $message = "<p>Bem vindo ao getcrudbyuml! Seu usuário foi cadastrado com sucesso! Aproveite!</p>";
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'From: getCrudById <contato@getcrudbyuml.com>';

    if (mail($to, $subject, $message, $headers)) {
        echo "<p>E-mail Enviado. Verifique sua caixa de e-mail.</p>";
        
    } else {
        echo "<p>E-mail não foi enviado.</p>";
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
<title>getCrudByUml</title>
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

</body>
</html>