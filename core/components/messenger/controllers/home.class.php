<?php

/**
 * The home manager controller for messenger.
 *
 */
class messengerHomeManagerController extends messengerMainController {
	/* @var messenger $messenger */
	public $messenger;


	/**
	 * @param array $scriptProperties
	 */
	public function process(array $scriptProperties = array()) {
	}


	/**
	 * @return null|string
	 */
	public function getPageTitle() {
		return $this->modx->lexicon('messenger');
	}


	/**
	 * @return void
	 */
	public function loadCustomCssJs() {
		$this->addCss($this->messenger->config['cssUrl'] . 'mgr/main.css');
		$this->addCss($this->messenger->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
		$this->addJavascript($this->messenger->config['jsUrl'] . 'mgr/misc/utils.js');
		$this->addJavascript($this->messenger->config['jsUrl'] . 'mgr/widgets/items.grid.js');
		$this->addJavascript($this->messenger->config['jsUrl'] . 'mgr/widgets/items.windows.js');
		$this->addJavascript($this->messenger->config['jsUrl'] . 'mgr/widgets/home.panel.js');
		$this->addJavascript($this->messenger->config['jsUrl'] . 'mgr/sections/home.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			MODx.load({ xtype: "messenger-page-home"});
		});
		</script>');
	}


	/**
	 * @return string
	 */
	public function getTemplateFile() {
		return $this->messenger->config['templatesPath'] . 'home.tpl';
	}
}