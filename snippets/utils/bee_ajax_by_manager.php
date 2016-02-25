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
	$modx->log(xPDO::LOG_LEVEL_ERROR, 'bee_ajax_by_manager Params: ' . print_r($params, true));
	$modx->log(xPDO::LOG_LEVEL_ERROR, 'bee_ajax_by_manager POST: ' . print_r($_POST, true));

	switch($_POST['bee_ajax_snippet'])	{
		case('card_update'):
			$r = json_decode($modx->runSnippet('card_update', array(
					'number' => $params['number'],
					'name' => $params['name'],
					'expiry' => $params['expiry'],
					'user_id' => $params['user_id']), true));
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
					'user_id' => $params['user_id'])), true);
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
					'user_id' => $params['user_id'])), true);

			// сохранение профайла менеджером
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