<?php
/**
 * Прописать мобильный телефон в профиль пользователя
 * Используется при подключении к акции если блогер не указал номер телефона
 */

// Проверка на непустой телефон
if($scriptProperties['mobilephone_notempty'] !== 'yes')	{
	$error['mobilephone_notempty'] = 'Необходимо указать телефон для получения бонусов на баланс.';
}

// Проверка на подтвержденный телефон
if($scriptProperties['mobilephone_confirmed'] !== 'yes')	{
	$error['mobilephone_confirmed'] = 'Необходимо подтвердить телефон SMS-кодом подтверждения.';
}

if(count($error) > 0)	{
	$out['result'] = 'error';
	$out['errors'] = $error;
}
else {
	$out['result'] = 'ok';
}
return json_encode($out);