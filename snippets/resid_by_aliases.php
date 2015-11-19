<?php
if(!empty($scriptProperties['aliases'])) {
	$input = $scriptProperties['aliases'];
	$q = $modx->newQuery('modResource');
	$q->select('alias, id');
	$q->where("alias IN (" . $input . ")");
	$q->prepare();
	$q->stmt->execute();
	$res = $q->stmt->fetchAll(PDO::FETCH_ASSOC);

	$output = array_reduce($res, function ($result, $item) {
		$result[$item['alias']] = $item['id'];
		return $result;
	}, array());


	return json_encode($output);
};

