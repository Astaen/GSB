$(document).ready(function() {

	function updateForm() {
		if($('#add-popup input#cat_fraisf').prop("checked")) {
			$(".lib").hide();
			$(".typ").show();
			$(".date_valeur").hide();
			$("p.qty").text("Quantité :");
			$("span.after-qty").remove();
		} else {
			$(".lib").show();
			$(".typ").hide();
			$(".date_valeur").show();
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

		data = {};

		if($('#add-popup input#cat_fraisf').prop("checked")) {
			data.cat_frais = "f";
		} else {
			data.cat_frais = "hf";
		}

		if(data.cat_frais == "f") {
			data.type_frais = $("select.typ").val();
			data.qty = $("input.qty").val();
		} else  {
			data.libelle = $("input.lib").val();
			data.montant = $("input.qty").val();
			data.date = $("input.date_valeur").val();
		}

		console.log(data);

		$.ajax({
		  method: "POST",
		  url: "../ajax/update_sheet.php",
		  data: {data: data}
		}).done(function( msg ) {
			console.log(msg);
		  });

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