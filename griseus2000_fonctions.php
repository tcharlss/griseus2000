<?php

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Retourne les paramètres de personnalisation css de l'espace privé
 *
 * Surcharge pour virer les couleurs !
 *
 * @see parametres_css_prive()
 * @return string
 */
function filtre_parametres_css_prive_dist() {
	$args = array();
	$args['v'] = $GLOBALS['spip_version_code'];
	$args['p'] = substr(md5($GLOBALS['meta']['plugin']), 0, 4);
	$args['themes'] = implode(',', lister_themes_prives());
	$args['ltr'] = $GLOBALS['spip_lang_left'];
	// un md5 des menus : si un menu change il faut maj la css
	$args['md5b'] = (function_exists('md5_boutons_plugins') ? md5_boutons_plugins() : '');

	// Il faudrait qu’on ne les utilise plus du tout.
	// Cela dit la couleur par défaut historique écrite dans les squelette est bleu vif, surchageons !
	$args['couleur_foncee'] = '808080';
	$args['couleur_claire'] = '909090';

	if (_request('var_mode') == 'recalcul' or (defined('_VAR_MODE') and _VAR_MODE == 'recalcul')) {
		$args['var_mode'] = 'recalcul';
	}

	return http_build_query($args);
}

/**
 * Retourne une couleur proche de celle choisie par l’utilisateur
 *
 * On limite le nombre de couleurs possibles (~24), c'est surtout pour générer le favicon !
 * Un jour... il y aura des favicon en SVG stylables en CSS... Un jour !
 *
 */
function utilisateur_couleur_favicon() {
	$h = isset($GLOBALS['visiteur_session']['prefs']['teinte']) ? $GLOBALS['visiteur_session']['prefs']['teinte'] : 0;
	$s = isset($GLOBALS['visiteur_session']['prefs']['saturation']) ? $GLOBALS['visiteur_session']['prefs']['saturation'] : 10;
	$h = intval(round($h / 30)) * 30;
	$s = ($s < 40) ? 20 : 70;
	return GriseusColors::hslToHex([$h/360, $s/100, .5]);
}


/**
 * Calculer les valeurs hsl depuis une couleur hexa
 *
 * @param string $hex
 * @param string|null $type Type souhaité : h,l,s ou 'all'
 * @return string|array
 *     - valeur demandé si type h, l ou s
 *     - tableau [h,l,s] si type 'all'
 *     - couleur CSS 'hls(h,l,s)' sinon.
 */
function couleur_hsl($hex, $type = null) {
	list($h, $s, $l) = GriseusColors::hexToHsl($hex);
	$hd = intval($h * 360);
	$sp = intval($s * 100);
	$lp = intval($l * 100);
	switch ($type) {
		case 'h':
			return $hd;
			break;
		case 's':
			return $sp;
			break;
		case 'l':
			return $lp;
			break;
		case 'all':
			return ['h' => $hd, 's' => $sp, 'l' => $lp];
			break;
	}
	return "hsl($hd, $sp, $lp)";
}




/**
 * Passe une couleur hexa en hsl et vice versa
 *
 * @see https://gist.github.com/bedeabza/10463089
 */
class GriseusColors {

	public static function hexToHsl($hex) {
		$hex = ltrim($hex, '#');
		$hex = array($hex[0].$hex[1], $hex[2].$hex[3], $hex[4].$hex[5]);
		$rgb = array_map(function($part) {
			return hexdec($part) / 255;
		}, $hex);

		$max = max($rgb);
		$min = min($rgb);

		$l = ($max + $min) / 2;

		if ($max == $min) {
			$h = $s = 0;
		} else {
			$diff = $max - $min;
			$s = $l > 0.5 ? $diff / (2 - $max - $min) : $diff / ($max + $min);

			switch($max) {
				case $rgb[0]:
					$h = ($rgb[1] - $rgb[2]) / $diff + ($rgb[1] < $rgb[2] ? 6 : 0);
					break;
				case $rgb[1]:
					$h = ($rgb[2] - $rgb[0]) / $diff + 2;
					break;
				case $rgb[2]:
					$h = ($rgb[0] - $rgb[1]) / $diff + 4;
					break;
			}

			$h /= 6;
		}

		return array($h, $s, $l);
	}

	public static function hslToHex($hsl)
	{
		list($h, $s, $l) = $hsl;

		if ($s == 0) {
			$r = $g = $b = 1;
		} else {
			$q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
			$p = 2 * $l - $q;

			$r = GriseusColors::hue2rgb($p, $q, $h + 1/3);
			$g = GriseusColors::hue2rgb($p, $q, $h);
			$b = GriseusColors::hue2rgb($p, $q, $h - 1/3);
		}

		return GriseusColors::rgb2hex($r) . GriseusColors::rgb2hex($g) . GriseusColors::rgb2hex($b);
	}

	public static function hue2rgb($p, $q, $t) {
		if ($t < 0) $t += 1;
		if ($t > 1) $t -= 1;
		if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
		if ($t < 1/2) return $q;
		if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;

		return $p;
	}

	public static function rgb2hex($rgb) {
		return str_pad(dechex($rgb * 255), 2, '0', STR_PAD_LEFT);
	}

}






