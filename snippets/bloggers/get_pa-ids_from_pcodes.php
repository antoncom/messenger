<?php
/**
 * Получить список id промоакций блогера в виде "29,30,41"
 * Эти данные берем из таблицы-представления промо-кодов
 */

$pa_list = "";
$userId = $modx->getOption('blgid',$scriptProperties,false);
if (empty($userId)) {
	$user = $modx->getUser();
	$userId = $user->get('id');
}

$user = $modx->getObject('modUser',$userId);
if (!$user) return '';

$sql = "SELECT DISTINCT(`pa_id`) FROM `" . $modx->getOption('table_prefix') . "promocodes` WHERE `blogger_id` = " . $userId;
$q = $modx->prepare($sql);

$q->execute();
$res = $q->fetchAll(PDO::FETCH_COLUMN);

return implode(',',$res);