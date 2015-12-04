<?php
/**
 * Run any snippet via ajax
 */

// TODO


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

	$params['as_mode'] = 'onclick';
	$params['as_target'] = 'pa_join_status_' . $params['pa_id'];

	if (empty($params['bonus_method'])) {
		return $AjaxForm->error('Ошибка', array('name' => 'Необходимо выбрать способ получения бонусов!'));
	}
	else {
		$r = $modx->runSnippet('blogger_join_promoaction', array(
			'pa_id' => $params['pa_id'],
			'bonus_method' => $params['bonus_method']
		));
		return $AjaxForm->success($r . ' - Способ получения бонусов: на баланс телефона.');
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