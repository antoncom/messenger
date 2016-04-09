<?php
/**
 * Check joining status of blogger for a promoaction
 */

if(!empty($scriptProperties['pa_id']))	{
	$pa_id = $scriptProperties['pa_id'];
	$out = '<a data-toggle="modal" data-target="#accepting_payment" data-whatever="' . $pa_id . '" style="cursor: pointer;">Подключить акцию</a>';

	$user = $modx->user;
	$profile = $user->getOne('Profile');
	if ($profile) {
		$extended = $profile->get('extended');
		// Если в профиле пользователя есть записи о подключенных акциях
		if ($extended['promo_actions'] != NULL) {
			// Если среди подключенных акций есть идентификатор данной акции
			if (array_search($pa_id, $extended['promo_actions']) !== FALSE) {

				$pa_activations_count = $modx->runSnippet('pa_activations_count', array(
						'pa_id' => $pa_id,
						'blogger_id' => $user->get('id')
				));
				// Если включен показ данных о промо-коде
				if(!empty($scriptProperties['show_pcode']) && $scriptProperties['show_pcode'] === '1')	{
					$pcode_stat = json_decode($modx->runSnippet('blg_active_promocode', array('pa_id' => $pa_id, 'blogger_id' => $modx->user->get('id'))),true);
					if(count($pcode_stat) > 0)	{
						$out = '<span data-target="#accepting_payment" data-whatever="'.$pa_id.'"></span>';
						$out .= 'Вы участвуете. <br />Всего активаций: <span class="badge">' . $pa_activations_count . '</span>';
						$out .='<br>Активный промо-код: ' . $pcode_stat['pcode'] . ', активаций: ' . $pcode_stat['pc_activations_count'];
					}
					else{
						$out = '<span data-target="#accepting_payment" data-whatever="'.$pa_id.'"></span>';
						$out .= 'Вы участвуете. Всего активаций: <br /><span class="badge">' . $pa_activations_count . '</span>';
						$out .='<br>Активный промо-код: нет.';
					}
				}
				else	{
					$out = '<span data-target="#accepting_payment" data-whatever="'.$pa_id.'"></span>';
					$out .= 'Вы участвуете. <br />Всего активаций: <span class="badge">' . $pa_activations_count . '</span>';
				}
			}
		}
		return $out;
	}
}