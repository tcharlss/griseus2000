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
 * Couleurs de l'interface de l’espace privé de SPIP.
 *
 * @package SPIP\Core\Couleurs
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Obtenir ou définir les différents jeux de couleurs de l'espace privé
 *
 * - Appelée _sans argument_, cette fonction retourne un tableau décrivant les jeux les couleurs possibles.
 * - Avec un _argument numérique_, elle retourne les paramètres d'URL
 *   pour les feuilles de style calculées (cf. formulaire configurer_preferences)
 * - Avec un _argument de type tableau_ :
 *   - soit elle remplace le tableau par défaut par celui donné en argument
 *   - soit elle le complète, si `$ajouter` vaut `true`.
 *
 * @see formulaires_configurer_preferences_charger_dist()
 *
 * @staticvar array $couleurs_spip
 * @param null|int|array $choix
 * @param bool $ajouter
 * @return array|string
 */
function inc_couleurs_dist($choix = null, $ajouter = false) {
	static $couleurs_spip = [];
	if (empty($couleurs_spip)) {
		include_spip('griseus2000_fonctions');
		$couleurs_spip[] = null;
		$l = .5;
		foreach ([.6, .2] as $s) {
			for ($i = 0; $i < 9; $i++) {
				$h = $i * 40 / 360;
				$couleurs_spip[] = [
					"couleur_foncee" => '#' . GriseusColors::hslToHex([$h, $s, $l]),
					"couleur_claire" => '#' . GriseusColors::hslToHex([$h, $s, $l + .1]),
					"couleur_lien" => '#' . GriseusColors::hslToHex([$h, $s + .05, $l - .1]),
					"couleur_lien_off" => '#' . GriseusColors::hslToHex([$h, $s - .10, $l - .1]),
				];
			};
		}
		$s = 0;
		$h = 0;
		$couleurs_spip[] = [
			"couleur_foncee" => '#' . GriseusColors::hslToHex([$h, $s, $l]),
			"couleur_claire" => '#' . GriseusColors::hslToHex([$h, $s, $l + .1]),
			"couleur_lien" => '#' . GriseusColors::hslToHex([$h, $s + .5, $l - .1]),
			"couleur_lien_off" => '#' . GriseusColors::hslToHex([$h, $s - .10, $l - .1]),
		];
		unset($couleurs_spip[0]);
	}

	if (is_numeric($choix)) {
		// Compatibilite ascendante (plug-ins notamment)
		$GLOBALS["couleur_claire"] = $couleurs_spip[$choix]['couleur_claire'];
		$GLOBALS["couleur_foncee"] = $couleurs_spip[$choix]['couleur_foncee'];
		$GLOBALS["couleur_lien"] = $couleurs_spip[$choix]['couleur_lien'];
		$GLOBALS["couleur_lien_off"] = $couleurs_spip[$choix]['couleur_lien_off'];

		return
			"couleur_claire=" . substr($couleurs_spip[$choix]['couleur_claire'], 1) .
			'&couleur_foncee=' . substr($couleurs_spip[$choix]['couleur_foncee'], 1);
	} else {
		if (is_array($choix)) {
			if ($ajouter) {
				foreach ($choix as $c) {
					$couleurs_spip[] = $c;
				}

				return $couleurs_spip;
			} else {
				return $couleurs_spip = $choix;
			}
		}

	}

	return $couleurs_spip;
}
