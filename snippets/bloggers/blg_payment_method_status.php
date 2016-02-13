<?php
/**
 * Check payment method status of blogger for a promoaction
 */

if(!empty($scriptProperties['pa_id']))	{
	$pa_id = $scriptProperties['pa_id'];
	$out = '<span data-target="#payment_method" data-whatever="'.$pa_id.'">Акция не подключена.</span>';

	$user = $modx->user;
	$profile = $user->getOne('Profile');
	if ($profile) {
		$extended = $profile->get('extended');
		// Если в профиле пользователя есть записи о подключенных акциях
		if ($extended['promo_actions'] != NULL) {
			// Если среди подключенных акций есть идентификатор данной акции
			if (array_search($pa_id, $extended['promo_actions']) !== FALSE) {
				$bonus_method = $extended['bonus_method'][$pa_id];
				if($bonus_method == 'phone')	{
					$out = 'На баланс телефона: ' . $modx->runSnippet('get_blogger_phone', array('code' => '+7'));
				}
				elseif($bonus_method == 'card')	{
					$out = 'На карту: ' . $modx->runSnippet('get_blogger_card', array('mask_num' => 1));
				}
			}
		}
	}
	return $out;
}