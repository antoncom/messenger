<?php
/** @noinspection PhpIncludeInspection */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var messenger $messenger */
$messenger = $modx->getService('messenger', 'messenger', $modx->getOption('messenger_core_path', null, $modx->getOption('core_path') . 'components/messenger/') . 'model/messenger/');
$modx->lexicon->load('messenger:default');

// handle request
$corePath = $modx->getOption('messenger_core_path', null, $modx->getOption('core_path') . 'components/messenger/');
$path = $modx->getOption('processorsPath', $messenger->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));