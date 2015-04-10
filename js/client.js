$(document).ready(function() {

	function updateForm() {
		if($('#add-popup input:checked').val() == "forf") {
			$(".lib").hide();
			$(".typ").show();
			$("p.qty").text("Quantité :");
			$("span.after-qty").remove();
		} else {
			$(".lib").show();
			$(".typ").hide();
			$("p.qty").text("Montant :");
			$("input.qty").after("<span class='after-qty'>EUR</span>");
		}
	}

	$("button#add").click(function() {
		$('#add-popup').addClass("show");
		updateForm();
	});

	$("input#cat_fraish, input#cat_fraisf").change(function() {
		updateForm();
	});

	$("select.typ").change(function() {
		if($("select.typ option:selected").val() == "KM") {
			$("input.qty").after("<span class='after-qty'>KM</span>");
		} else {
			$("span.after-qty").remove();
		}
	});

	$("button#cancel").click(function() {
		$('#add-popup').removeClass("show");
	});

	$('#add-popup form').submit(function(e) {
		e.preventDefault();
		console.log(e);
		$('#add-popup').addClass("send");
		var interval = setInterval(function() {
			$('#add-popup').removeClass("show").removeClass("send");
			clearInterval(interval);
		}, 200);
		$("#summary").addClass("loading");
		// $.ajax({
		//   method: "POST",
		//   url: "../ajax/login_ajax.php",
		//   data: { username: login, password: pw }
		// }).done(function( msg ) {
		// 	console.log(msg);
		//     if(msg) {
		//     	$("#summary").removeClass("loading");
		});
});