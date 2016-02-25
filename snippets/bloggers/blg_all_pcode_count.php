<?php
/**
 * Посчитать число всех промо-кодов блогера
 */

$userId = $modx->getOption('blgid',$scriptProperties,false);
if (empty($userId)) {
	$user = $modx->getUser();
	$userId = $user->get('id');
}

$user = $modx->getObject('modUser',$userId);
if (!$user) return '';

return $modx->runSnippet('pdoResources', array(
	'parents' => 5135, // ресурс "Мои промокоды" (родитель для всех промо-кодов в системе)
	'limit' => 1,
	'includeTVs' => 'blogger_id',
	'tvFilters'=> 'blogger_id==' . $userId,
	'processTVs' => 'blogger_id',
	'tpl' => '@INLINE [[+total]]'
));

