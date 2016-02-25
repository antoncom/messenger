<?php
$userId = $modx->getOption('blgid',$scriptProperties,false);
if (empty($userId)) {
	$user = $modx->getUser();
	$userId = $user->get('id');
}
$user = $modx->getObject('modUser',$userId);
if (!$user) return '';


$profile = $user->getOne('Profile');
if (!$profile) return '';

$userArray = array_merge($user->toArray(),$profile->toArray());
$modx->toPlaceholders($userArray,'beeuser');
return '';