<?php
$out = array();
$error = array();
// Проверка на обязательность заполнения полей
$required = array('firstname', 'middlename', 'lastname', 'passport', 'address', 'inn');
foreach($scriptProperties as $key=> $value)	{
	if(in_array($key, $required) && empty($value)) $error[$key] = 'Это поле не должно быть пустым!';
}

if(count($error) > 0)	{
	$out['result'] = 'error';
	$out['errors'] = $error;
	return json_encode($out);
}

$user = $modx->user;
$profile = $user->getOne('Profile');
$extended = $profile->get('extended');
if ($profile) {
	foreach($scriptProperties as $field=> $value)	{
		switch($field)	{
			default:
				$extended['taxing'][$field] = $value;
				$profile->set('extended', $extended);
				$profile->save();
		}
	}
	$out['result'] = 'ok';
	return json_encode($out);
}