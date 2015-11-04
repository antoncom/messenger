<?php

/**
 * Remove an Items
 */
class messengerItemRemoveProcessor extends modObjectProcessor {
	public $objectType = 'messengerItem';
	public $classKey = 'messengerItem';
	public $languageTopics = array('messenger');
	//public $permission = 'remove';


	/**
	 * @return array|string
	 */
	public function process() {
		if (!$this->checkPermissions()) {
			return $this->failure($this->modx->lexicon('access_denied'));
		}

		$ids = $this->modx->fromJSON($this->getProperty('ids'));
		if (empty($ids)) {
			return $this->failure($this->modx->lexicon('messenger_item_err_ns'));
		}

		foreach ($ids as $id) {
			/** @var messengerItem $object */
			if (!$object = $this->modx->getObject($this->classKey, $id)) {
				return $this->failure($this->modx->lexicon('messenger_item_err_nf'));
			}

			$object->remove();
		}

		return $this->success();
	}

}

return 'messengerItemRemoveProcessor';