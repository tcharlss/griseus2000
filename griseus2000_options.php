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

	// Uniquement avant SPIP 3.3 ou la révision 23832
	$spip_version = floatval(spip_version());
	$revision = abs(version_svn_courante(_DIR_RACINE));
	if ($spip_version < 3.3
		or ($revision and $revision < 23832)
	) {

		// Le numéro de la couleur de l'utilisateur
		$couleur = isset($GLOBALS['visiteur_session']['prefs']['couleur'])
		? $GLOBALS['visiteur_session']['prefs']['couleur']
		: 9;

		// On ajoute la classe sous la forme couleur_N
		$cherche = '/(<body[^>]*class=["\'][^"\']+)/i';
		$remplace = "$1 couleur_$couleur";
		$flux = preg_replace($cherche, $remplace, $flux);

	}

	return $flux;
}