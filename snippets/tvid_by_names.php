<?php

if(!empty($scriptProperties['names'])) {
	$input = $scriptProperties['names'];
	$q = $modx->newQuery('modTemplateVar');
	$q->select('name, id');
	$q->where("name IN (" . $input . ")");
	$q->prepare();
	$q->stmt->execute();
	$res = $q->stmt->fetchAll(PDO::FETCH_ASSOC);

	$output = array_reduce($res, function ($result, $item) {
		$result[$item['name']] = $item['id'];
		return $result;
	}, array());

	return json_encode($output);
}