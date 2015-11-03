<?php

/**
 * Get an Item
 */
class messengerOfficeItemGetProcessor extends modObjectGetProcessor {
	public $objectType = 'messengerItem';
	public $classKey = 'messengerItem';
	public $languageTopics = array('messenger:default');
	//public $permission = 'view';


	/**
	 * We doing special check of permission
	 * because of our objects is not an instances of modAccessibleObject
	 *
	 * @return mixed
	 */
	public function process() {
		if (!$this->checkPermissions()) {
			return $this->failure($this->modx->lexicon('access_denied'));
		}

		return parent::process();
	}

}

return 'messengerOfficeItemGetProcessor';