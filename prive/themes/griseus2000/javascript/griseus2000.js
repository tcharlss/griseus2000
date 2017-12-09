;(function($){
	$(function(){

		// Colonne latérale en position fixe lors du scroll
		navigation_lat_sticky = function(){
			var body      = $('body');
			var navigation = $('#navigation');
			var plein     = ($('#navigation > .ajaxbloc > *').length > 0);
			var offset    = parseInt(navigation.parents('#conteneur').css('margin-top'));
			var limite    = navigation.offset().top - offset;
			var largeur   = navigation.outerWidth()+'px';
			if ( plein ) {
				$(window).scroll(function() {
					if ( $(this).scrollTop() > limite ) {
						body.addClass('nav-is-stuck');
						navigation.css({'width': largeur});
					} else if ( $(this).scrollTop() < limite ) {
						body.removeClass('nav-is-stuck');
						navigation.css({'width': ''});
					}
				});
			}
		}
		navigation_lat_sticky();

	});
})(jQuery);
