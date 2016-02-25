<?php
/**
 * Сохранить данные карты Билайн в профиле блогера
 */
if(!empty($scriptProperties['number']) && !empty($scriptProperties['name']) && !empty($scriptProperties['expiry']))	{
	$number = $scriptProperties['number'];
	$name = $scriptProperties['name'];
	$expiry = $scriptProperties['expiry'];
	$out = 0;

	$user_id = $scriptProperties['user_id'];
	$user = (!empty($user_id)) ? $modx->getObject('modUser', array('id' => $user_id)) : $modx->user;

	$profile = $user->getOne('Profile');
	if ($profile) {
		$extended = $profile->get('extended');
		$extended['blogger_card']['number'] = $number;
		$extended['blogger_card']['name'] = $name;
		$extended['blogger_card']['expiry'] = $expiry;
		$profile->set('extended', $extended);
		$profile->save();

		$out = 1;
	}
	return $out;
}