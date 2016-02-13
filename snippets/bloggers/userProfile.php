<?php
$userId = $modx->getOption('id',$scriptProperties,false);
if (empty($userId)) return '';

/* get user and profile by user id */
$user = $modx->getObject('modUser',$userId);
if (!$user) return '';
$profile = $user->getOne('Profile');
if (!$profile) return '';

$userArray = array_merge($user->toArray(),$profile->toArray());
$modx->toPlaceholders($userArray,'user');
return '';