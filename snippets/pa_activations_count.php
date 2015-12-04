<?php
/**
 * Counts a number of activations for a promo-action
 */

if(!empty($scriptProperties['pa_id']))	{
	$andSql = "";
	if(!empty($scriptProperties['blogger_id']))	{
		$andSql = " AND `blogger_id` = " . $scriptProperties['blogger_id'];
	}
	$sql = "SELECT COUNT(id) FROM `" . $modx->getOption('table_prefix') . "activations` WHERE `pa_id` = " . $scriptProperties['pa_id'] . $andSql;
	$q = $modx->prepare($sql);
	$q->execute();
	$res = $q->fetchAll(PDO::FETCH_COLUMN);

	return $res[0];
}