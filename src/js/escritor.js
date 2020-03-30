$("#menu-toggle").click(function(e) {
  e.preventDefault();
  $("#wrapper").toggleClass("toggled");
});

$(".campmo-selecionado").focus();

$('#select-tipo-atributo').selectize({
    create: false,
    sortField: 'text'
});

$("#form-email" ).submit(function( event ) {
  event.preventDefault();
  var valor = $('#email').val();
  console.log(valor);
  $.ajax({
      url: 'index.php?enviar_email='+valor,
      success: function (response) {
          $('#local-do-email').html(response);

      }
  });
  
});