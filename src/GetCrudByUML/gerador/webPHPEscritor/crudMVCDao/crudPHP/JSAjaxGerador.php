<?php

namespace GetCrudByUML\gerador\webPHPEscritor\crudMVCDao\crudPHP;

use GetCrudByUML\model\Objeto;
use GetCrudByUML\model\Software;

class JSAjaxGerador
{

    private $software;

    private $listaDeArquivos;

    private $diretorio;

    public static function main(Software $software)
    {
        $gerador = new JSAjaxGerador($software);
        return $gerador->geraCodigo();
    }

    public function __construct(Software $software)
    {
        $this->software = $software;
        
    }

    /**
     * Selecione uma linguagem
     *
     * @param int $linguagem
     */
    public function geraCodigo()
    {
        foreach($this->software->getObjetos() as $objeto){
            $this->geraModel($objeto);
        }
        return $this->listaDeArquivos;
        
        
    }
   
    private function geraModel(Objeto $objeto)
    {
        $codigo = '

$(document).ready(function(e) {
	$("#insert_form_'.$objeto->getNomeSnakeCase().'").on(\'submit\', function(e) {
		e.preventDefault();
        $(\'#modalAdd'.$objeto->getNome().'\').modal(\'hide\');

		var dados = jQuery( this ).serialize();
		jQuery.ajax({
            type: "POST",
            url: "index.php?ajax=' .$objeto->getNomeSnakeCase() . '",
            data: dados,
            success: function( data )
            {
            

            	if(data.split(":")[1] == \'sucesso\'){
            		
            		$("#botao-modal-resposta").click(function(){
            			window.location.href=\'?page=' .$objeto->getNomeSnakeCase() . '\';
            		});
            		$("#textoModalResposta").text("' .$objeto->getNomeTextual() . ' enviado com sucesso! ");                	
            		$("#modalResposta").modal("show");
            		
            	}
            	else
            	{
            		
                	$("#textoModalResposta").text("Falha ao inserir ' .$objeto->getNomeTextual() . ', fale com o suporte. ");                	
            		$("#modalResposta").modal("show");
            	}

            }
        });
		
		
	});
	
	
});
   
';
        
        
        $caminho = $objeto->getNomeSnakeCase().'.js';
        $this->listaDeArquivos[$caminho] = $codigo;
        
    }

}

?>