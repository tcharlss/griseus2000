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
			})
			.on('mouseleave', 'li', function(){
				$(this).removeClass('actif');
			})
			// navigation au clavier : deplier le ul enfant
			.on('focus', 'li > a', function(){
				var $parents = $(this).parents('li').addClass('actif');
				$deroulants.find('.actif').not($parents).removeClass('actif');
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
