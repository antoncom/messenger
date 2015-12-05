<?php
/**
 * Извлечение свободного промо-кода для блогера
 * на входе pa_id
 * на выходе свободный промокод
 */

if(!empty($scriptProperties['pa_id']))	{
	$pa_id = $scriptProperties['pa_id'];

	// получаем ближайший свободный промо-код
	$q = $modx->newQuery('modResource');
	$q->select('modResource.id AS id,pagetitle');
	$q->leftJoin('modTemplateVarResource', 'modTemplateVarResourceBlg', array('modTemplateVarResourceBlg.tmplvarid = 5',
			'modTemplateVarResourceBlg.contentid = modResource.id'));
	$q->leftJoin('modTemplateVarResource', 'modTemplateVarResourcePa', array('modTemplateVarResourcePa.tmplvarid = 15',
			'modTemplateVarResourcePa.contentid = modResource.id'));

	$q->where(array('parent' => 5135,
			'modTemplateVarResourceBlg.value:IS' => NULL,
			'modTemplateVarResourcePa.value' => $pa_id));
	$q->sortby('pagetitle', 'ASC');
	$q->limit(1);
	$q->prepare();

	$q->stmt->execute();
	$res = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
	$modx->log(xPDO::LOG_LEVEL_ERROR, $q->toSQL());
	// Присваиваем блоггера для данного промокода
	$user = $modx->getUser();
	$userId = $user->get('id');
	$pcode = $modx->getObject('modResource', $res[0]['id']);
	if (!$pcode->setTVValue('blogger_id', $userId)) {
		$modx->log(xPDO::LOG_LEVEL_ERROR, 'EXTRACT_PROMOCODE SNIPPET: There was a problem saving your TV blogger_id');
	}

	// Присваиваем начальную и конечную дату промо-кода
	$now = date("Y-m-d H:i:s", time());
	if (!$pcode->setTVValue('pc_start_date', $now)) {
		$modx->log(xPDO::LOG_LEVEL_ERROR, 'EXTRACT_PROMOCODE SNIPPET: There was a problem saving your TV pc_start_date');
	}
	$in2months = date('Y-m-d H:i:s', strtotime($now .' +2 month'));
	if (!$pcode->setTVValue('pc_end_date', $in2months)) {
		$modx->log(xPDO::LOG_LEVEL_ERROR, 'EXTRACT_PROMOCODE SNIPPET: There was a problem saving your TV pc_start_date');
	}

	return $res[0]['pagetitle'];
}