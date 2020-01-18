<?php 

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
    }
}
spl_autoload_register('autoload');


$selecionado = new Software();
$selecionado->setId(14);
$softwareDao = new SoftwareDAO();
$softwareDao->pesquisaPorId($selecionado);
$objetoDao = new ObjetoDAO($softwareDao->getConexao());
$objetoDao->pesquisaPorIdSoftware($selecionado);
$atributoDao = new AtributoDAO($softwareDao->getConexao());
foreach($selecionado->getObjetos() as $objeto){
    $atributoDao->pesquisaPorIdObjeto($objeto);
}



EscritorDeSoftware::main($selecionado);


?>