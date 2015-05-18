$(document).ready(function() {
	/**
 	 * CE SCRIPT (TUTO) SERA SELEUEMENT ACTIF SUR LA PAGE "FICHES.PHP"
 	 */
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
	var cookie_expire = 60 *60 * 24 * 365, cookie_tuto = docCookies.getItem('tutoFiches');
	var typeuser = $('.ariane').find("strong")[0].innerText; // Récupère le type de l'interface
		typeuser = typeuser.substr(0, typeuser.length-2);
	var $body = $('body');
	var url = window.location.pathname; // On récupère l'url afin de le récupèrer la page courant
		url = url.substr(1,url.length); // On enlève le "/"

	// Si le cookie n'existe pas (ou qu'il n'a pas été lancer) et qu'on est sur la page "fiches.php"
	if(cookie_tuto == null && url == "fiches.php") {
		$body.css({'overflow':'hidden'}); // Cache la bar de défilement
		var exist = document.querySelector(".history-list ul");
		var isEndCom = (exist)?"Suivant":"Fin"; // Pour le compta, si il n'y a pas de fiche de frais pour le tuto suivant

		// L'écran devient noir & un container & la cookie-bar
		$body.append('<div class="tuto"><div class="tuto-container"></div><div class="tuto-cookiebar"><p>Cliquez si vous ne voulez plus afficher le tutoriel</p><button id="btn-cookie" class="btn-tuto btn-cookie">Ne plus afficher le tutoriel</button></div></div>');

		var $tuto = $('.tuto');
		var $container = $('.tuto-container');
		var isEnd = (typeuser == "Interface Comptable")?"Suivant":"Fin";
		var isVis = (typeuser == "Interface Comptable")?"nom, prénom, date (2 mots clés max.)<br/><i>Exemples de rechercher : Michel, 2015, Janvier, Janvier 2015, Robert Lejean, 05.</i>":"date (2 mots clés max.)<br/><i>Exemples de rechercher : 2015, Janvier, Janvier 2015, 05.</i>";

		// Affiche l'infobulle du corps
		$container.append('<div class="tuto-flash-history-search"></div><div class="tuto-wrap-history-search arrow-right"><div class="tuto-info">Vous pouvez rechercher des fiches de frais par : '+isVis+'</div><button id="btn-history-search" class="btn-tuto">'+isEnd+'</button></div>');

		if(typeuser == "Interface Visiteur") {
			$tuto.on('click', '#btn-history-search', function(){
				docCookies.setItem('tutoFiches', false, cookie_expire);
				docCookies.setItem('tutoDetails', false, cookie_expire);
				document.querySelector(".nav-menu li:first-child a").click();
			});
		}

		if(typeuser == "Interface Comptable") {
			$tuto.on('click', '#btn-history-search', function(){
				$('.tuto-flash-history-search').remove();
				$('.tuto-wrap-history-search').fadeOut();
				$container.append('<div class="tuto-flash-history-etat"></div><div class="tuto-wrap-history-etat arrow-right"><div class="tuto-info">Vous pouvez aussi trier des fiches par leur état : cloturé, validé et remboursé.</div><button id="btn-history-etat" class="btn-tuto">'+isEndCom+'</button></div>');
			});

			$tuto.on('click', '#btn-history-etat', function(){
				docCookies.setItem('tutoFiches', false, cookie_expire);
				// Si il y a des fiches on clic sur la première fiches de frais
				if(exist) {
					document.querySelector(".history-list ul li:first-child a").click();
				}
				else { // Sinon on redirige vers la pge d'acceuil
					document.querySelector(".nav-menu li:first-child a").click();
				}
			});
		}

		// Arrête le tuto et ne le réaffiche plus
		var vis = (typeuser == "Interface Comptable") ? ", #btn-func6" : "";
		$tuto.on('click', '#btn-cookie'+vis+'', function(e){
			e.preventDefault();
			$tuto.fadeOut();
			$body.css({'overflow-y':'scroll'});
			docCookies.setItem('tutoFiches', false, cookie_expire);
			docCookies.setItem('tutoDetails', false, cookie_expire);
			removeTuto = setInterval(function() {
	    		$tuto.remove();
	    		clearInterval(removeTuto);
	    	},1000);	
		});
	}
});