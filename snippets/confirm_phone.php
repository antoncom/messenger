<?php
/**
 * Подтвердить ввод телефона через SMS
 */
$modx->log(xPDO::LOG_LEVEL_ERROR, 'SCRIPT_PROPERTIES: ' . print_r($scriptProperties,true) );
if(!empty($scriptProperties['phone']) && !empty($scriptProperties['confirm_code']) && !empty($scriptProperties['key']))	{
	$cache = $modx->cacheManager;
	$cache_key = '/confirmphone/';
	$stored_code = $cache->get($cache_key . $key);
	$phone = $scriptProperties['phone'];
	$user = $modx->user;

	$profile = $user->getOne('Profile');
	$profile->set('mobilephone');
	$profile->set('mobilephone', $phone);
	$profile->save();


	return ($scriptProperties['confirm_code'] == $stored_code) ? 1 : 0;
}
