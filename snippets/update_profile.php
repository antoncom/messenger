<?php

$required = array('username', 'email', 'fullname', 'mobilephone');
foreach($scriptProperties as $key=> $value)	{
	if(in_array($key, $required) && empty($value)) $error[$key] = 'Это поле не должно быть пустым!';
}

$user = $modx->user;
$profile = $user->getOne('Profile');
if ($profile) {
	foreach($scriptProperties as $field=> $value)	{
		switch($field)	{
			case('username')
			default:
				$profile->set($field, $value);
				$profile->save();
		}
	}
}
$r = json_decode($modx->runSnippet('update_profile', array(
	'username' => $params['username'],
	'email' => $params['email'],
	'gender' => $params['gender'],
	'dob' => $params['dob'],
	'fullname' => $params['fullname'],
	'password' => $params['password'],
	'mobilephone' => $params['mobilephone'])), true);
if($r == '1')	{

}
if(!empty($scriptProperties['pa_id']) && !empty($scriptProperties['bonus_method']))	{
	$pa_id = $scriptProperties['pa_id'];
	$bonus_method = $scriptProperties['bonus_method'];

	$user = $modx->user;
	$profile = $user->getOne('Profile');
	if ($profile) {
		$extended = $profile->get('extended');
		if($extended['promo_actions'] == NULL)	{
			$extended['promo_actions'] =  array($pa_id);
			$extended['bonus_method'] =  array($pa_id => $bonus_method);
		}
		else	{
			if(array_search($pa_id, $extended['promo_actions']) !== FALSE)	return;
			array_push($extended['promo_actions'], $pa_id);
			$extended['bonus_method'][$pa_id] = $bonus_method;
		}
		$profile->set('extended', $extended);
		$profile->save();
		return "Пользователь " . $profile->get('fullname') . " подключен к акции.";

	} else {
		$modx->log(modX::LOG_LEVEL_ERROR,
			'blogger_join_promoaction[SNIPPET] Could not find profile for user: ' .
			$usr->get('username'));
	}
}