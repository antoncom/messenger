<?php
/**
 * addtoResourceGroup
 * Добавить ресурсы в групу ресурсов
 * На входе json pagetitle-ов ресурса, json IDs групп, в которые добавить
 * На выходе true/error
 */

if(!$scriptProperties['resources'] && !$scriptProperties['groups']) return;

$resources = json_decode($scriptProperties['resources']);
$groups = json_decode($scriptProperties['groups']);

if($scriptProperties['parents'])	{
	$parents = json_decode($scriptProperties['parents']);
	$search = array(
		'pagetitle:IN' => $resources,
		'parent:IN' => $parents
	);
}
else{
	$search = array(
		'pagetitle:IN' => $resources
	);
}

//$modx->log(xPDO::LOG_LEVEL_ERROR, "RES = " . print_r($resources, true));
//$modx->log(xPDO::LOG_LEVEL_ERROR, "GRO = " . print_r($groups, true));
//$modx->log(xPDO::LOG_LEVEL_ERROR, "PAR = " . print_r($parents, true));

// Вначале находим ID-шники только что созданных ресурсов
$q = $modx->newQuery('modResource', $search);
$q->select('id');
$q->prepare();
$sql = $q->toSQL();
$modx->log(xPDO::LOG_LEVEL_ERROR, 'addtoResourceGroup SQL = ' . $sql);
if(!$q->stmt->execute())	{
	$modx->log(xPDO::LOG_LEVEL_ERROR, 'addtoResourceGroup snippet ERROR' . print_r($q->errorInfo(),true));
	return;
}
$resIds = $q->stmt->fetchAll(PDO::FETCH_COLUMN);

$modx->log(xPDO::LOG_LEVEL_ERROR, 'addtoResourceGroup RES = ' . print_r($resIds, true));
// Помещаем ID-шники вновь созданных ресурсов промо кодов в таблицу групп ресурсов
$protoGroup = array(
	'document_group'=>'',
	'document'=>''
);
$fields = "";
$values = "";
$rows = "";

// Формируем SQL запрос на добавление ресурсов
foreach($groups as $ind1 => $group)	{
	foreach($resIds as $ind2 => $id)	{
		$protoGroup['document_group'] = $group;
		$protoGroup['document'] = $id;
		$values = join(',', array_values($protoGroup));
		$rows .= "(". $values ."),";
	}
}
$rows = rtrim($rows, ",");
$fields = join(',', array_keys($protoGroup));
$sql = "INSERT INTO {$modx->getTableName('modResourceGroupResource')} (" . $fields . ") VALUES " . $rows;
$q = $modx->prepare($sql);

$modx->log(xPDO::LOG_LEVEL_ERROR, "SQL = " . $sql);
if(!$q->execute(array(0)))	{
	$modx->log(xPDO::LOG_LEVEL_ERROR, 'startNum.pcode snippet ERROR' . print_r($q->errorInfo(),true));
	return;
}