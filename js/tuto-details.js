$(document).ready(function(){
	/**
 	 * CE SCRIPT (TUTO) SERA SELEUEMENT ACTIF SUR LA PAGE "DETAILS.PHP"
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
	var cookie_expire = 60 *60 * 24 * 365, cookie_tuto = docCookies.getItem('tutoDetails');
	var typeuser = $('.ariane').find("strong")[0].innerText; // Récupère le type de l'interface
		typeuser = typeuser.substr(0, typeuser.length-2);
	var $body = $('body');
	var url = window.location.pathname; // On récupère l'url afin de le récupèrer la page courant
		url = url.substr(1,url.length); // On enlève le "/"

	// Si le cookie n'existe pas (ou qu'il n'a pas été lancer) et qu'on est sur la page "details.php"
	if(cookie_tuto == null && url == "details.php") {
		$body.css({'overflow':'hidden'}); // Cache la bar de défilement

		// L'écran devient noir & un container & la cookie-bar
		$body.append('<div class="tuto"><div class="tuto-container"></div><div class="tuto-cookiebar"><p>Cliquez si vous ne voulez plus afficher le tutoriel</p><button id="btn-cookie" class="btn-tuto btn-cookie">Ne plus afficher le tutoriel</button></div></div>');

		var $tuto = $('.tuto');
		var $container = $('.tuto-container');

		// Affiche l'infobulle du corps
		$container.append('<div class="tuto-flash-details-etat"></div><div class="tuto-wrap-details-etat arrow-right"><div class="tuto-info">En tant que comptable, vous avez la possibilité de changer l\'état des fiches.</div><button id="btn-details-etat" class="btn-tuto">Fin</button></div>');

		$tuto.on('click', '#btn-details-etat', function(){
			docCookies.setItem('tutoDetails', false, cookie_expire);
			document.querySelector(".nav-menu li:first-child a").click(); // Redirige vers la page d'acceuil
		});


		// Arrête le tuto et ne le réaffiche plus
		$tuto.on('click', '#btn-cookie', function(e){
			e.preventDefault();
			$tuto.fadeOut();
			$body.css({'overflow-y':'scroll'});
			docCookies.setItem('tutoDetails', false, cookie_expire);
			removeTuto = setInterval(function() {
	    		$tuto.remove();
	    		clearInterval(removeTuto);
	    	},1000);	
		});
	}

});