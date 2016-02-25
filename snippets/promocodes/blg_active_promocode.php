<?php
/**
 * Найти активный промо-код блогера
 * и выдать если есть
 **/

if(!empty($scriptProperties['blogger_id']) && !empty($scriptProperties['pa_id'])) {
	$blogger_id = $scriptProperties['blogger_id'];
	$pa_id = $scriptProperties['pa_id'];

	// Для подключенного блогера получаем состояние промо-кода
	$q = $modx->newQuery('modResource');
	$q->select('pagetitle, modResource.id AS pc_id, modTemplateVarResourceEndDate.value AS end_date');
	$q->leftJoin('modTemplateVarResource', 'modTemplateVarResourceBlg', array('modTemplateVarResourceBlg.tmplvarid = 5',
		'modTemplateVarResourceBlg.contentid = modResource.id'));
	$q->leftJoin('modTemplateVarResource', 'modTemplateVarResourcePa', array('modTemplateVarResourcePa.tmplvarid = 15',
		'modTemplateVarResourcePa.contentid = modResource.id'));
	$q->leftJoin('modTemplateVarResource', 'modTemplateVarResourceEndDate', array('modTemplateVarResourceEndDate.tmplvarid = 4',
		'modTemplateVarResourceEndDate.value > NOW()',
		'modTemplateVarResourceEndDate.value = modResource.id'));

	$q->where(array('parent' => 5135,
		'modTemplateVarResourceBlg.value' => $blogger_id,
		'modTemplateVarResourcePa.value' => $pa_id,
		'modTemplateVarResourceEndDate.value'=>'modResource.id'));
	$q->limit(1);
	$q->prepare();

	$q->stmt->execute();
	$res = $q->stmt->fetchAll(PDO::FETCH_ASSOC);

	$out = array();
	if (count($res) > 0) {
		$out['pcode'] = $res[0]['pagetitle'];
		$out['pc_id'] = $res[0]['pc_id'];
		// получаем число активаций промо-кода
		$out['pc_activations_count'] = $modx->runSnippet('pc_activations_count', array(
			'pc_id' => $res[0]['pc_id'],
		));
	}
	return json_encode($out);
}