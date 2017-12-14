<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2017                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

/**
 * Formulaire de configuration des préférences auteurs dans l'espace privé
 *
 * Ces préférences sont stockées dans la clé `prefs` dans la session de l'auteur
 * en tant que tableau, ainsi que dans la colonne SQL `prefs` de spip_auteurs
 * sous forme sérialisée.
 *
 * @package SPIP\Core\Formulaires
 **/

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Chargement du formulaire de préférences d'un auteur dans l'espace privé
 *
 * @return array
 *     Environnement du formulaire
 **/
function formulaires_configurer_preferences_charger_dist() {
	// travailler sur des meta fraiches
	include_spip('inc/meta');
	lire_metas();

	// Teinte / Saturation  par défaut
	$valeurs = [
		'teinte' => 240,
		'saturation' => 30,
		#'luminosite' => 60,
	];
	$prefs = isset($GLOBALS['visiteur_session']['prefs']) ? $GLOBALS['visiteur_session']['prefs'] : [];
	foreach ($valeurs as $nom => $def) {
		$valeurs[$nom] = isset($prefs[$nom]) ? intval($prefs[$nom]) : $valeurs[$nom];
	}

	$couleurs = charger_fonction('couleurs', 'inc');
	$les_couleurs = $couleurs(array(), true);

	include_spip('griseus2000_fonctions');
	foreach ($les_couleurs as $k => $c) {
		$couleur = $c['couleur_foncee'];
		$hsl = couleur_hsl($couleur,'all');
		$valeurs['couleurs'][$k] = [
			'couleur' => $couleur,
			'teinte' => intval(round($hsl['h'] / 5)) * 5,
			'saturation' => intval(round($hsl['s'] / 10)) * 10,
			//'luminosite' => $hsl['l'],
		];
	}

	$valeurs['imessage'] = $GLOBALS['visiteur_session']['imessage'];

	return $valeurs;
}

/**
 * Traitements du formulaire de préférences d'un auteur dans l'espace privé
 *
 * @return array
 *     Retours des traitements
 **/
function formulaires_configurer_preferences_traiter_dist() {

	// non utilisé dans ce thème
	unset($GLOBALS['visiteur_session']['prefs']['couleur']);
	unset($GLOBALS['visiteur_session']['prefs']['display']);
	unset($GLOBALS['visiteur_session']['prefs']['display_navigation']);
	unset($GLOBALS['visiteur_session']['prefs']['display_outils']);
	// 'spip_ecran' également non utilisé

	if ($teinte = _request('teinte')) {
		$GLOBALS['visiteur_session']['prefs']['teinte'] = $teinte;
	}
	if ($saturation = _request('saturation')) {
		$GLOBALS['visiteur_session']['prefs']['saturation'] = $saturation;
	}
	if (intval($GLOBALS['visiteur_session']['id_auteur'])) {
		include_spip('action/editer_auteur');
		$c = array('prefs' => serialize($GLOBALS['visiteur_session']['prefs']));

		if (_request('imessage')) {
			$c['imessage'] = _request('imessage');
		}

		auteur_modifier($GLOBALS['visiteur_session']['id_auteur'], $c);
	}

	return array('message_ok' => _T('config_info_enregistree'), 'editable' => true);
}
