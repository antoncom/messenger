<?php
/*
 * Обрабатывает ajax запросы от сниппета snippet.ajaxsnippet_bee
 */
switch ($modx->event->name) {

	case 'OnLoadWebDocument':
		if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
			return;
		}
		/** @var xPDOFileCache $cache */
		$cache = $modx->cacheManager;
		$cache_key = '/ajaxsnippet_bee/';

		if (!empty($_REQUEST['as_action']) && $scriptProperties = $cache->get($cache_key . $_REQUEST['as_action'])) {
			// Берем данные переданные из формы
			// и получаем параметры для запуска пользовательского сниппета
			if(!empty($_POST['bee_ajax_snippet'])) {
				$params = array();
				foreach ($_POST as $key => $value) {
					if (strpos($key, 'bee_ajax_') === FALSE) continue;
					else {
						$paramName = str_replace('bee_ajax_', '', $key);
						$params[$paramName] = $value;
					}
				}
			}
			// Здесь имеем массив params[key]=value который содержит параметры для запуска сниппета

			$output = '';
			/** @var modSnippet $object */
			if ($object = $modx->getObject('modSnippet', array('name' => $params['snippet']))) {
				$properties = $object->getProperties();
				$scriptProperties = array_merge($properties, $params);

				$output = $object->process($params);
				if (strpos($output, '[[') !== false) {
					$maxIterations = intval($modx->getOption('parser_max_iterations', $options, 10));
					$modx->parser->processElementTags('', $output, true, false, '[[', ']]', array(), $maxIterations);
					$modx->parser->processElementTags('', $output, true, true, '[[', ']]', array(), $maxIterations);
				}
			}

			$response = array(
				'output' => $output,
				'key' => $_REQUEST['as_action'],
				'snippet' => $params['snippet'],
			);

			echo $modx->toJSON($response);
			@session_write_close();
			exit;
		}
		break;

}
