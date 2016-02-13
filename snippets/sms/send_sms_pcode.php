<?php
/**
 * Отправляем промо-код блогеру по функции "Извлечение промо-кода, прислать в SMS"
 */

include MODX_CORE_PATH.'components/beecore/beesms_class.php';
$sms= new BEESMS('6244','Beeline1');

if(!empty($pcode) && !empty($pa_id))	{

	// Телефон блогера
	$phone = $modx->runSnippet('get_blogger_phone', array('code'=>'+7'));
	$sender = '7152';

	// Название акции
	$pa_name = $modx->runSnippet('pdoField', array('field' => 'pagetitle', 'id' => $pa_id));

	$smstext = $pcode . ' - промо-код для акции "' . $pa_name . '"."';


	$result=$sms->post_message($smstext, $phone, $sender);

	$out = array();
	$out['phone'] = $phone;
	$out['result'] = $result;
	$out['pcode'] = $pcode;
	return json_encode($out, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOTE);
}
else{
	return '';
}