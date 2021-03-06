function focus_zone(selecteur){
	jQuery(selecteur).eq(0).find('a,input:visible').get(0).focus();
	return false;
}

jQuery(function($){
	function init_gadgets(url_menu_rubrique) {
		$('#boutonbandeautoutsite').one('click', function(){
			$(this).siblings('ul').find('li:first>a').animeajax();
			$.ajax({
				url: url_menu_rubrique,
				success: function(c){
					$('#boutonbandeautoutsite').siblings('ul').remove();
					$('#boutonbandeautoutsite').after(c).parent().find('li').menuFocus();
				}
			});
		});
	}

	init_gadgets(url_menu_rubrique);

	/**
	 * Gérer le dépliement des boutons / liste de navigation du bandeau haut.
	 * (infos persos, plan du site, créations, collaboration)
	 */
	$('#bando_principal').each(function() {
		var $menu = $(this);
		var $navs = $menu.find('nav.depliant');
		$navs
			.on('click', '> .toggle', function(event){
				$menu.find('.actif').removeClass('actif');
				event.stopPropagation();
				$(this).focus();
				if ($(this).attr('aria-expanded') === 'false') {
					$menu.find('.toggle[aria-expanded=true]').attr('aria-expanded', 'false');
					$(this).attr('aria-expanded', 'true');
					// fermer le menu sur un clic extérieur
					$('html').one('click', function() {
						$menu.find('.toggle[aria-expanded=true]').attr('aria-expanded', 'false');
					});
				} else {
					$(this).attr('aria-expanded', 'false');
				}
				return false;
			})
			// pour les sous menus (plan du site...)
			.on('mouseenter focus', 'li', function() {
				$(this).parent().find('.actif').removeClass('actif');
				$(this).addClass('actif');
			})
			// navigation flèches
			.on('keypress', '> .toggle', function(event) {
				// tout en haut dans le bando_principal
				var ouvert = $(this).attr('aria-expanded') === 'true';
				if (event.key === 'ArrowDown') {
					// si non ouvert, on ouvre le menu
					if (!ouvert) { $(this).trigger('click'); }
					$(this).parent().find('> ul > li:first-child a').focus();
					event.stopPropagation(); // éviter scroll navigateur
					return false;
				} else if (event.key === 'ArrowRight') {
					// si le menu était ouvert, on ouvre le menu suivant aussi
					var $next = $(this).closest('nav').next().find('> .toggle').first().focus();
					if (ouvert) {  $next.trigger('click'); }
				} else if (event.key === 'ArrowLeft') {
					// si le menu était ouvert, on ouvre le menu précédent aussi
					var $last = $(this).closest('nav').prev().find('> .toggle').first().focus();
					if (ouvert) {  $last.trigger('click'); }
				}
			})
			.on('keypress', 'li > a', function(event) {
				// dans la liste qui s’est ouverte
				if ( event.key === 'ArrowDown') {
					var $next = $(this).parent().next();
					var $sel = ($next.length) ? $next : $(this).closest('ul').find('> li:first-child');
					$sel.find('> a').first().focus();
					event.stopPropagation(); // éviter scroll navigateur
					return false;
				} else if ( event.key === 'ArrowUp') {
					// si on remonte tout en haut, repasser sur le bouton .toggle
					var $prev = $(this).parent().prev();
					if ($prev.length) {
						$prev.find('> a').first().focus();
					} else if ($(this).parent().parent().parent().is('nav')) {
						$(this).closest('nav').find('> .toggle').focus();
					} else {
						$(this).closest('ul').find('> li:last-child > a').first().focus();
					}
					event.stopPropagation(); // éviter scroll navigateur
					return false;
				} else if ( event.key === 'ArrowRight') {
					$(this).parent().find('> ul > li:first-child > a').focus();
				} else if ( event.key === 'ArrowLeft') {
					$(this).closest('ul').parent().find('> a').focus();
				}
			});
	});

	/**
	 * Menu de navigation latéral
	 */
	$('#bando_navigation').each(function() {
		var $navs = $(this).find('ul.depliant');
		$navs
			.on('click', '> li > a', function(event) {
				if ($(this).parent().has('> ul').length) {
					$(this).parent().toggleClass('actif');
					event.stopPropagation();
					return false;
				}
			})
			.on('keypress', '> li > a', function(event) {
				console.log(event.key, event);
				if ( event.key === ' ' ) {
					$(this).trigger('click');
				} else if ( event.key === 'ArrowDown') {
					if ($(this).parent().is('.actif')) {
						$(this).parent().find('> ul > li:first-child > a').first().focus();
					} else {
						$(this).parent().next().find('> a').first().focus();
					}
					return false;
				} else if ( event.key === 'ArrowUp') {
					if ($(this).parent().prev().is('.actif')) {
						$(this).parent().prev().find('> ul > li:last-child > a').first().focus();
					} else {
						$(this).parent().prev().find('> a').first().focus();
					}
					return false;
				}
			})
			// sous entrées de menu
			.on('keypress', 'ul > li > a', function(event) {
				if ( event.key === 'ArrowDown') {
					if ($(this).parent().next().length) {
						$(this).parent().next().find('> a').first().focus();
					} else {
						$(this).closest('ul').parent().next().find('> a').first().focus();
					}
					return false;
				} else if ( event.key === 'ArrowUp') {
					if ($(this).parent().prev().length) {
						$(this).parent().prev().find('> a').first().focus();
					} else {
						$(this).closest('ul').parent().find('> a').first().focus();
					}
					return false;
				}
			})
		;
	});


	// le focus de certains éléments doit replier les menus dépliés
	$('#bando_rechercher input, #bando_site a')
	.on('focus', function() {
		$('#bando_haut').find('.toggle[aria-expanded=true]').attr('aria-expanded', 'false');
	});

	if (typeof window.test_accepte_ajax != "undefined") {
		test_accepte_ajax();
	}
});
