<?php
/**
 * Отправщик SMS
 */

function gen()	{
//	$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
	$chars="1234567890";
	$max=6;

	$size=strlen($chars)-1;
	$password=null;

	while($max--)
		$password.=$chars[rand(0,$size)];
	}

return($password);


if(!empty($abonent) && !empty($text))	{
	//header("Content-Type: text/xml; charset=UTF-8");
	Include('BEESMS.class.php');

	// Сохраняем код подтверждения в кэш
	$cache = $modx->cacheManager;
	$cache_key = '/sendsms/';
	$key = sha1(serialize($scriptProperties));

	$confirmation_code = gen();
	$cache->set($cache_key . $key, $confirmation_code);

	$target='+9030507175';
	$sender='Bloggers.Beeline';

	$sms= new BEESMS('6244','Beeline1');
	$result=$sms->post_message($confirmation_code, $target, $sender);

	$out = array();
	$out['session_id'] = $key;
	$out['phone'] = $result;
	return json_encode($result, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOTE);
}