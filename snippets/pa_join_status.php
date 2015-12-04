<?php
/**
 * Check joining status of blogger for a promoaction
 */

if(!empty($scriptProperties['pa_id']))	{
	$pa_id = $scriptProperties['pa_id'];
	$out_no = '<a data-toggle="modal" data-target="#accepting_payment" data-whatever="' . $pa_id . '" style="cursor: pointer;">Подключить акцию</a>';

	$user = $modx->user;
	$profile = $user->getOne('Profile');
	if ($profile) {
		$extended = $profile->get('extended');
		if ($extended['promo_actions'] == NULL) {
			return $out_no;
		} else {
			if (array_search($pa_id, $extended['promo_actions']) !== FALSE) {
				// получаем число активаций блоггера по данной акции
				$pa_activations_count = $modx->runSnippet('pa_activations_count', array(
						'pa_id' => $pa_id,
						'blogger_id' => $modx->user->get('id')
				));

				if(!empty($scriptProperties['show_pcode']) && $scriptProperties['show_pcode'] === '1')	{
					// Для подключенного блогера получаем состояние промо-кода
					$q = $modx->newQuery('modResource');
					$q->select('pagetitle, modTemplateVarResourceEndDate.value AS end_date');
					$q->leftJoin('modTemplateVarResource', 'modTemplateVarResourceBlg', array('modTemplateVarResourceBlg.tmplvarid = 5',
							'modTemplateVarResourceBlg.contentid = modResource.id'));
					$q->leftJoin('modTemplateVarResource', 'modTemplateVarResourcePa', array('modTemplateVarResourcePa.tmplvarid = 15',
							'modTemplateVarResourcePa.contentid = modResource.id'));
					$q->leftJoin('modTemplateVarResource', 'modTemplateVarResourceEndDate', array('modTemplateVarResourceEndDate.tmplvarid = 4',
							'modTemplateVarResourceEndDate.value > NOW()'));

					$q->where(array('parent' => 5135,
							'modTemplateVarResourceBlg.value' => $user->get('id'),
							'modTemplateVarResourcePa.value' => $pa_id));
					$q->limit(1);
					$q->prepare();

					$q->stmt->execute();
					$res = $q->stmt->fetchAll(PDO::FETCH_ASSOC);

					$modx->log(xPDO::LOG_LEVEL_ERROR, 'PA_ID = ' . $pa_id);

					if(count($res) > 0)	{
						$out_yes = 'Промо-код: <span class="badge">' . $res[0]['pagetitle'] . '</span> сгорает ' . date('d.m.Y', strtotime($res[0]['end_date']))  .  '.';
						$out_yes .= '<br>Всего активаций: <span class="badge">' . $pa_activations_count . '</span>';
					}
					else{
						$out_yes = 'Вы участвуете, но не получили промо-код.';
					}

					return $out_yes;
				}
				else	{
					$out_yes = 'Вы участвуете. Всего активаций: <span class="badge">' . $pa_activations_count . '</span>';
					return $out_yes;
				}

			}
			else return $out_no;
		}
	}
}