<?php
/**
 * Подключить блоггера к промо-акции, т.е.:
 * записать в доп.поля пользователя следующий JSON
 * {"promo_actions":["29","30"]}
 */
if(!empty($scriptProperties['pa_id']))	{
	$pa_id = $scriptProperties['pa_id'];

	$user = $modx->user;
	$profile = $user->getOne('Profile');
	if ($profile) {
		$extended = $profile->get('extended');
		if($extended['promo_actions'] == NULL)	{
			$extended['promo_actions'] =  array($pa_id);
		}
		else	{
			if(array_search($pa_id, $extended['promo_actions']) !== FALSE)	return;
			array_push($extended['promo_actions'], $pa_id);
		}
		$profile->set('extended', $extended);
		$profile->save();
	} else {
		$modx->log(modX::LOG_LEVEL_ERROR,
			'blogger_join_promoaction[SNIPPET] Could not find profile for user: ' .
			$usr->get('username'));
	}
}