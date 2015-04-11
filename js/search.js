$(document).ready(function() {

	// Function : Requete ajax pour rechercher
	getCallback = function(keyword, data) {
		$.ajax({
			method: "GET",
			url: "/ajax/search-history.php",
			data: data,
			dataType: "json",
			success: function (response) {
				var data = $.parseJSON(response);
				if(data == "false") { // Retour en JSON, donc string
					var show = "";
					console.log(data);
					for (var i = 0; i < data.length; i++) {
						show+="<li>";
							show+="<a href=/details.php?fiche="+data[i].id+">";
								show+='<span class="date">'+data[i].date+'</span>';
								show+='<span class="etat"><strong>Etat : </strong>'+date[i].id_etat+'</span>';
							show+="</a>";
						show+="</li>";
					};
					$(".history-list > ul").empty();
					$(".history-list > ul").append(show);
				}
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
	$(".search-icon").click(function() {
		getCallback();
	});

});