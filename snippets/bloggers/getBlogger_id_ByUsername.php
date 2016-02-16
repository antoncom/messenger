<?php
/**
 * Получить id блогера на основании данного username
 */
$userName = $modx->getOption('username',$scriptProperties,false);
if (empty($userName)) {
	return '';
}

$user = $modx->getObject('modUser', array(
	'username' => $userName
));

return $user->get('id');
