<?php
//$modx->log(xPDO::LOG_LEVEL_ERROR, 'PCODE_PROC: ' .print_r($_REQUEST, true));

// получаем команду и данные из запроса
$bee_comm = $_REQUEST['beeComm'];
$bee_data = $_REQUEST['beeData'];

if(!isset($bee_comm))	{
	return;
}

// выполняем операции с активациями
switch($bee_comm)	{
	case ('delete'): {
		if(isset($bee_data))	{
			$bee_data = str_replace("row_", "", $bee_data);
			$ids = explode(",",$bee_data);
			if(count($ids)>0)	{
				$modx->removeCollection('modResource', array('id:IN'=> $ids));
				$modx->removeCollection('modTemplateVarResource', array('contentid:IN'=> $ids));
				$modx->removeCollection('modResourceGroupResource', array('document:IN'=> $ids));
			}
		}
		break;
	}
	default:{}
}