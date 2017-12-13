<?php

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

// Définir le thème par défaut
$GLOBALS['theme_prive_defaut'] = 'griseus2000';

/**
 * Ajouter des choses dans le <head> du privé
 */
function griseus2000_header_prive($flux) {

	if ($js = find_in_path('prive/themes/griseus2000/javascript/griseus2000.js')) {
		$flux .=
			"<!-- JS Plugin Griseus2000 -->\n" .
			"<script type='text/javascript' src='$js'></script>";
	}

	return $flux;
}

/**
 * Modifier la balise <body> de l'espace privé
 *
 * On ajoute une classe « couleur_N » pour indiquer la couleur de l'utilisateur.
 *
 * @see
 * Il y a la fonction init_body_class() qui permet d'ajouter des classes au <body>, mais il n'y a pas de point d'entrée et elle n'est pas surchargeable.
 *
 * @param string $flux Balise <body> entière
 * @return srting
 */
function griseus2000_body_prive($flux) {

	// Teinte / Saturation  par défaut
	$valeurs = [
		'teinte' => 240,
		'saturation' => 30,
		#'luminosite' => 60,
	];

	// Teinte / Saturation  de l’utilisateur
	$prefs = isset($GLOBALS['visiteur_session']['prefs']) ? $GLOBALS['visiteur_session']['prefs'] : [];
	$css = '';
	$data = '';
	foreach ($valeurs as $nom => $def) {
		$valeurs[$nom] = isset($prefs[$nom]) ? intval($prefs[$nom]) : $valeurs[$nom];
		$css .= " $nom-{$valeurs[$nom]}";
		$data .= " data-$nom='{$valeurs[$nom]}'";
	}

	// On ajoute les classes sous la forme teinte-240 saturation-20
	$flux = preg_replace('/(<body[^>]*class=["\'][^"\']+)/i', "$1$css", $flux);
	// On ajoute les datas sous la forme data-teinte=240 data-saturation=20
	$flux = preg_replace('/(<body[^>]*)/i', "$1$data", $flux);

	return $flux;
}