$(document).ready(function() {
	$('#login_form').css("top",Math.round(($(document).height()-350)/2)+"px");

	$('#login_form input').focus(function(e) {
		$("label.focused").removeClass('focused');
		$('label[for='+e.currentTarget.id+']').addClass('focused');
	});

	$(window).resize(function() {
		$('#login_form').css("top",Math.round(($(document).height()-350)/2)+"px");
	})

	showIndex = function() {
		$.ajax({
		  method: "POST",
		  url: "index.php",
		  data: {login: true}
		}).done(function( msg ) {
			document.write(msg);
		  });		
	}

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
		    		showIndex();
		    		clearInterval(interval);
		    	},100);		    	
		    } else {
		    	$("#login_form").addClass("wrong");
		    	$('#login_form form').fadeIn("fast");
		    	$('#password').val("");
		    	console.log("Erreur");
		    }
		  });		
	})


});
