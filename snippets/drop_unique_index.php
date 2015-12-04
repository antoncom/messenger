<?php
if(!empty($scriptProperties['index_name']) &&
	!empty($scriptProperties['table'])) {

	$index_name = $scriptProperties['index_name'];
	$table = $scriptProperties['table'];

	$sql = "ALTER TABLE " . $table .
			" DROP INDEX " . $index_name;
	$q = $modx->prepare($sql);
	$q->execute();
	return '';
};