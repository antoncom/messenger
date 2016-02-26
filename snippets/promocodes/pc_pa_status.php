<?php
/**
 * Вывод промо-кода в списке акций в кабинете блогера
 * На входе pa_id
 * На выходе: промокод в виде ссылки
 */

if(!empty($scriptProperties['pa_id'])) {
	$pa_id = $scriptProperties['pa_id'];
	// Проверяем участвует ли блогер в акции и меет ли промокод
	$user = $modx->user;
	$profile = $user->getOne('Profile');
	if ($profile) {
		$extended = $profile->get('extended');
		if ($extended['promo_actions'] !== NULL && array_search($pa_id, $extended['promo_actions']) !== FALSE) {
//			if (array_search($pa_id, $extended['promo_actions']) !== FALSE) {
			// Для подключенного блогера получаем состояние промо-кода
			$q = $modx->newQuery('modResource');
			$q->select('modResource.id, pagetitle, modTemplateVarResourceEndDate.value AS end_date');
			$q->leftJoin('modTemplateVarResource', 'modTemplateVarResourceBlg', array('modTemplateVarResourceBlg.tmplvarid = 5',
				'modTemplateVarResourceBlg.contentid = modResource.id'));
			$q->leftJoin('modTemplateVarResource', 'modTemplateVarResourcePa', array('modTemplateVarResourcePa.tmplvarid = 15',
				'modTemplateVarResourcePa.contentid = modResource.id'));
			$q->leftJoin('modTemplateVarResource', 'modTemplateVarResourceEndDate', array('modTemplateVarResourceEndDate.tmplvarid = 4',
				'modTemplateVarResourceEndDate.contentid = modResource.id'));

			$q->where(array('parent' => 5135,
				'modTemplateVarResourceBlg.value' => $user->get('id'),
				'modTemplateVarResourcePa.value' => $pa_id));
			$q->sortby('modTemplateVarResourceEndDate.value', 'DESC');
			$q->limit(1);
			$q->prepare();

			$q->stmt->execute();
			$res = $q->stmt->fetchAll(PDO::FETCH_ASSOC);

			// Получить bonus_sum из ДБ modx_promocodes
			$bonus_sum = 88888;


			// Если промо-код есть
			if (count($res) > 0) {
				// получить число активаций промо-кода
				$pc_activations_count = $modx->runSnippet('pc_activations_count', array(
						'pc_id' => $res[0]['id']
				));

				$stat = $modx->getChunk('blg_pcode_short_stat', array(
					'expired' => date('d.m.Y', strtotime($res[0]['end_date'])),
					'count' => $pc_activations_count,
					'income' => $bonus_sum
				));
				// Если промокод действующий
				if (strtotime($res[0]['end_date']) > time()) {
					$doings = $modx->getChunk('blg_pcode_short_stat_doings', array(
						'pcode_page' => $res[0]['pagetitle'] . '.html',
						'extract_new_link'=>''
					));

					$colored = 'active';
				}
				// Если промо-код истек
				else {
					$doings = $modx->getChunk('blg_pcode_short_stat_doings', array(
						'pcode_page' => $res[0]['pagetitle'] . '.html',
						'extract_new_link'=>"<li><a data-target='#extract_promocode' data-toggle='modal' data-whatever='".$pa_id."' style='cursor: pointer;'>Извлечь промокод</a></li>"
					));
					$colored = 'expired';
				}
				$output = '<a data-pa_id="'.$pa_id.'" href="javascript:;" class="pc_status '.$colored.'" data-html="true" data-toggle="popover" data-placement="auto left" data-animation="false" title="Промо-код активен" data-content="'.$stat . $doings .'">'.$res[0]['pagetitle'].'</a>';
			}
			else{
				// если ни одного промо-кода еще не было извлечено блогером
				$doings = $modx->getChunk('blg_pa_extract_firstly_popover', array(
						'bonus_method' => 'на баланс мобильного телефона.',
						'extract_new_link'=>"<li><a data-target='#extract_promocode' data-toggle='modal' data-whatever='".$pa_id."' style='cursor: pointer;'>Извлечь промокод</a></li>"
				));
				$colored = 'expired';
				$output = '<a data-pa_id="'.$pa_id.'" href="javascript:;" class="pc_status '.$colored.'" data-html="true" data-toggle="popover" data-placement="auto left" data-animation="false" title="Акция подключена" data-content="' . $doings .'">Извлечь</a>';
			}
		}
		else{
			// Подключение к акции
			$join_msg = $modx->getChunk('blg_pa_join_popover', array(
					'pa_id' => $pa_id,
					'extract_new_link'=>"<li><a data-pa_id='[[+pa_id]]' data-toggle='modal' data-whatever='[[+pa_id]]' style='cursor: pointer;' class='disactive'>Извлечь промокод</a></li>"
			));
//				$output = "<a href='javascript:;' class='pc_status notincluded' data-html='true' data-toggle='popover' data-placement='left' data-animation='false' title='Участие в промо-акции' data-content='".$join_msg."'></a>";
			$output = '<a data-pa_id="'.$pa_id.'" href="javascript:;" class="pc_status notincluded" data-html="true" data-toggle="popover" data-placement="auto left" data-animation="false" title="Участие в промо-акции" data-content="'.$join_msg.'">&nbsp;</a>';
		}
		return $output;
	}
}


