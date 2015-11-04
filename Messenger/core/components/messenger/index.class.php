<?php

/**
 * Class messengerMainController
 */
abstract class messengerMainController extends modExtraManagerController {
	/** @var messenger $messenger */
	public $messenger;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('messenger_core_path', null, $this->modx->getOption('core_path') . 'components/messenger/');
		require_once $corePath . 'model/messenger/messenger.class.php';

		$this->messenger = new messenger($this->modx);
		//$this->addCss($this->messenger->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->messenger->config['jsUrl'] . 'mgr/messenger.js');
		$this->addHtml('
		<script type="text/javascript">
			messenger.config = ' . $this->modx->toJSON($this->messenger->config) . ';
			messenger.config.connector_url = "' . $this->messenger->config['connectorUrl'] . '";
		</script>
		');

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('messenger:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends messengerMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}