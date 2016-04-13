<?php
/**
 * Выдать значения переданные при помощи GET
 * На входе сниппета - названия переменных( &varNames=`user,password,etc`), которые необходимо выдать в плейсхолдер
 * Если на входе ничего нет, то в плейсхолдеры выдаются все переменные переданные методом GET
 */

$out = array();
$get = $_GET;
$get_out = array();
if(!empty($scriptProperties['varNames'])) {
	$varNames = explode(",", $scriptProperties['varNames']);
	$propNames = array_keys($scriptProperties);
	foreach($get as $key=>$value)	{
		if ($key === in_array($varNames)) $out[$key] = htmlentities($value, ENT_QUOTES);
	}
	$modx->toPlaceholders($out,'beeget');
}
else{
	foreach($get as $key=>$value)	{
		$get_out[$key] = htmlentities($value, ENT_QUOTES);
	}
	$modx->toPlaceholders($get_out,'beeget');
}
return '';