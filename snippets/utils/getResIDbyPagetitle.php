<?php
/**
 * getResIDbyPagetitle
 * Created by PhpStorm.
 * User: well
 * Date: 18.11.15
 * Time: 12:41
 */


$resIds = array();
if(!empty($scriptProperties['pagetitles'])) {
	$pagetitles = $scriptProperties['pagetitles'];
	$q = $modx->newQuery('modResource');
	$q->select('pagetitle, id');
	$q->where("pagetitle IN (" . $pagetitles . ")");
	$q->prepare();
	$q->stmt->execute();
	$resIds = $q->stmt->fetchAll(PDO::FETCH_ASSOC);

	return json_encode($resIds);
}