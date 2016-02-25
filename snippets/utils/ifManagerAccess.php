<?php
/**
 * Проверить кто входит на страницу
 * Если менеджер, то вернуть 1 иначе 0.
 */

$user = $modx->getUser();
$userId = $user->get('id');
$user = $modx->getObject('modUser', array('id' => $userId));
if (!$user) return '';

$modx->log(xPDO::LOG_LEVEL_ERROR, "USER GROUPS _____: " . $user->isMember(array('Managers', 'Administrator'), false));
return ($user->isMember(array('Managers', 'Administrator'), false)) ? '1' : '0' ;
