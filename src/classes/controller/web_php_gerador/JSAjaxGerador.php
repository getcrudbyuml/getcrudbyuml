<?php

class JSAjaxGerador
{

    private $software;

    private $listaDeArquivos;

    private $diretorio;

    public static function main(Software $software, $diretorio)
    {
        $gerador = new JSAjaxGerador($software, $diretorio);
        $gerador->geraCodigo();
    }

    public function __construct(Software $software, $diretorio)
    {
        $this->software = $software;
        $this->diretorio = $diretorio;
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
        $this->criarArquivos();
        
        
    }
    private function criarArquivos(){
        
        $caminho = $this->diretorio.'/AppWebPHP/'.$this->software->getNomeSimples().'/src/classes/model/';
        if(!file_exists($caminho)) {
            mkdir($caminho, 0777, true);
        }
        
        foreach ($this->listaDeArquivos as $path => $codigo) {
            if (file_exists($path)) {
                unlink($path);
            }
            $file = fopen($path, "w+");
            fwrite($file, stripslashes($codigo));
            fclose($file);
        }
    }
    private function geraModel(Objeto $objeto)
    {
        $codigo = '

$(document).ready(function(e) {
	$("#form_enviar_'.$objeto->getNomeSnakeCase().'").on(\'submit\', function(e) {
		e.preventDefault();

		var dados = jQuery( this ).serialize();
		jQuery.ajax({
            type: "POST",
            url: "index.php?ajax=' .$objeto->getNomeSnakeCase() . '",
            data: dados,
            success: function( data )
            {
            

            	if(data.split(":")[1] == \'sucesso\'){
            		
            		$("#botao-modal-resposta").click(function(){
            			window.location.href=\'' .$objeto->getNomeSnakeCase() . '\';
            		});
            		$("#textoModalResposta").text("Contrato enviado com sucesso! ");                	
            		$("#modalResposta").modal("show");
            		
            	}
            	else
            	{
            		console.log(data);
                	$("#textoModalResposta").text("Falha ao inserir contrato, fale com o Fabiano. ");                	
            		$("#modalResposta").modal("show");
            	}

            }
        });
		
		
	});
	
	
});
   
';
        
        
        $caminho = $this->diretorio.'/AppWebPHP/'.$this->software->getNomeSimples().'/src/js/'.$objeto->getNomeSnakeCase().'.js';
        $this->listaDeArquivos[$caminho] = $codigo;
        
    }

}

?>