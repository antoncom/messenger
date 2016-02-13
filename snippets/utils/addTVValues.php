<?php
/**
 * addTVValues
 * Добавить значения TV-полей для многих ресурсов одним запросом
 */

if(!$abonents = $scriptProperties['abonents'] &&
	!$act_dates = $scriptProperties['act_dates'] &&
	!$bloggers = $scriptProperties['bloggers'] &&
	!$promo_actions = $scriptProperties['promo_actions'] &&
	!$pcodes = $scriptProperties['pcodes']) {
	return;
}


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


//$modx->log(xPDO::LOG_LEVEL_ERROR, "TVVALUES = " . print_r($tvValues, true));


$protoTV = array();
// Формируем SQL запрос на добавление ресурсов
foreach($tvValues as $res => $tvs)	{
	foreach($tvs as $tv => $value)	{
		$protoTV['contentid'] = $res;
		$protoTV['tmplvarid'] = $tv;
		$protoTV['value'] = $value;
		$values = join(',', array_values($protoTV));
		$rows .= "(". $values ."),";
	}
}
$rows = rtrim($rows, ",");
$fields = join(',', array_keys($protoGroup));
$sql = "INSERT INTO {$modx->getTableName('modTemplateVarResource')} (" . $fields . ") VALUES " . $rows;
$q = $modx->prepare($sql);

$modx->log(xPDO::LOG_LEVEL_ERROR, "modTemplateVarResource SQL = " . $sql);
//if(!$q->execute(array(0)))	{
//	$modx->log(xPDO::LOG_LEVEL_ERROR, 'startNum.pcode snippet ERROR' . print_r($q->errorInfo(),true));
//	return;
//}
