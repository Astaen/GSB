$(document).ready(function() {

	/*On calcule la position centrale du formulaire de connexion */
	$('#login_form').css("top",Math.round(($(document).height()-350)/2)+"px");

	/* On colore le label de chaque input si elle est selectionnée */
	$('#login_form input').focus(function(e) {
		$("label.focused").removeClass('focused');
		$('label[for='+e.currentTarget.id+']').addClass('focused');
	});

	/* On conserve le formulaire au centre, même si la fenêtre est redimensionnée */
	$(window).resize(function() {
		$('#login_form').css("top",Math.round(($(document).height()-350)/2)+"px");
	})

	/* Sur l'envoi du formulaire de connexion */
	$('#login_form').submit(function(e) {
		e.preventDefault();
		$("#login_form").removeClass("wrong");
		var login = $('#username').val();
		var pw = $('#password').val();
		$.ajax({
		  method: "POST",
		  url: "../ajax/login_ajax.php",
		  data: { username: login, password: pw }
		}).done(function( msg ) {
			console.log(msg);
		    if(msg) {
		    	$(e.currentTarget).removeClass("wrong");
		    	$('#login_form').addClass("logged");
		    	interval = setInterval(function() {
		    		window.location.replace("/");
		    		clearInterval(interval);
		    	},150);		    	
		    } else {
		    	$("#login_form").addClass("wrong");
		    	$('#login_form form').fadeIn("fast");
		    	$('#password').val("");
		    	console.log("Erreur");
		    }
		  });		
	})


});
