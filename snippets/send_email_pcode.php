<?php
/**
 * Sending promo code via Email
 */
$output = 'Промо-код не отправлен в связи с ошибкой функции Mail.';
$result = array();
$result['status'] = 'error';

if(!empty($pa_id) && !empty($pcode))	{
	$profile = $modx->user->getOne('Profile');

	$params = array();
	$params['to_email'] = $profile ? $profile->get('email') : '';
	$params['blogger_name'] = $profile ? $profile->get('fullname') : '';
	$params['pa_name'] = $modx->runSnippet('pdoField', array('field' => 'pagetitle', 'id' => $pa_id));
	$params['message'] = $modx->getChunk('tpl.pcode_to_email', array('pcode' => $pcode, 'pa_name' => $params['pa_name'], 'site_name' => $modx->getOption('site_name')  ));
	$params['from_email'] = $modx->getOption('emailsender');
	$params['from_name'] = $modx->getOption('site_name');
	$params['subject'] = 'Извлечение промо-кода';

	$modx->getService('mail', 'mail.modPHPMailer');
	$modx->mail->set(modMail::MAIL_BODY,$params['message']);
	$modx->mail->set(modMail::MAIL_FROM,$params['from_email']);
	$modx->mail->set(modMail::MAIL_FROM_NAME, $params['from_name']);
	$modx->mail->set(modMail::MAIL_SUBJECT, $params['subject']);
	$modx->mail->address('to',$params['to_email']);
	$modx->mail->address('reply-to',$params['from_email']);
	$modx->mail->setHTML(true);

	$sent = $modx->mail->send();
	if ($sent) {
		$output = 'Промо-код ' . $pcode . ' отправлен на ' . $params['to_email'] . '.';
		$result['status'] = 'ok';
	}
	else	{
			$modx->log(modX::LOG_LEVEL_ERROR,'An error occurred while trying to send the email: '.$modx->mail->mailer->ErrorInfo);
	}
	$modx->mail->reset();
}
$result['message'] = $output;
return json_encode($result);



