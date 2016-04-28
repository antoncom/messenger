<?php
/**
 * Подключить блоггера к промо-акции, т.е.:
 * записать в доп.поля пользователя следующий JSON
     {"promo_actions":["30","29"],"bonus_method":{"29":"phone","30":"card"}}
 */
if(!empty($scriptProperties['pa_id']) && !empty($scriptProperties['bonus_method']))	{
	$pa_id = $scriptProperties['pa_id'];
	$bonus_method = $scriptProperties['bonus_method'];

	$out = array();
	$user = $modx->user;
	$profile = $user->getOne('Profile');
	if ($profile) {
		$extended = $profile->get('extended');
		if($extended['promo_actions'] == NULL)	{
			$extended['promo_actions'] =  array($pa_id);
			$extended['bonus_method'] =  array($pa_id => $bonus_method);
		}
		else	{
			if(array_search($pa_id, $extended['promo_actions']) !== FALSE)	return;
			array_push($extended['promo_actions'], $pa_id);
			$extended['bonus_method'][$pa_id] = $bonus_method;
		}
		$profile->set('extended', $extended);
		$profile->save();
		$out['success'] = "Пользователь " . $profile->get('fullname') . " подключен к акции.";
		$out['result'] = 'ok';
		return json_encode($out);

	} else {
		$modx->log(modX::LOG_LEVEL_ERROR,
			'blogger_join_promoaction[SNIPPET] Could not find profile for user: ' .
			$user->get('username'));
	}
}