<?php
/**
 * Получить историческую цену бонуса данной акции на основании даты активации промо-кода
 * Цена берется из дочерних ресурсов промо-акции, представляющих собой историю изменения цены бонуса
 */
if(!empty($scriptProperties['pa_id']))	{

	$sql = "SELECT pagetitle FROM `"
		. $modx->getOption('table_prefix') . "site_content` "
		. "WHERE `parent` = " . $scriptProperties['pa_id']
		. " AND publishedon < " .$scriptProperties['activation_date']
		. " ORDER BY `publishedon` DESC LIMIT 1";
	$q = $modx->prepare($sql);
	$q->execute();
	$res = $q->fetchAll(PDO::FETCH_COLUMN);

	if(!empty($res))	{
		return $res[0];
	}
	else{
		// если по каким-то причинам истории цен нет
		// напр. менеджер удалил все ресурсы-истории изменения цен
		// то берем цену из TV bonus_size
		$pa_bonus_tv_value = $modx->runSnippet('pdoField', array('id'=>$pa_id,
				'field'=>'bonus_size'));

		return $pa_bonus_tv_value;	}
}