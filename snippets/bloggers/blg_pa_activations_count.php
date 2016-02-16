<?php
/**
 * Посчитать число активаций промо-кодов блогера
 * На входе список промо-акций вида 23,26,31
 * и id блогера
 */

if(!empty($scriptProperties['pa_ids']))	{
	$andSql = "";
	if(!empty($scriptProperties['blogger_id']))	{
		$andSql = " AND `blogger_id` = " . $scriptProperties['blogger_id'];
	}
	$sql = "SELECT COUNT(id) FROM `" . $modx->getOption('table_prefix') . "activations` WHERE `pa_id` IN (" . $scriptProperties['pa_ids'] . ") " . $andSql;
	$q = $modx->prepare($sql);
	$q->execute();
	$res = $q->fetchAll(PDO::FETCH_COLUMN);

	return $res[0];
}