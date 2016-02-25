<?php
/**
 * Получить id пользователя из GET запроса
 * а если нет, то из объекта modUser
 **/

$fromGET = $_GET['blgid'];

if(empty($fromGET))	{
	$user = $modx->getUser();
	$userId = $user->get('id');
	$user = $modx->getObject('modUser',$userId);
	if (!$user) return '';
}
else{
	$userId = $fromGET;
}

return $userId;

