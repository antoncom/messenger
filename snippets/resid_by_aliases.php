<?php
if(!empty($scriptProperties['aliases'])) {
	$input = $scriptProperties['aliases'];
	$q = $modx->newQuery('modResource');
	$q->select('alias, id');
	$q->where("alias IN (" . $input . ")");
	$q->prepare();
	$q->stmt->execute();
	$res = $q->stmt->fetchAll(PDO::FETCH_ASSOC);

//	$modx->log(XPDO::LOG_LEVEL_ERROR, "RES IDS SQL ===== " . $q->toSQL());

	$output = array_reduce($res, function ($result, $item) {
		$result[$item['alias']] = $item['id'];
		return $result;
	}, array());

	$modx->log(XPDO::LOG_LEVEL_ERROR, "RES IDS BY ALIAS INPUT = " . print_r($input, true));

	return json_encode($output,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
};