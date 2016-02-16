<?php
/**
 * Вывести число промо-акций, в которых участвует блогер
 */

$user = $modx->user;
$profile = $user->getOne('Profile');
if ($profile) {
	$extended = $profile->get('extended');
	// Если в профиле пользователя есть записи о подключенных акциях
	if ($extended['promo_actions'] != NULL) {
		$pa_count = count($extended['promo_actions']);
	}

	return $pa_count;
}