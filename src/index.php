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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
        	<h1>Softwares Criados</h1>
            <ul>        	
        	<?php 
        	
        	$conexao = Conexao::retornaConexaoComBanco();
        	$softwaredao = new SoftwareDAO();
        	$softwaredao->setConexao($conexao);
        	$softwares = $softwaredao->retornaSoftwaresComId();
        	if($softwares)
			{
        		foreach ($softwares as $software)
				{
        			echo '<li><a href="software.php?idsoftware='.$software->getId().'">'.$software->getNome().'</a></li>';
        		}
        	}
        	else
			{
				echo "Nenhum software adicionado ainda.";
		
			}
        	
        	
        	?>

	       </ul>

        </div>
       	<div id="direita">
        	<h1>Criar Novo Software</h1>
            <p>(Não use Acento ou caracteres especiais, somente letras)</p>
            
            <form action="inserirsoftware.php" method="post">
            	<fieldset>
                	<legend>Criar Novo Software</legend>
	                <label for="nome_do_software"> Nome do Software</label>
    	            <input type="text" id="nome_do_software" name="nome_do_software" />
                	<label for="linguagem">Linguagem</label>
                    <select id="linguagem" name="linguagem">
                    	<option value="php">PHP </option>
                    </select>
                    
                    <input type="hidden" value="mysql" id="sgdb" name="sgdb" />
                    
<!--                     <label for="host">Host do Banco</label> -->
                      <input type="hidden" value="localhost" id="host" name="host" />
<!--                     <label for="nome_do_banco">Nome Do Banco</label> -->
                      <input type="hidden" value="bd_teste" id="nome_do_banco" name="nome_do_banco" />
<!--                      <label for="usuario">Usuario do banco</label> -->
                     <input type="hidden" value="mvlineco_root" id="usuario" name="usuario" />
<!--                      <label for="senha">senha do banco</label> -->
                     <input type="hidden" value="cocacola@12" id="senha" name="senha" />
                    <input type="submit" value="Criar Software"  />
                    
                </fieldset>
            
            </form>

	    </div>

        
    </div>

</body>
</html>