<?php

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Retourne la liste des couleurs de l’espace privé où d’une couleur
 *
 * @see inc_couleurs_dist()
 * @param null|int $couleur
 * @return array
 */
function recuperer_couleurs_espace_prive($couleur = null) {
	$couleurs = charger_fonction('couleurs', 'inc');
	$couleurs = $couleurs();
	if ($couleur and $couleur = intval($couleur)) {
		return isset($couleurs[$couleur]) ? $couleurs[$couleur] : [];
	}
	// mettre la couleur par défaut en premier.
	$couleur_defaut = 9;
	ksort($couleurs);
	$couleurs = [$couleur_defaut => $couleurs[$couleur_defaut]] + $couleurs;
	return $couleurs;
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






