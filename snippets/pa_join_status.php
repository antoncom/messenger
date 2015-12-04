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

				$out_yes = 'Вы участвуете. Всего активаций: <span class="badge">' . $pa_activations_count . '</span>';
				return $out_yes;
			}
			else return $out_no;
		}
	}
}