$(document).ready(function() {

	// Function : Requete ajax pour rechercher
	getCallback = function(keyword, data) {
		$.ajax({
		  method: "GET",
		  url: "../ajax/search-history.php",
		  data: data
		}).done(function( msg ) {
			console.log(msg);
			data = JSON.parse(msg);
			if(data) { // Retour en JSON, donc string ***** Pourquoi le test == "false" ? On n'entrerait jamais dans la condition en cas de retour
				var show = "";
				data.forEach(function(el, index){ // ******** remplacé par un foreach *****
					show+="<li>";
						show+="<a href=/details.php?fiche="+el.id+">";
							show+='<span class="date">'+el.date+'</span>';
							if(el.fiche_de) {show+='<span class="proprietaire"><strong>Fiche de : </strong>'+el.fiche_de+'</span>';}
							show+='<span class="etat"><strong>Etat : </strong>'+el.id_etat+'</span>';
						show+="</a>";
					show+="</li>";
				});
				$(".history-list > ul").empty();
				$(".history-list > ul").append(show);
			} else {
				$(".history-list > ul").empty();
				$("#search-history").val("").focus();
				$(".history-list > ul").append('<li><p>Aucun résultat trouvé pour "'+keyword+'".</li>');
			}
		});
	}

	// Lorsqu'on appuie sur Entrer pour envoyer
	$(".search").submit(function(e){
		e.preventDefault();
		var keyword = $("#search-history").val();
		var data = "search=" + keyword;
		if(keyword != "") {
			getCallback(keyword, data);
		}
	});

	// Lorsqu'on clique sur l'icone recherche
	$(".search-icon").click(function(e) { //****** Les instructions étaient incomplètes ici
		e.preventDefault();
		var keyword = $("#search-history").val();
		var data = "search=" + keyword;
		if(keyword != "") {
			getCallback(keyword, data);
		}
	});

});