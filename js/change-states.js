$(document).ready(function(){

	$('#form-change-etat').submit(function(e) {
		e.preventDefault();
		var etat = $("#action-etat").val();
		var id = window.location.href;
			id = id.substr( (id.indexOf("=")+1) , (id.length) ); // Récupère l'ID de la fiche via l'url
			console.log(id);
		$.ajax({
			method: "GET",
			url: "/ajax/change-states.php",
			data: {etat: etat, id_fiche: id }
		}).done(function(msg) {
			msg = JSON.parse(msg);
			console.log(typeof(msg)+" "+msg);
			if(msg) {
				$('.alert-success').fadeIn().delay(3000).fadeOut();
			}
			else {
				$('.alert-error').fadeIn().delay(3000).fadeOut();
			}
		});
	});

});