<?php
/**
 * Получить последнюю установленную цену бонуса данной акции
 */
if(!empty($scriptProperties['pa_id']))	{
	$sql = "SELECT pagetitle FROM `"
		. $modx->getOption('table_prefix') . "site_content` WHERE `parent` = "
		. $scriptProperties['pa_id']
		. " ORDER BY `createdon` DESC LIMIT 1";
	$q = $modx->prepare($sql);
	$q->execute();
	$res = $q->fetchAll(PDO::FETCH_COLUMN);

	return $res[0];
}