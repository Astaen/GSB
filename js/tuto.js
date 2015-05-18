$(document).ready(function(){
	// Doc cookie : https://developer.mozilla.org/en-US/docs/Web/API/Document/cookie
	/* OBJET COOKIE */
	var docCookies = {
	  getItem: function (sKey) {
	    if (!sKey) { return null; }
	    return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;
	  },
	  setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure) {
	    if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) { return false; }
	    var sExpires = "";
	    if (vEnd) {
	      switch (vEnd.constructor) {
	        case Number:
	          sExpires = vEnd === Infinity ? "; expires=Fri, 31 Dec 9999 23:59:59 GMT" : "; max-age=" + vEnd;
	          break;
	        case String:
	          sExpires = "; expires=" + vEnd;
	          break;
	        case Date:
	          sExpires = "; expires=" + vEnd.toUTCString();
	          break;
	      }
	    }
	    document.cookie = encodeURIComponent(sKey) + "=" + encodeURIComponent(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
	    return true;
	  },
	  removeItem: function (sKey, sPath, sDomain) {
	    if (!this.hasItem(sKey)) { return false; }
	    document.cookie = encodeURIComponent(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "");
	    return true;
	  },
	  hasItem: function (sKey) {
	    if (!sKey) { return false; }
	    return (new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
	  },
	  keys: function () {
	    var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
	    for (var nLen = aKeys.length, nIdx = 0; nIdx < nLen; nIdx++) { aKeys[nIdx] = decodeURIComponent(aKeys[nIdx]); }
	    return aKeys;
	  }
	};



	/***
	 * TUTORIEL
	 */
	var cookie_expire = 60 *60 * 24 * 365, cookie_tuto = docCookies.getItem('tuto');
	var typeuser = $('.ariane').find("strong")[0].innerText; // Récupère le type de l'interface
		typeuser = typeuser.substr(0, typeuser.length-2);
	var info = ""; var classe = "";
	var $body = $('body');

	// (string) null : Affiche le tuto
	// (string) false : N'affiche plus le tuto jusqu'à l'expiration du cookie
	if(cookie_tuto == null) {
		$body.css({'overflow':'hidden'});
		
		// L'écran devient noir & un container & la cookie-bar
		$body.append('<div class="tuto"><div class="tuto-container"></div><div class="tuto-cookiebar"><p>Cliquez si vous ne voulez plus afficher le tutoriel</p><button id="btn-cookie" class="btn-tuto btn-cookie">Ne plus afficher le tutoriel</button></div></div>');

		var $tuto = $('.tuto');
		var $container = $('.tuto-container');

		// Affiche le message de bienvenu
		$tuto.append('<div class="firsttime"><h1 class="tuto-title">Bienvenue !</h1><p class="tuto-info">C\'est la première fois que vous vous connectez ! Un petit aperçu des fonctionnalités du site va vous être présenté.</p><button id="btn-first" class="btn-tuto">Suivant</button></div>');

		// Affiche l'infobulle sur l'ariane
		$tuto.on('click', '#btn-first', function(){
			$('.firsttime').fadeOut();
			classe = (typeuser == "Interface Visiteur") ? "tuto-flash-ariane" : "tuto-flash-ariane tuto-flash-ariane-com";
			$tuto.append('<div class="'+classe+'"></div><div class="tuto-wrap-ariane arrow-right"><div class="tuto-ariane-infobulle">Indique sur quel type de compte utilisateur vous êtes connecté ainsi que la page sur laquelle vous êtes actuellement.</div><button id="btn-ariane" class="btn-tuto">Suivant</button></div>');
		});

		// Affiche l'infobulle sur le menu
		$tuto.on('click', '#btn-ariane', function(){
			$('.tuto-flash-ariane').remove();
			$('.tuto-wrap-ariane').fadeOut();
			info = 'Vous avez plusieurs fonctionnalités à disposition:<br/>';
			if(typeuser == "Interface Visiteur") {
				info += '- Dans le "<i>Tableau de bord</i>" vous disposez d\'un bref résumé sur vos frais.<br/>- Et vous pouvez consulter vos frais présents et antérieur dans "<i>Gestion des frais</i>".';
			}
			else {
				info += '- Dans le "<i>Tableau de bord</i>" vous pouvez voir les fiches de frais des visiteurs du mois courant.<br/>- Et vous pouvez consulter toutes les fiches de frais des visiteurs présentes et antérieur dans "<i>Gestion des frais</i>".';
			}
			$container.append('<div class="tuto-flash-menu"></div><div class="tuto-wrap-menu"><div class="tuto-info">'+info+'</div><button id="btn-menu" class="btn-tuto">Suivant</button></div>');
		});

		// Affiche l'infobulle du corps
		$tuto.on('click', '#btn-menu', function(){
			$('.tuto-flash-menu').remove();
			$('.tuto-wrap-menu').fadeOut();
			info = (typeuser == "Interface Visiteur") ? 'Résumé des frais que vous avez entrés dans le mois.' : 'Toute les fiches du mois courant dont l\'état est "En cours".';
			$container.append('<div class="tuto-flash-monthsummary"></div><div class="tuto-wrap-monthsummary arrow-right"><div class="tuto-info">'+info+'</div><button id="btn-monthsummary" class="btn-tuto">Suivant</button></div>');
		});

		if(typeuser == "Interface Visiteur") {
			// Affiche l'infobulle sur le bouton 'Ajouter'
			$tuto.on('click', '#btn-monthsummary', function(){
				$('.tuto-flash-monthsummary').remove();
				$('.tuto-wrap-monthsummary').fadeOut();
				$container.append('<div class="tuto-flash-add"></div><div class="tuto-wrap-add arrow-right"><div class="tuto-info">Permet d\'ajouter des frais sur votre fiche du mois en cours.</div><button id="btn-add" class="btn-tuto">Suivant</button></div>');
			});

			/*
			 * Infobulles sur les champs pour l'ajout de frais (pop-up)
			 */
			// Focus sur le catégorie
			$tuto.on('click', '#btn-add', function(){
				$('.tuto-flash-add').remove();
				$('.tuto-wrap-add').fadeOut();
				document.getElementById('add').click(); // Click automatiquement sur le bouton 'Ajouter'
				$container.append('<div class="tuto-flash-func1"></div><div class="tuto-wrap-func1 arrow-right"><div class="tuto-info">Lorsque vous entrez des frais, vous pouvez choisir une catégorie de frais.<br/>- Les frais forfaitaires sont les dépenses éffectuées pour l\'entreprise.<br/>- Les frais hors-forfaits sont les dépenses personnelles éffectuées avec l\'argent de l\'entreprise.</div><button id="btn-func1" class="btn-tuto">Suivant</button></div>');
			});
			// Focus sur le type de frais
			$tuto.on('click', '#btn-func1', function(){
				$('.tuto-flash-func1').remove();
				$('.tuto-wrap-func1').fadeOut();
				var scroll = $('form').offset().top;
				$body.animate({scrollTop: scroll}, 500); // Scroll vers le bas
				$container.append('<div class="tuto-flash-func2"></div><div class="tuto-wrap-func2 arrow-right"><div class="tuto-info">Vous devez choisir le type de frais correspondant aux différents frais dépensés lors de vos déplacements professionnels: <br>- Etape: <i>Nombre de forfait comprenant une nuit et un repas.</i><br>- Voyage: <i>Nombre de kilomètre parcouru.</i><br>- Nuitée: <i>Nombre de nuit passé dans l\'/les hôtels au total.</i><br/>- Repas: <i>Nombre de repas effectué.</i></div><button id="btn-func2" class="btn-tuto">Suivant</button></div>');
			});
			// Focus sur la quantité
			$tuto.on('click', '#btn-func2', function(){
				$('.tuto-flash-func2').remove();
				$('.tuto-wrap-func2').fadeOut();
				$container.append('<div class="tuto-flash-func3"></div><div class="tuto-wrap-func3 arrow-right"><div class="tuto-info">Vous devez indiquer la quantité d\'étape, de voyage, de nuitée ou de repas que vous avez effectués.</div><button id="btn-func3" class="btn-tuto">Suivant</button></div>');
			});
			// Focus sur le libellé
			$tuto.on('click', '#btn-func3', function(){
				$('.tuto-flash-func3').remove();
				$('.tuto-wrap-func3').fadeOut();
				document.getElementById('cat_fraish').click(); // Click sur le bouton radio 'Frais hors forfait'
				$container.append('<div class="tuto-flash-func4"></div><div class="tuto-wrap-func4 arrow-right"><div class="tuto-info">Donnez la raison des frais (<i>ex: Car Wash, fournitures de bureau, ...</i>).</div><button id="btn-func4" class="btn-tuto">Suivant</button></div>');
			});
			// Focus sur la date valeur
			$tuto.on('click', '#btn-func4', function(){
				$('.tuto-flash-func4').remove();
				$('.tuto-wrap-func4').fadeOut();
				$container.append('<div class="tuto-flash-func5"></div><div class="tuto-wrap-func5 arrow-right"><div class="tuto-info">Vous devrez préciser la date à laquelle vous avez effectué votre dépense.</div><button id="btn-func5" class="btn-tuto">Suivant</button></div>');
			});
			// Focus sur montant
			$tuto.on('click', '#btn-func5', function(){
				$('.tuto-flash-func5').remove();
				$('.tuto-wrap-func5').fadeOut();
				$container.append('<div class="tuto-flash-func6"></div><div class="tuto-wrap-func6 arrow-right"><div class="tuto-info">Et enfin vous devrez préciser le montant de votre dépense.</div><button id="btn-func6" class="btn-tuto">Suivant</button></div>');
			});

			$tuto.on('click', '#btn-func6', function(){
				docCookies.setItem('tuto', false, cookie_expire);
				document.querySelector(".nav-menu li:nth-child(2) a").click(); // Click sur le bouton 'Gestion des frais'
			});
		}
		else { // Si c'est un compta
			// Passer à la présentation de la parti Gestion des frais
			$tuto.on('click', '#btn-monthsummary', function(){
				docCookies.setItem('tuto', false, cookie_expire);
				document.querySelector(".nav-menu li:nth-child(2) a").click(); // Click sur le bouton 'Gestion des frais'
			});
		}

		// Arrête le tuto et ne le réaffiche plus
		$tuto.on('click', '#btn-cookie', function(e){
			e.preventDefault();
			$tuto.fadeOut();
			var scroll = $body.offset().top; // Position du body (soit 0px en haut)
			$body.animate({scrollTop: scroll}, 500); // Scroll vers le haut
			$body.css({'overflow-y':'scroll'});
			if(vis != "") { document.getElementById('cancel').click(); }; // Click automatiquement sur le bouton 'Annulé'
			docCookies.setItem('tuto', false, cookie_expire);
			docCookies.setItem('tutoFiches', false, cookie_expire);
			docCookies.setItem('tutoDetails', false, cookie_expire);
			removeTuto = setInterval(function() {
	    		$tuto.remove();
	    		clearInterval(removeTuto);
	    	},1000);	
		});
	}
});