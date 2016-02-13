<?php
/*
 * Получить список id промоакций блогера в виде "29,30,41"
 */

$pa_list = "";
$user = $modx->user;
$profile = $user->getOne('Profile');
if ($profile) {
	$extended = $profile->get('extended');
	if($extended['promo_actions'] !== NULL)	{
		$pa_list = implode(",", array_values($extended['promo_actions']));
	}
}
return $pa_list;