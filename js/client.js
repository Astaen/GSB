$(document).ready(function() {

	/* Met à jour l'apparence du formulaire d'ajout pour frais forfait ou hors-forfait */
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

	/* Action du bouton d'ajout */
	$("button#add").click(function() {
		$('#add-popup form p.error').remove();
		$('#add-popup').addClass("show");
		updateForm();
	});

	/* Sur la sélection de la catégorie de frais */
	$("input#cat_fraish, input#cat_fraisf").change(function() {
		updateForm();
	});

	/* Gadget : lors du changement de type de frais, on ajoute "KM" derrrière le champ si "Voyage" est selectionné */
	$("select.typ").change(function() {
		if($("select.typ option:selected").val() == "KM") {
			$("input.qty").after("<span class='after-qty'>KM</span>");
		} else {
			$("span.after-qty").remove();
		}
	});

	/* Fermeture du formulaire d'ajout */
	$("button#cancel").click(function() {
		$('#add-popup').removeClass("show");
	});

	/* Evenement 'submit' du formulaire d'ajout */
	$('#add-popup form').submit(function(e) {
		e.preventDefault();
		var error = false;

		/* On initialiste data en tant qu'objet afin de pouvoir le remplir. */
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

		// console.log(data);

		$.ajax({
		  method: "POST",
		  url: "../ajax/update_sheet.php",
		  data: {data: data}
		}).done(function( msg ) {
			var result = JSON.parse(msg);

			console.log(result);

			/* Si c'est une mise à jour des frais forfaitaires ... */
			if(data.cat_frais == "f") {
				error = false;
				var total = 0;
				/* ... on les parcourt un par un pour mettre à jour les montants. */
				result['frais'].forEach(function(element, index){
					switch(element['id_typefrais']) {
						case "ETP":
							$('#etapes').text(element['quantite']);
							total += result['couts']["ETP"]*element['quantite'];
							break;
						case "NUI":
							$('#nuite').text(element['quantite']);
							total += result['couts']["NUI"]*element['quantite'];
							break;
						case "REP":
							$('#repas').text(element['quantite']);
							total += result['couts']["REP"]*element['quantite'];
							break;
						case "KM":
							$('#voyage').text(element['quantite'] + " km");
							total += result['couts']["KM"]*element['quantite'];
							break;					
						default:
							console.log(element);
							break;
					}
				});	
				$('.f_amt').html("<strong>Total forfaitaire: </strong>" + parseFloat(total).toFixed(2) + " €");			
			} else {
				if(!isNaN(result)) {
					$('.hf_amt').html("<strong>Total hors-forfait: </strong>" + parseFloat(result).toFixed(2) + " €");
					error = false;
				} else {
					error = true;
					$('#add-popup form').prepend('<p class="error">Vous ne pouvez pas rentrer de frais antérieurs à un an, ou ultérieurs à aujourdh\'hui.</p>');
				}
			}

			if(!error) {
				/* Animation de l'envoi du formulaire d'ajout */
				$('#add-popup').addClass("send");
				var interval = setInterval(function() {
					$('#add-popup').removeClass("show").removeClass("send");
					clearInterval(interval);
				}, 200);			
			}			
		
		  }); // -- end of Ajax call

	});

});