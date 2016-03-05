<?php
/**
 * ПЛАГИН
 * Сохранить историю изменения размера бонуса по заданной акции
 * Плагин активируется при событии сохранения промо-акции в админке
 * История хранится в виде дочерних ресурсов к ресурсу промо-акции
 */

switch ($modx->event->name) {
	case 'OnDocFormSave':
		$modx->log(xPDO::LOG_LEVEL_ERROR, "PLUGIN parent = " . $resource->get('parent'));
		if ($resource->get('parent') == 28) { // если родитель "Промо-акции"
			$pa_id = $resource->get('id');
			$pa_bonus_tv_value = $modx->runSnippet('pdoField', array('id'=>$pa_id,
				'field'=>'bonus_size'));


//			return "PLUGIN pa_id = " . $pa_id;
//			return "PLUGIN bonus_soze = " . $pa_bonus_tv_value;
//
			// если история уже была создана, то обновляем

			// если истории не было (напр. при создании новой промо-акции), то добавляем
			$history = $modx->newObject('modResource');
			$history->set('pagetitle', $pa_bonus_tv_value); // Простой заголовок
			$history->set('alias', 'history_' . $pa_id . time()); // Заботимся об уникальности алиаса
			$history->set('parent', $pa_id); // Под какого родителя поместить
			// в поле редактирования запишем текущую дату,
			// т.к. это поле будет участвовать в дальнейшем при получении/обновлении цены
			$history->set('publishedon', time());
			$history->set('published', 1);
			$history->set('hidemenu', 1);
			$history->save();

		}
		break;
	default:;
}