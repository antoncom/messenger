<?php
/**
 * Отправщик SMS
 */

include MODX_CORE_PATH.'components/beecore/beesms_class.php';
$sms= new BEESMS('6244','Beeline1');

function gen()	{
//	$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
	$chars="1234567890";
	$max=6;

	$size=strlen($chars)-1;
	$confirm_code = null;

	while($max--) $confirm_code .= $chars[rand(0,$size)];

	return($confirm_code);
}


if(!empty($phone) && !empty($key))	{

	// Сохраняем код подтверждения в кэш
	$cache = $modx->cacheManager;
	$cache_key = '/confirmphone/';

	$confirmation_code = gen();
	$cache->set($cache_key . $key, $confirmation_code, 600);

	$modx->log(xPDO::LOG_LEVEL_ERROR, 'Save_code: ' . $cache_key . $key );


	if(!in_array(trim(preg_replace('/[0-9]/', '#', $phone)), array('(###) ###-####', '##########'))) {
		return '';
	}

	$phone = str_replace(" ", "", $phone);
	$phone = str_replace("(", "", $phone);
	$phone = str_replace(")", "", $phone);
	$phone = str_replace("-", "", $phone);

	$target = '+7'.$phone;
	$sender = '7152';


	$result=$sms->post_message($confirmation_code, $target, $sender);

	$out = array();
	$out['session_id'] = $key;
	$out['phone'] = $phone;
	$out['result'] = $result;
	$modx->log(xPDO::LOG_LEVEL_ERROR, 'SMS: ' . print_r($out,true) );
	return json_encode($out, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOTE);
}