<?php
// Получаем последний (максимальный) промокод по заданной акции
function getStartNumber($pa_resid, $pa_code)	{
	global $modx;
	$c = $modx->newQuery('modResource');
	$c->select('pagetitle');
	$c->where(array('parent' => $pa_resid));
	$c->sortby('pagetitle', 'DESC');
	$c->limit(1);

	if ($c->prepare() && $c->stmt->execute()) {
		$snum = $c->stmt->fetchAll(PDO::FETCH_COLUMN);
	}
	else{
		echo "Error";
	}
	if(is_array($snum) && sizeof($snum) > 0)	{
		$start_pcode = (int) $snum[0] + 1;
	}
	else{
		$start_pcode = $pa_code . '0001';
	}
	return (int) $start_pcode;
}

// Добавляем ресурс (промокод) как дочерний ресурс к данной акции
function addResource($fullcode, $pa_resid, $template)	{
	global $modx;
	// Создаем ресурс
	$newRes = $modx->newObject('modResource');

	// Заполняем нужные значения
	$newRes->set('pagetitle',$fullcode);
	$newRes->set('template',$template);
	$newRes->set('published',1);
	$newRes->set('hidemenu',1);
	$newRes->set('parent',$pa_resid);
	$newRes->set('content_type',7); // set JSON as content type
	$newRes->save();

	if (!$newRes->isMember('Blogger_resources_group')) {
		$newRes->joinGroup('Blogger_resources_group');
	}
	if (!$newRes->isMember('Menegers_resource_group')) {
		$newRes->joinGroup('Menegers_resource_group');
	}


}


$pa_resid =  $scriptProperties['pa_resid']; // id ресурса промо-акции
$tpl_id = 8; // шаблон Bootstrap.inner.pcodes
// Получаем уникальный код акции, хранимый в TV
$pa_code = $modx->runSnippet('pdoField', array('id'=>$pa_resid, 'field'=>'pa-code'));

$new_start_pcode = getStartNumber($pa_resid, $pa_code);
$count = 500;
while ($count > 0)	{
	$new_start_pcode_str = str_pad($new_start_pcode, 6, '0', STR_PAD_LEFT);
	addResource($new_start_pcode_str, $pa_resid, $tpl_id);
	$count--;
	echo "<br> Код сгенерирован: " . $new_start_pcode_str;
	$new_start_pcode++;
}

//return 'Промо коды сгенерированы: ' . $count;