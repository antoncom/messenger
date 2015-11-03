<?php

/**
 * Create an Item
 */
class messengerOfficeItemCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'messengerItem';
	public $classKey = 'messengerItem';
	public $languageTopics = array('messenger');
	//public $permission = 'create';


	/**
	 * @return bool
	 */
	public function beforeSet() {
		$name = trim($this->getProperty('name'));
		if (empty($name)) {
			$this->modx->error->addField('name', $this->modx->lexicon('messenger_item_err_name'));
		}
		elseif ($this->modx->getCount($this->classKey, array('name' => $name))) {
			$this->modx->error->addField('name', $this->modx->lexicon('messenger_item_err_ae'));
		}

		return parent::beforeSet();
	}

}

return 'messengerOfficeItemCreateProcessor';