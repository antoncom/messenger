<?php
$out = array();

$modx->log(xPDO::LOG_LEVEL_ERROR, 'update_profile: ' . print_r($scriptProperties, true));

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

$user_id = $scriptProperties['user_id'];
$user = (!empty($user_id)) ? $modx->getObject('modUser', array('id' => $user_id)) : $modx->user;

$profile = $user->getOne('Profile');
if ($profile) {
	foreach($scriptProperties as $field=> $value)	{
		switch($field)	{
			case('username'):
				$user->set('username', $scriptProperties['username']);
				$user->save();
				break;

			// Пароль меняем путем опции "Сбросить пароль"
//			case('password'):
//				$user->set('password', $scriptProperties['password']);
//				$user->save();
//				break;

			default:
				$profile->set($field, $value);
				$profile->save();
		}
	}
	$out['result'] = 'ok';
	return json_encode($out);
}