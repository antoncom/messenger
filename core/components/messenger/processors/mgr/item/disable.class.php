<?php

/**
 * Disable an Item
 */
class messengerItemDisableProcessor extends modObjectProcessor {
	public $objectType = 'messengerItem';
	public $classKey = 'messengerItem';
	public $languageTopics = array('messenger');
	//public $permission = 'save';


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

			$object->set('active', false);
			$object->save();
		}

		return $this->success();
	}

}

return 'messengerItemDisableProcessor';
