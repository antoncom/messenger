<?php
// Получаем ближайший доступный стартовый номер для генерации промо-кодов заданной акции
$promo_action_code = $scriptProperties['pa_code'];
	$c = $modx->newQuery('modResource');
	$c->select('pagetitle');
	$c->where(array('parent' => 5135, 'pagetitle:REGEXP' =>  '^' . $promo_action_code . '[0-9]{1,4}$')); //$promo_action_resid));
	$c->sortby('pagetitle', 'DESC');
	$c->limit(1);

$c->prepare();
$modx->log(xPDO::LOG_LEVEL_ERROR, 'pcode_processor ' . $c->toSQL());


	if ($c->prepare() && $c->stmt->execute()) {
		$snum = $c->stmt->fetchAll(PDO::FETCH_COLUMN);
	}
	else{
		$modx->log(xPDO::LOG_LEVEL_ERROR, 'startNum.pcode snippet ERROR');
		return false;
	}

$modx->log(xPDO::LOG_LEVEL_ERROR, 'pcode_processor $snum:' . print_r($snum, true));
	if(is_array($snum) && sizeof($snum) > 0)	{
		$start_pcode = intval(substr($snum[0], 2)) + 1;
	}
	else{
		$start_pcode = 1;
	}
	return (int) $start_pcode;