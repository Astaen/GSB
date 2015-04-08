$(document).ready(function() {
	$("button#add").click(function() {
		$('#add-popup').addClass("show");
	});

	$("button#cancel").click(function() {
		$('#add-popup').removeClass("show");
	});
});