<?php
/**
 * Извлечение свободного промо-кода для блогера
 * на входе pa_id
 * на выходе свободный промокод
 */

if(!empty($scriptProperties['pa_id']))	{
	$pa_id = $scriptProperties['pa_id'];

	// Проверяем подключен ли блогер к акции
	// Если нет, то подключаем

	$r = $modx->runSnippet('blogger_join_promoaction', array(
		'pa_id' => $pa_id,
		'bonus_method' => 'phone'
	));
//	if($r['result'] !== 'ok') {
//		return 'Ошибка подключения к акции. Код ошибки 915409';
//	}

	// Проверяем: если у блогера есть уже активный промо-код, то ничео не извлекаем
	$pc_active = json_decode($modx->runSnippet('blg_active_promocode', array(
			'pa_id' => $pa_id,
			'blogger_id' => $modx->user->get('id')
	)), true);

	// Если активный промо-код найден, возвращаем его
	if(!empty($pc_active))	{
		return $pc_active['pcode'];
	}

	// Если у блоггера нет активного промо-кода
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

	// Если в системе закончились промо-коды
	// т.е. не найдено ни одного свободногопромо-кода по данной акции
	if(empty($res))	{
		return 'Нет свободных промо-кодов!';
	}

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
else{
	return "Извлечение..."; // Если pa_id не указан, то сниппет вызывается в закрытом модальном окне извлечения промокода
}