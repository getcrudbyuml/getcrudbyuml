

$(document).ready(function(e) {
	$("#form_enviar_usuario").on('submit', function(e) {
		e.preventDefault();
		console.log("Submeteu");


		var dados = jQuery( this ).serialize();
		jQuery.ajax({
            type: "POST",
            url: "index.php?ajax=usuario",
            data: dados,
            success: function( data )
            {
            

				console.log('Teste: '+data);
				
            	if(data.split(":")[1] == 'sucesso'){
            		

            		$('#local-do-email').text("Usuario enviado com sucesso! ");
					window.location.href='.';                	
            		            		
            		
            	}else if(data.split(":")[1] == 'falha_senhas'){
					$('#local-do-email').text("As senhas não estão batendo. ");                	
            		
				}else if(data.split(":")[1] == 'falha_email'){
					$('#local-do-email').text("O e-mail já está cadastrado.");                	
            		
				}else if(data.split(":")[1] == 'falha_login'){
					$('#local-do-email').text("Este login já está sendo utilizado. ");                	
            		
				}
            	else
            	{
            		
                	$('#local-do-email').text("Falha ao inserir Usuario, fale com o Fabiano. ");                	
            		
            	}

            }
        });
		
		
	});
	
	
});
   
