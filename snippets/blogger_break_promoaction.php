<?php
/**
 * Отключить блоггера от промо-акции, т.е.:
 * удалить из доп.поля пользователя id акции, хранимый в следующем JSON
 * {"promo_actions":["29","30"]}
 */
if(!empty($scriptProperties['pa_id']))	{
	$pa_id = $scriptProperties['pa_id'];

	$user = $modx->user;
	$profile = $user->getOne('Profile');
	if ($profile) {
		$extended = $profile->get('extended');

		if($extended['promo_actions'] == NULL)	{
			return;
		}
		else	{
			$pos = array_search($pa_id, $extended['promo_actions']);
			if($pos === FALSE)	return;
			unset($extended['promo_actions'][$pos]);
			$extended['promo_actions'] = array_values($extended['promo_actions']); // re-indexing array
		}
		$profile->set('extended', $extended);
		$profile->save();
	} else {
		$modx->log(modX::LOG_LEVEL_ERROR,
			'blogger_break_promoaction[SNIPPET] Could not find profile for user: ' .
			$usr->get('username'));
	}
}