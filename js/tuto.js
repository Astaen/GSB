$(document).ready(function(){
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
	var cookie_expire = 60 *60 * 24 * 365;
	var cookie_tuto = docCookies.getItem('tuto');
	var typeuser = $('.ariane').find("strong")[0].innerText; // Récupère le type de l'interface
		typeuser = typeuser.substr(0, typeuser.length-2);
	var info = ""; var classe = "";
	// (string) true | null : Affiche le tuto
	// (string) false : N'affiche plus le tuto jusqu'à l'expiration du cookie
	// Vérifie si le cookie éxiste OU qu'il est à "VRAI"
	if(cookie_tuto == null || cookie_tuto == "true") {
		docCookies.setItem('tuto', "true", cookie_expire);
		$('body').css({'overflow':'hidden'});
		// L'écran devient noir & un container & la cookie-bar
		$('body').append('<div class="tuto"><div class="tuto-container"></div><div class="tuto-cookiebar"><p>Cliquez si vous ne voulez plus afficher le tutoriel</p><button id="btn-cookie" class="btn-tuto btn-cookie">Ne plus afficher le tutoriel</button></div></div>');

		// Affiche le message de bienvenu
		$('.tuto').append('<div class="firsttime"><h1 class="tuto-title">Bienvenue !</h1><p class="tuto-info">C\'est la première fois que vous vous connectez ! Un petit aperçu des fonctionnalités du site vont vous être présenté.</p><button id="btn-first" class="btn-tuto">Suivant</button></div>');

		// Affiche l'infobulle sur l'ariane
		$('.tuto').on('click', '#btn-first', function(){
			$('.firsttime').fadeOut();
			if(typeuser == "Interface Visiteur") { classe = "tuto-flash-ariane"; }
			else { classe = "tuto-flash-ariane tuto-flash-ariane-com"; }
			$('.tuto').append('<div class="'+classe+'"></div><div class="tuto-wrap-ariane"><div class="tuto-ariane-infobulle">Indique sur quel type de compte utilisateur vous êtes connecté ainsi que la page sur laquelle vous êtes actuellement.</div><button id="btn-ariane" class="btn-tuto">Suivant</button></div>');
		});

		// Affiche l'infobulle sur le menu
		$('.tuto').on('click', '#btn-ariane', function(){
			$('.tuto-flash-ariane').remove();
			$('.tuto-wrap-ariane').fadeOut();
			if(typeuser == "Interface Visiteur") {
				info = 'Vous avez plusieurs fonctionnalités à disposition. Dans le "<i>Tableau de bord</i>" vous disposez d\'un bref résumé sur vos frais. Et vous pouvez consulter vos frais présents et antérieur dans "<i>Gestion des frais</i>".';
			}
			else {
				info = 'Vous avez plusieurs fonctionnalités à disposition. Dans le "<i>Tableau de bord</i>" vous pouvez voir les fiches de frais des visiteurs du mois en cours. Et vous pouvez consulter toutes les fiches de frais présentes et antérieur dans "<i>Gestion des frais</i>".';
			}
			$('.tuto-container').append('<div class="tuto-flash-menu"></div><div class="tuto-wrap-menu"><div class="tuto-info">'+info+'</div><button id="btn-menu" class="btn-tuto">Suivant</button></div>');
		});

		// Affiche l'infobulle sur le bouton 'Ajouter'
		if(typeuser == "Interface Visiteur") {
			$('.tuto').on('click', '#btn-menu', function(){
				$('.tuto-flash-menu').remove();
				$('.tuto-wrap-menu').fadeOut();
				$('.tuto-container').append('<div class="tuto-flash-add"></div><div class="tuto-wrap-add"><div class="tuto-info">Permet d\'ajouter des frais sur votre fiche du mois en cours.</div><button id="btn-add" class="btn-tuto">Suivant</button></div>');
			});
		}

		// Affiche l'infobulle du corps
		if(typeuser == "Interface Comptable") { var cmpt = ", #btn-menu" } // Fix en carton mais ca évite le code à répétition
		else { var cmpt = ""}
		$('.tuto').on('click', '#btn-add'+cmpt+'', function(){
			if($('.tuto-flash-menu')) {$('.tuto-flash-menu').remove(); $('.tuto-wrap-menu').fadeOut();}
			$('.tuto-flash-add').remove();
			$('.tuto-wrap-add').fadeOut();
			if(typeuser == "Interface Visiteur") {
				info = "Résumé des frais que vous avez entrés dans le mois.";
			}
			else {
				info = 'Toute les fiches du mois courant dont l\'état est "En cours"';
			}
			$('.tuto-container').append('<div class="tuto-flash-monthsummary"></div><div class="tuto-wrap-monthsummary"><div class="tuto-info">Résumé des frais que vous avez entrés dans le mois.</div><button id="btn-monthsummary" class="btn-tuto">Fin</button></div>');
		});
		/*
		// Fin du tutoriel
		$('.tuto').on('click', '#btn-monthsummary', function(){
			$('.tuto-flash-monthsummary').remove();
			$('.tuto-wrap-monthsummary').fadeOut();
			docCookies.removeItem('tuto');
			docCookies.setItem('tuto', false, cookie_expire);
			$('.tuto').fadeOut();
			$('body').css({'overflow-y':'initial'});
		});*/

		// Fin du tutoriel 
		// Arrête le tuto et ne le réaffiche plus
		$('.tuto').on('click', '#btn-cookie, #btn-monthsummary', function(e){
			e.preventDefault();
			if($('.tuto-flash-monthsummary')) {$('.tuto-flash-monthsummary').remove(); $('.tuto-wrap-monthsummary').fadeOut();}
			$('.tuto').fadeOut();
			$('body').css({'overflow-y':'scroll'});
			if(cookie_tuto) docCookies.removeItem('tuto');
			docCookies.setItem('tuto', false, cookie_expire);
			$('.tuto').fadeOut();
			$('body').css({'overflow-y':'initial'});
			removeTuto = setInterval(function() {
	    		$('.tuto').remove();
	    		clearInterval(removeTuto);
	    	},1000);	
		});
	}
});