<?php
/**
 * Получить массив существующих промо-кодов из БД
 */
$promo_action_code = $scriptProperties['pa_code'];
	$c = $modx->newQuery('modResource');
	$c->select('pagetitle');
	$c->where(array('parent' => 5135, 'pagetitle:REGEXP' =>  '^' . $promo_action_code . '[0-9]{1,4}$')); //$promo_action_resid));
	$c->sortby('pagetitle', 'ASC');

$c->prepare();
$modx->log(xPDO::LOG_LEVEL_ERROR, 'existed_pcodes ' . $c->toSQL());


	if ($c->prepare() && $c->stmt->execute()) {
		$snum = $c->stmt->fetchAll(PDO::FETCH_COLUMN);
	}
	else{
		$modx->log(xPDO::LOG_LEVEL_ERROR, 'existed_pcodes snippet ERROR');
		return false;
	}

$modx->log(xPDO::LOG_LEVEL_ERROR, 'pcode_processor $snum:' . print_r($snum, true));
return json_encode($snum);
/*
	if(is_array($snum) && sizeof($snum) > 0)	{
		$start_pcode = intval(substr($snum[0], 2)) + 1;
		return json_encode($snum);
	}
	else{
		$start_pcode = 1;
	}
	return (int) $start_pcode;*/