<?php
$out = array();

// Проверка на обязательность заполнения полей
$required = array('username', 'email', 'fullname');
foreach($scriptProperties as $key=> $value)	{
	if(in_array($key, $required) && empty($value)) $error[$key] = 'Это поле не должно быть пустым!';
}
// Проверка на подтвержденный телефон
if($scriptProperties['mobilephone_confirmed'] !== 'yes')	{
	$error['mobilephone_confirmed'] = 'Необходимо подтвердить телефон SMS-кодом подтверждения.';
}

if(count($error) > 0)	{
	$out['result'] = 'error';
	$out['errors'] = $error;
	return json_encode($out);
}

$user = $modx->user;
$profile = $user->getOne('Profile');
if ($profile) {
	$modx->log(xPDO::LOG_LEVEL_ERROR, 'update_profile: ' . print_r($scriptProperties, true));
	foreach($scriptProperties as $field=> $value)	{
		switch($field)	{
			case('username'):
				$user->set('username', $scriptProperties['username']);
				$user->save();
				break;

			case('password'):
				$user->set('password', $scriptProperties['password']);
				$user->save();
				break;

			default:
				$profile->set($field, $value);
				$profile->save();
		}
	}
	$out['result'] = 'ok';
	return json_encode($out);
}