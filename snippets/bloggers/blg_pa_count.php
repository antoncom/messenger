<?php
/**
 * Вывести число промо-акций, в которых участвует блогер
 */

$userId = $modx->getOption('blgid',$scriptProperties,false);
if (empty($userId)) {
	$user = $modx->getUser();
	$userId = $user->get('id');
}

$user = $modx->getObject('modUser',$userId);
if (!$user) return '';



// Подсчет делаем посредством таблицы-представления modx_promocodes
// поскольку подсчет через данные профайла может дать неверный результат
// в связи с тем, что акция может быть подключена, но промо-кодов не извлечено

$sql = "SELECT COUNT(DISTINCT(`pa_id`)) FROM `" . $modx->getOption('table_prefix') . "promocodes` WHERE `blogger_id` = " . $userId;
	$q = $modx->prepare($sql);

	$q->execute();
	$res = $q->fetchAll(PDO::FETCH_COLUMN);

return $res[0];