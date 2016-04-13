<?php
/**
 * FormIt Hook for removing any XSS-non-safe content
 */

/*$allFormFields = $hook->getValues();
$out = array();
foreach ($allFormFields as $key => $value)	{
	$out[$key] = htmlentities($value, ENT_QUOTES);
}

$hook->setValues($out);
return true;*/

$validator->fields[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');
return true;