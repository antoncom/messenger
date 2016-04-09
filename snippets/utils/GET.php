<?php
/**
 * Выдать значения переданные при помощи GET
 * На входе сниппета - названия переменных( &varNames=`user,password,etc`), которые необходимо выдать в плейсхолдер
 * Если на входе ничего нет, то в плейсхолдеры выдаются все переменные переданные методом GET
 */

$out = array();
$get = $_GET;
if(!empty($scriptProperties['varNames'])) {
	$varNames = explode(",", $scriptProperties['varNames']);
	$propNames = array_keys($scriptProperties);
	foreach($get as $key=>$value)	{
		if ($key === in_array($varNames)) $out[$key] = $value;
	}

	$modx->toPlaceholders($out,'beeget');
}
else{
	$modx->toPlaceholders($get,'beeget');
}
return '';