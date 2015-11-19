<?php
/**
 * Created by PhpStorm.
 * User: well
 * Date: 09.11.15
 * Time: 11:43
 */

$fullname = "";
if(!empty($scriptProperties['id'])) {
	$id = $scriptProperties['id'];
	$profile = $modx->getObject('modUserProfile', array('internalKey' => $id));
	$fullname = $profile->get('fullname');
}


return $fullname;