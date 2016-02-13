<?php
/**
 * Сохранить историю изменения размера бонуса по заданной акции
 * Плагин активируется при событии сохранения промо-акции в админке
 * История хранится в дочернем ресурсе в виде json
 */
/*
switch ($modx->event->name) {
	case 'OnDocFormSave':
		$pa_id = $modx->resource->get('id');
		$pa_alias = $modx->resource->get('alias');
		$pa_bonus_tv_value = $modx->runSnippet('pdoField', array('id'=>$pa_id,
																	'field'=>'bonus_size'));
		// если история уже была создана, то обновляем

		// если истории не было (напр. при создании новой промо-акции), то добавляем
		$history = $modx->newObject('modResource');
		$history->set('pagetitle', 'TestPage'); // Простой заголовок
		$history->set('alias', 'history_' . $pa_alias); // Заботимся об уникальности алиаса
		$history->set('parent', $pa_id); // Под какого родителя поместить
		$history->set('parent', $pa_id); // Под какого родителя поместить
		$history->setContent('This will be the content of the new resource.');
		$history->save();
		break;
	default:;
}*/