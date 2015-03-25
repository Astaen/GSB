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
		  method: "GET",
		  url: "index.php"
		}).done(function( msg ) {
			document.write(msg);
		  });		
	}

	$('#login_form').submit(function(e) {
		e.preventDefault();
		$('#login_form form').fadeOut("fast");
		var login = $('#username').val();
		var pw = $('#password').val();
		$(e.currentTarget).addClass("loading");
		$.ajax({
		  method: "POST",
		  url: "../ajax/login_ajax.php",
		  data: { username: login, password: pw }
		}).done(function( msg ) {
		    if(msg) {
		    	$('#login_form').addClass("logged");
		    	interval = setInterval(function() {
		    		showIndex();
		    		clearInterval(interval);
		    	},1500);		    	
		    } else {

		    }
		  });		
	})


});
