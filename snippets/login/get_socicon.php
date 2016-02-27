<?php
/**
 * Получить пиктограмму соц.сервиса, при помощи которого залогинился юзер
 */

$userId = $modx->getOption('userid',$scriptProperties,false);
if (empty($userId)) {
	$user = $modx->getUser();
	$userId = $user->get('id');
}

$user = $modx->getObject('modUser',$userId);
if (!$user) return '';

$username = $user->get('username');
$sql = "SELECT provider FROM `" . $modx->getOption('table_prefix') . "ha_user_services` WHERE `identifier` = " . $username;
$q = $modx->prepare($sql);
$q->execute();
$res = $q->fetchAll(PDO::FETCH_COLUMN);

if(empty($res[0])) return;
else $provider = strtolower($res[0]);

$out = '<span class="socicon '.$provider.'">&nbsp;</span>';
return $out;

