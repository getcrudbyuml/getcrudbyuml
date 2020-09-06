

$(document).ready(function(e) {
	$("#form_enviar_usuario").on('submit', function(e) {
		e.preventDefault();
        $('#modalAddUsuario').modal('hide');

		var dados = jQuery( this ).serialize();
		jQuery.ajax({
            type: "POST",
            url: "index.php?ajax=usuario",
            data: dados,
            success: function( data )
            {
            

            	if(data.split(":")[1] == 'sucesso'){
            		
            		$("#botao-modal-resposta").click(function(){
            			window.location.href='?pagina=software';
            		});
            		$('#local-do-email').text("Usuario enviado com sucesso! ");                	
            		$("#modalResposta").modal("show");
            		
            	}
            	else
            	{
            		
                	$('#local-do-email').text("Falha ao inserir Usuario, fale com o Fabiano. ");                	
            		$("#modalResposta").modal("show");
            	}

            }
        });
		
		
	});
	
	
});
   
