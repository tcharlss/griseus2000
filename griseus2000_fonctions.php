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
 * Calculer la teinte depuis une couleur hexa
 * @param string $hex
 * @return string
 */
function couleur_hsl($hex, $type = null) {
	list($h, $s, $l) = hexToHsl($hex);
	$hd = intval($h * 360);
	$sp = intval($s * 100);
	$lp = intval($l * 100);
	switch ($type) {
		case 'teinte':
			return $hd;
			break;
		case 'saturation':
			return $sp;
			break;
		case 'luminosite':
			return $lp;
			break;
	}
	return [$hd, $sp, $lp];
}

/**
 * Passe une couleur hexa en hsl
 * @see https://gist.github.com/bedeabza/10463089
 *
 * @param string $hex
 * @return array
 */
function hexToHsl($hex) {
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