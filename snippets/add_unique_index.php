<?php
if(!empty($scriptProperties['index_name']) &&
	!empty($scriptProperties['table']) &&
	!empty($scriptProperties['fields'])) {

	$index_name = $scriptProperties['index_name'];
	$table = $scriptProperties['table'];
	$fields = $scriptProperties['fields'];

	$sql = "ALTER TABLE " . $table .
			" ADD UNIQUE INDEX " . $index_name . " (" . $fields . ")";
	$q = $modx->prepare($sql);
	$q->execute();
	return '';
};

