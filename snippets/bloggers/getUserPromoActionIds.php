<?php
/*
 * Получить список id промоакций блогера в виде "29,30,41"
 * Эти данные берем из профайла
 */

$pa_list = "";
$userId = $modx->getOption('blgid',$scriptProperties,false);
if (empty($userId)) {
	$user = $modx->getUser();
	$userId = $user->get('id');
}

$user = $modx->getObject('modUser',$userId);
if (!$user) return '';

$profile = $user->getOne('Profile');
if ($profile) {
	$extended = $profile->get('extended');
	if($extended['promo_actions'] !== NULL)	{
		$pa_list = implode(",", array_values($extended['promo_actions']));
	}
}
return $pa_list;