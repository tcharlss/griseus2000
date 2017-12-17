function focus_zone(selecteur){
	jQuery(selecteur).eq(0).find('a,input:visible').get(0).focus();
	return false;
}

jQuery(function($){
	function init_gadgets(url_menu_rubrique){
		$('#boutonbandeautoutsite').one('mouseover',function(){
			$(this).siblings('ul').find('li:first>a').animeajax();
			$.ajax({
				url: url_menu_rubrique,
				success: function(c){
					$('#boutonbandeautoutsite').siblings('ul').remove();
					$('#boutonbandeautoutsite').after(c).parent().find('li').menuFocus();
				}
			});
		}).one('focus touchend', function(){
			$(this).trigger('mouseover');
		});
	}

	init_gadgets(url_menu_rubrique);

	// deplier le menu au focus clavier,
	// enlever ce depliement si passage a la souris,
	// delai de fermeture.
	$.fn.menuFocus = function(){
		var $racine = $(this);
		var $deroulants = $racine.find('.deroulant');

		$deroulants
			// ouvrir celui ci, fermer les autres
			.on('mouseenter', 'li', function(){
				var $parents_and_me = $(this).parents('li').add($(this)).addClass('actif');
				$deroulants.find('.actif').not($parents_and_me).removeClass('actif').blur();
				// le sous menu de navigation
				if (
					$(this).parents('#bando_navigation').length
					&& $(this).parent('.deroulant').length
					&& $(this).find('> ul').length
				) {
					var $sous_menu = $(this).find('> ul');
					if (($sous_menu.position().top + $sous_menu.height()) > $(window).height()) {
						$sous_menu
							.css('top', $(this).position().top + $(this).height() - $sous_menu.height()).css('margin-top', 0);
					}
				}
			})
			.on('mouseleave', 'li', function(){
				$(this).removeClass('actif');
			})
			// navigation au clavier : deplier le ul enfant
			.on('focus', 'li > a', function(){
				$(this).parent('li').trigger('mouseenter');
			})
			// navigation flèches
			.on('keypress', 'li > a', function(event) {
				// tout en haut dans le bando_principal
				if ($(this).closest('ul').is('.deroulant') && $(this).parents('#bando_principal').length) {
					if (event.key === 'ArrowDown') {
						$(this).parent().find('> ul > li:first-child a').focus();
						event.stopPropagation(); // éviter scroll navigateur
						return false;
					} else if (event.key === 'ArrowRight') {
						$(this).closest('ul').next().find('a').first().focus();
					} else if (event.key === 'ArrowLeft') {
						$(this).closest('ul').prev().find('a').first().focus();
					}
				// navigation dans les menus… ailleurs
				} else {
					if ( event.key === 'ArrowDown') {
						var $next = $(this).parent().next();
						var $sel = ($next.length) ? $next : $(this).closest('ul').find('> li:first-child');
						$sel.find('> a').first().focus();
						event.stopPropagation(); // éviter scroll navigateur
						return false;
					} else if ( event.key === 'ArrowUp') {
						var $prev = $(this).parent().prev();
						var $sel = ($prev.length) ? $prev : $(this).closest('ul').find('> li:last-child');
						$sel.find('> a').first().focus();
						event.stopPropagation(); // éviter scroll navigateur
						return false;
					} else if ( event.key === 'ArrowRight') {
						$(this).parent().find('> ul > li:first-child > a').focus();
					} else if ( event.key === 'ArrowLeft') {
						$(this).closest('ul').parent().find('> a').focus();
					}
				}
			})

			// navigation au doigt des items déroulants
			.has('ul').find(' > a')
			.on('touchend', function(event) {
				event.preventDefault();
				var me = jQuery(this).parent();
				if (me.hasClass('actif')) {
					me.trigger('mouseleave').find('> a').trigger('blur');
				} else {
					me.trigger('mouseenter').find('> a').trigger('focus');
				}
			});
		return this;
	}

	$('#bando_haut')
		.menuFocus()
		// le focus de certains éléments doit replier les menus dépliés
		.find('#bando_rechercher input, #bando_liens_rapides a, #bando_site a')
		.on('focus touchstart', function() {
			$('#bando_haut').find('.deroulant .actif').removeClass('actif');
		});

	if (typeof window.test_accepte_ajax != "undefined") {
		test_accepte_ajax();
	}
});
