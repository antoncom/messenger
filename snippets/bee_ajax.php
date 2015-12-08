<?php
/**
 * Run any snippet via ajax
 */

if(!empty($_POST['bee_ajax_snippet']))	{
	$params = array();
	foreach($_POST as $key => $value)	{
		if(strpos($key, 'bee_ajax_') === FALSE) continue;
		else	{
			$paramName = str_replace('bee_ajax_', '', $key);
			$params[$paramName] = $value;
		}
	}
	$modx->log(xPDO::LOG_LEVEL_ERROR, 'bee_ajax: ' . print_r($params, true));

//	$params['as_mode'] = 'onclick';
//	$params['as_target'] = 'pa_join_status_' . $params['pa_id'];

	switch($_POST['bee_ajax_snippet'])	{
		case('pa_join_status'):
			if (empty($params['bonus_method'])) {
				return $AjaxForm->error('Ошибка', array('name' => 'Необходимо выбрать способ получения бонусов!'));
			}
			else {
				$r = $modx->runSnippet('blogger_join_promoaction', array(
						'pa_id' => $params['pa_id'],
						'bonus_method' => $params['bonus_method']
				));
				$bmStr = ($params['bonus_method'] == 'phone') ? 'на баланс телефона.' : 'на карту Билайн.';
				return $AjaxForm->success($r . ' - Способ получения бонусов: ' . $bonus_method);
			}
			break;

		case('extract_promocode'):
			$extract_method = $params['extract_promocode_to'];
			if(!empty($extract_method))	{
				if($extract_method === 'clipboard')	{

				}
				if($extract_method === 'phone')	{

				}
				if($extract_method === 'email')	{

				}
				return $AjaxForm->success('Промо-код скопирован в буфер обмена.');
			}
			else{
				return $AjaxForm->error('Ошибка', array('name' => 'Необходимо выбрать способ извлечения промо-кода'));
			}
			break;

		case('send_phone_confirmation'):
			$r = json_decode($modx->runSnippet('send_phone_confirmation', array(
					'phone' => $params['blogger_phone'],
					'key' => $params['key'])), true);
			if(empty($r)) return $AjaxForm->error('Некорректный телефон: ' . $params['blogger_phone'], array('name' => $err));

			require_once(MODX_CORE_PATH.'components/beecore/include.xmlcdata.php' );
			$sms_result = xmlstr_to_array($r['result']);

			if(count($sms_result['errors']) == 0)	{
				return $AjaxForm->success('На номер ' . $r['phone'] . ' отправлен проверочный код.<br>' . $r['result']);
			}
			else{
				$err = implode("<br> ", array_values($sms_result['errors']));
				return $AjaxForm->error('Ошибка: ' . $err, array('name' => $err));
			}
			break;

		case('confirm_phone'):
			$r = json_decode($modx->runSnippet('confirm_phone', array(
					'phone' => $params['blogger_phone'],
					'confirm_code' => $params['blogger_confirmcode'],
					'key' => $params['key'])), true);

			if($r == '1')	{
				return $AjaxForm->success('Ваш телефон ' . $params['blogger_phone'] . ' подтвержден.');
			}
			else{
				return $AjaxForm->error('Ошибка. Телефон не подтвержден.', array('name' => 'Телефон не подтвержден.'));
			}
			break;

		case('card_update'):
			$r = json_decode($modx->runSnippet('card_update', array(
					'number' => $params['number'],
					'name' => $params['name'],
					'expiry' => $params['expiry'])), true);
			if($r == '1')	{
				return $AjaxForm->success('Ваша карта подтверждена');
			}
			else{
				return $AjaxForm->error('Ошибка. Карта не подтверждена.', array('name' => 'Карта не подтверждена.'));
			}


		default:;
	}





	//$modx->runSnippet('AjaxSnippet', $bee_post);



//	switch($snippet)	{
//		case('pdoResources'):	{
//			$modx->runSnippet('AjaxSnippet', array(
//				'snippet' => $snippet,
//				'parents' => $_POST('bee_ajax_parents'),
//				'tpl' => '@INLINE <p>[[+idx]]. <a href="[[+link]]">[[+pagetitle]]</a></p>'));
//			break;
//		}
//		default: {}
//	}

}