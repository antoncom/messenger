<?php
/**
 * Counts a number of activations for a promo-code
 */

if(!empty($scriptProperties['pc_id']))	{
	$sql = "SELECT COUNT(id) FROM `" . $modx->getOption('table_prefix') . "activations` WHERE `pc_id` = " . $scriptProperties['pc_id'];
	$q = $modx->prepare($sql);
	$q->execute();
	$res = $q->fetchAll(PDO::FETCH_COLUMN);

	return $res[0];
}