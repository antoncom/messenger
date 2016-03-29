<?php
/**
 * Run any snippet via ajax
 */

if(!empty($_POST['bee_ajax_snippet']))	{
	$params = array();
	require_once(MODX_CORE_PATH.'components/beecore/include.xmlcdata.php' );

	foreach($_POST as $key => $value)	{
		if(strpos($key, 'bee_ajax_') === FALSE) continue;
		else	{
			$paramName = str_replace('bee_ajax_', '', $key);
			$params[$paramName] = $value;
		}
	}
	$modx->log(xPDO::LOG_LEVEL_ERROR, 'bee_ajax Params: ' . print_r($params, true));

	// Если передан id блогера значит профиль сохраняется менеджером
	if(!empty($_POST['bee_ajax_blgid']))	{
		$user_id = $_POST['bee_ajax_blgid'];
	}
	else	{
		$user = $modx->getUser();
		$user_id = $user->get('id');
	}

	switch($_POST['bee_ajax_snippet'])	{
		// комментируем данный блок, поскольку в Билайне принято решение, что бонусы будут приходить только на телефон (на карту не будут)
		/*case('pa_join_status'):
			if (empty($params['bonus_method'])) {
				return $AjaxForm->error('Ошибка', array('name' => 'Необходимо выбрать способ получения бонусов!'));
			}
			else {
				$r = $modx->runSnippet('blogger_join_promoaction', array(
						'pa_id' => $params['pa_id'],
						'bonus_method' => $params['bonus_method']
				));
				$bmStr = ($params['bonus_method'] == 'phone') ? 'на баланс телефона.' : 'на карту Билайн.';
				return $AjaxForm->success($r . ' - Способ получения бонусов: ' . $bmStr);
			}
			break;*/

		// Подключение к акции - вывод денег на телефон всегда
		case('pa_join_with_phone'):
			$r = json_decode($modx->runSnippet('set_mobilephone', array(
				'mobilephone_confirmed' => $params['mobilephone_confirmed'],
				'mobilephone_notempty' => $params['mobilephone_notempty'])), true);
			if($r['result'] == 'ok')	{
				$r = $modx->runSnippet('blogger_join_promoaction', array(
					'pa_id' => $params['pa_id'],
					'bonus_method' => 'phone'
				));
				if($r['result'] == 'ok') {
					return $AjaxForm->success($r . ' - Способ получения бонусов: на баланс телефона.');
				}
				else{
					return $AjaxForm->error('Ошибка подключения к акции 915409', $r['errors']);
				}
			}
			elseif($r['result'] == 'error')	{
				return $AjaxForm->error('Ошибка. Телефон не сохранен.', $r['errors']);
			}
			else{
				return $AjaxForm->error('Ошибка 915405. Телефон не сохранен.');
			}
			break;

		case('deliver_promocode'):
			$extract_method = $params['extract_promocode_to'];

			$modx->log(xPDO::LOG_LEVEL_ERROR, 'deliver_promocode extract_method  = ' . $extract_method );

			$pcode = $params['promo_code'];
			if(empty($extract_method)) {
				return $AjaxForm->error('Необходимо выбрать способ извлечения промо-кода', array('name' => 'Необходимо выбрать способ извлечения промо-кода'));
			}
			else{
				if(empty($pcode) || $pcode === 'Нет свободных промо-кодов!')	{
					return $AjaxForm->error('Нет промо-кода для извлеченя.', array('name' => 'Нет промо-кода для извлеченя.'));
				}
				else{
					if($extract_method === 'clipboard')	{
						return $AjaxForm->success('Промо-код ' . $pcode . ' скопирован в буфер обмена.');
					}
					if($extract_method === 'phone')	{
						$send_result = json_decode($modx->runSnippet('send_sms_pcode', array(
							'pcode' => $params['promo_code'],
							'pa_id' => $params['pa_id'])), true);

						if(!empty($send_result))	{
							$smsgate_response = xmlstr_to_array($send_result['result']);
							if(count($smsgate_response['errors']) == 0)	{
								return $AjaxForm->success('На номер ' . $smsgate_response['sms']['@attributes']['phone'] . ' отправлен промо-код ' . $send_result['pcode']);
							}
							else{
								$err = implode("<br> ", array_values($smsgate_response['errors']));
								return $AjaxForm->error('Ошибка отправки промо-кода по SMS.' . $err, array('name' => $err));
							}
						}
						else{
							return $AjaxForm->error('Ошибка отправки промо-кода по SMS.', 1);
						}
					}
					if($extract_method === 'email')	{
						$send_result = json_decode($modx->runSnippet('send_email_pcode', array('pa_id' => $params['pa_id'], 'pcode' => $params['promo_code'])), true);
						if($send_result['status'] == 'ok')	{
							return $AjaxForm->success($send_result['message']);
						}
						elseif($send_result['status'] == 'error')	{
							return $AjaxForm->error($send_result['message']);
						}
						else{
							return $AjaxForm->error('Статус отправки Емайл неопределен.');
						}

					}
				}
			}
			break;

		case('extract_promocode'):

			// Подключаем к акции, если блоггер впервые извлекает промокод
			$r = $modx->runSnippet('blogger_join_promoaction', array(
				'pa_id' => $params['pa_id'],
				'bonus_method' => 'phone'
			));
			return $AjaxForm->success($r . ' - Способ получения бонусов: на баланс телефона.');

			$extract_method = $params['extract_promocode_to'];

$modx->log(xPDO::LOG_LEVEL_ERROR, 'extract_method  = ' . $extract_method );

			$pcode = $params['promo_code'];
			if(empty($extract_method)) {
				return $AjaxForm->error('Необходимо выбрать способ извлечения промо-кода', array('name' => 'Необходимо выбрать способ извлечения промо-кода'));
			}
			else{
				if(empty($pcode) || $pcode === 'Нет свободных промо-кодов!')	{
					return $AjaxForm->error('Нет промо-кода для извлеченя.', array('name' => 'Нет промо-кода для извлеченя.'));
				}
				else{
					if($extract_method === 'clipboard')	{
						return $AjaxForm->success('Промо-код ' . $pcode . ' скопирован в буфер обмена.');
					}
					if($extract_method === 'phone')	{
						$send_result = json_decode($modx->runSnippet('send_sms_pcode', array(
								'pcode' => $params['promo_code'],
								'pa_id' => $params['pa_id'])), true);

						if(!empty($send_result))	{
							$smsgate_response = xmlstr_to_array($send_result['result']);
							if(count($smsgate_response['errors']) == 0)	{
								return $AjaxForm->success('На номер ' . $smsgate_response['sms']['@attributes']['phone'] . ' отправлен промо-код ' . $send_result['pcode']);
							}
							else{
								$err = implode("<br> ", array_values($smsgate_response['errors']));
								return $AjaxForm->error('Ошибка отправки промо-кода по SMS.' . $err, array('name' => $err));
							}
						}
						else{
							return $AjaxForm->error('Ошибка отправки промо-кода по SMS.', 1);
						}
					}
					if($extract_method === 'email')	{
						$send_result = json_decode($modx->runSnippet('send_email_pcode', array('pa_id' => $params['pa_id'], 'pcode' => $params['promo_code'])), true);
						if($send_result['status'] == 'ok')	{
							return $AjaxForm->success($send_result['message']);
						}
						elseif($send_result['status'] == 'error')	{
							return $AjaxForm->error($send_result['message']);
						}
						else{
							return $AjaxForm->error('Статус отправки Емайл неопределен.');
						}

					}
				}
			}
			break;

		case('send_phone_confirmation'):
			$r = json_decode($modx->runSnippet('send_phone_confirmation', array(
					'phone' => $params['blogger_phone'],
					'key' => $params['key'])), true);
			if(empty($r)) return $AjaxForm->error('Некорректный телефон: ' . $params['blogger_phone'], array('name' => $err));

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
					'expiry' => $params['expiry'],
					'user_id' => $user_id)), true);
			if($r == '1')	{
				return $AjaxForm->success('Ваша карта подтверждена');
			}
			else{
				return $AjaxForm->error('Ошибка. Карта не подтверждена.', array('name' => 'Карта не подтверждена.'));
			}

		case('update_profile'):
			$r = json_decode($modx->runSnippet('update_profile', array(
					'username' => $params['username'],
					'email' => $params['email'],
					'gender' => $params['gender'],
					'dob' => $params['dob'],
					'fullname' => $params['fullname'],
					'password' => $params['password'],
					'mobilephone_confirmed' => $params['mobilephone_confirmed'],
					'mobilephone_notempty' => $params['mobilephone_notempty'],
					'user_id' => $user_id)), true);
			if($r['result'] == 'ok')	{
				return $AjaxForm->success('Ваш профиль обновлен.');
			}
			elseif($r['result'] == 'error')	{
				return $AjaxForm->error('Ошибка. Профиль не обновлен.', $r['errors']);
			}
			else{
				return $AjaxForm->error('Ошибка 915404. Профиль не обновлен.');
			}
			break;

		case('update_taxing_profile'):
			$r = json_decode($modx->runSnippet('update_taxing_profile', array(
					'firstname' => $params['firstname'],
					'middlename' => $params['middlename'],
					'lastname' => $params['lastname'],
					'passport' => $params['passport'],
					'address' => $params['address'],
					'inn' => $params['inn'],
					'user_id' => $user_id)), true);
			if($r['result'] == 'ok')	{
				return $AjaxForm->success('Ваш налоговый профиль обновлен.');
			}
			elseif($r['result'] == 'error')	{
				return $AjaxForm->error('Ошибка. Профиль не обновлен.', $r['errors']);
			}
			else{
				return $AjaxForm->error('Ошибка 915404. Профиль не обновлен.');
			}
			break;

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