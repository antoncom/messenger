<?php
/*
 * Получить ID промокода во известному pagetitle
 * Получить ID блогера по данному промо-коду
 * Получить ID акции по данному промо-коуд
 * Либо вернуть текст ошибки
 */
$pcodeData = array();
if(!empty($scriptProperties['pagetitle'])) {
	$pagetitle = $scriptProperties['pagetitle'];
	$q= $modx->newQuery('modResource');
	$q->select('id');
	$q->where("pagetitle = '".$pagetitle."'");
	$q->prepare();
	$q->stmt->execute();
	$res = $q->stmt->fetchColumn();

	if(isset($res))	{
		$pcodeData['id'] = $res;
	}
	else	{
		$pcodeData['error'] = "В системе не найден идентификатор для промо-кода [" . $pagetitle . "]";
	}
	if($bid = $modx->runSnippet('pdoField', array('id' => $pcodeData['id'], 'field' => 'blogger_id')))	{
		$pcodeData['blogger_id'] = $bid;
	}
	else	{
		$pcodeData['error'] = "В системе не найден идентификатор блоггера для промо-кода [" . $pagetitle . "]";
	}
	if($prnt = $modx->runSnippet('pdoField', array('id' => $res, 'field' => 'pa_id')))	{
		$pcodeData['pa_id'] = $prnt;
	}
	else	{
		$pcodeData['error'] = "В системе не найден идентификатор промо-акции для промо-кода [" . $pagetitle . "]";
	}
}
return json_encode($pcodeData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);