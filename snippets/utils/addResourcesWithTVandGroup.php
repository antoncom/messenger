<?php
// Добавляем ресурсы одним запросом
if(!empty($scriptProperties['resources']))	{
	$resources = json_decode($scriptProperties['resources'], true);
	if(!is_array($resources)) return;

	foreach($resources as $res)	{
		$fields = array_keys($res);
		break;
	}
	$values = "";
	$odku = "";
	foreach($resources as $alias => $res)	{
		$values .= "(" . implode(",", array_values($res)) . "),";
	}
	$values = rtrim($values, ",");

	foreach($fields as $k)	{
		$odku .= $k . "=VALUES(" . $k . "),";
	}
	$odku = rtrim($odku, ",");


	// Добавляем уникальный индекс чтобы сработала команда ON DUPLICATE KEY UPDATE
	$modx->runSnippet('add_unique_index', array(
		'index_name'=>'ix_alias',
		'table'=>$modx->getTableName('modResource'),
		'fields'=>'alias',
	));

	// Добавляем ресурсы в БД
	$sql = "INSERT INTO {$modx->getTableName('modResource')} (" . implode(",", $fields) . ")" .
		" VALUES " . $values .
		" ON DUPLICATE KEY UPDATE " .$odku;

	$q = $modx->prepare($sql);
	if(!$q->execute(array(0)))	{
		$modx->log(xPDO::LOG_LEVEL_ERROR, 'addResourcesWithTVandGroup snippet ERROR' . print_r($q->errorInfo(),true));
		return;
	}
	$count = $q->rowCount();

	// Убираем индекс после добавлеия записей
	$modx->runSnippet('add_unique_index', array(
		'index_name'=>'ix_alias',
		'table'=>$modx->getTableName('modResource'),
		'fields'=>'alias',
	));

	// Получаем ID вновь созданных ресурсов путем поиска по алиасу
	$resIds = json_decode($modx->runSnippet('resid_by_aliases', array('aliases' => "'" . implode("','", array_keys($resources)). "'")), true);
	//$resIds = json_decode($modx->runSnippet('resid_by_aliases', array('aliases' => implode(",", array_keys($resources)))), true);
	$modx->log(XPDO::LOG_LEVEL_ERROR, "RES IDS ====== " . print_r($resIds, true));


}

// Добавляем TV одним запросом
if(!empty($scriptProperties['tvs'])) {
	$tvs = json_decode($scriptProperties['tvs'], true);
	if(!is_array($tvs)) return;
	foreach($tvs as $tv)	{
		$tvNames = array_keys($tv);
		break;
	}
	$tvIds = json_decode($modx->runSnippet('tvid_by_names', array('names' => "'".implode("','", $tvNames). "'")), true);
	$fields = array('tmplvarid', 'contentid', 'value');
	$values = "";
	$odku = "";

	foreach($tvs as $alias => $tv)	{
		foreach($tv as $name => $value)	{
			$values .= "(" . $tvIds[$name] . "," . $resIds[$alias] . "," . $value . "),";
		}
	}
	$values = rtrim($values, ",");

	foreach($fields as $k)	{
		$odku .= $k . "=VALUES(" . $k . "),";
	}
	$odku = rtrim($odku, ",");

	// Добавляем уникальный индекс чтобы сработала команда ON DUPLICATE KEY UPDATE
	$modx->runSnippet('add_unique_index', array(
		'index_name'=>'ix_tmplvar_contid',
		'table'=>$modx->getTableName('modTemplateVarResource'),
		'fields'=>'tmplvarid, contentid'
	));

	// Добавляем записиси в БД
	$sql = "INSERT INTO {$modx->getTableName('modTemplateVarResource')} (" . implode(",", $fields) . ")" .
		" VALUES " . $values .
		" ON DUPLICATE KEY UPDATE " .$odku;

	$q = $modx->prepare($sql);
	if(!$q->execute(array(0)))	{
		$modx->log(xPDO::LOG_LEVEL_ERROR, 'addResourcesWithTVandGroup snippet TV ADDING ERROR' . print_r($q->errorInfo(),true));
		$modx->log(xPDO::LOG_LEVEL_ERROR, 'TV ADDING ERROR SQL = ' . $sql);
		return;
	}

	// Удаляем индекс чтобы вернуть таблицу в исходное состояние
	$modx->runSnippet('drop_unique_index', array(
		'index_name'=>'ix_tmplvar_contid',
		'table'=>$modx->getTableName('modTemplateVarResource')
	));
}

// Добавляем Groups одним запросом
if(!empty($scriptProperties['grps'])) {
	$grps = json_decode($scriptProperties['grps'], true);
	if(!is_array($grps)) return;

	$fields = array('document', 'document_group');
	$values = "";
	$odku = "";
	foreach($grps as $alias => $gr)	{
		foreach($gr as $ind => $value)	{
			$values .= "(" . $resIds[$alias] . "," . $value . "),";
		}
	}
	$values = rtrim($values, ",");

	foreach($fields as $k)	{
		$odku .= $k . "=VALUES(" . $k . "),";
	}
	$odku = rtrim($odku, ",");

	// Добавляем уникальный индекс чтобы сработала команда ON DUPLICATE KEY UPDATE
	$modx->runSnippet('add_unique_index', array(
		'index_name'=>'ix_doc_gr',
		'table'=>$modx->getTableName('modResourceGroupResource'),
		'fields'=>'document, document_group'
	));

	// Добавляем записи в БД
	$sql = "INSERT INTO {$modx->getTableName('modResourceGroupResource')} (" . implode(",", $fields) . ")" .
		" VALUES " . $values .
		" ON DUPLICATE KEY UPDATE " .$odku;

	$q = $modx->prepare($sql);
	if(!$q->execute(array(0)))	{
		$modx->log(xPDO::LOG_LEVEL_ERROR, 'addResourcesWithTVandGroup snippet GROUPS ADDING ERROR' . print_r($q->errorInfo(),true));
		return;
	}

	// Удаляем индекс чтобы вернутьтаблицу в исходное состояние
	$modx->runSnippet('drop_unique_index', array(
		'index_name'=>'ix_doc_gr',
		'table'=>$modx->getTableName('modResourceGroupResource')
	));
}

return $count;