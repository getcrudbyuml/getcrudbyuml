$("#menu-toggle").click(function(e) {
  e.preventDefault();
  $("#wrapper").toggleClass("toggled");
});

$(".campmo-selecionado").focus();

$('#select-tipo-atributo').selectize({
    create: false,
    sortField: 'text'
});

$('#select-tipo-codigo').selectize({
    create: false,
    sortField: 'text'
});

