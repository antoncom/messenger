<?php
//$modx->log(xPDO::LOG_LEVEL_ERROR, 'PCODE_PROC: ' .print_r($_REQUEST, true));

// получаем команду и данные из запроса
$bee_comm = $_REQUEST['beeComm'];
$bee_data = $_REQUEST['beeData'];

if(!isset($bee_comm))	{
	return;
}

// выполняем операции с промо-кодами
switch($bee_comm)	{
	case ('browse'): {
		if(!isset($bee_data)) break;

		//$beeData['promo_action_resid'];


		break;
	}
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

	case 'add':	{
		if(!isset($bee_data)) {
			break;
		}
		$beeData = json_decode($bee_data, true);
		$modx->log(xPDO::LOG_LEVEL_ERROR, 'pcode_processor: BeeData: ' . print_r($beeData,true));
		$protoRow = array(
			'pagetitle'=>"",
			'alias'=>"",
			'template'=>8,
			'published'=>1,
			'hidemenu'=>1,
			'parent'=>$beeData['promo_action_resid'],
			'content_type'=>7
		);

		// Получаем код промоакции, напр. 01, 02, хранимый в TV 'pa_code'
		if(!$promo_action_code = $modx->runSnippet('pdoField', array(
													'id'=>$beeData['promo_action_resid'],
													'field'=>'pa_code')))	{
			$modx->log(xPDO::LOG_LEVEL_ERROR, 'pcode_processor: No promo_action_code for res [' . $beeData['promo_action_resid'] . ']');
			return false;
		}
		$pa_code = str_pad($promo_action_code, 2, '0', STR_PAD_LEFT);

		// Получаем ближайший стартовый номер для генерации промо-кодов, напр. 1612
		$new_start_pcode = $modx->runSnippet('startNum.pcode', array(
													'id' => $beeData['promo_action_resid'])
		);

		// формируем запрос на добавление множества промо-кодов вида 011612, 011613
		$count = $beeData['count'];
		$n_pcode = $new_start_pcode;
		while ($count > 0)	{
			$protoRow['pagetitle'] = "'" . $pa_code . str_pad($n_pcode, 4, '0', STR_PAD_LEFT) . "'";
			$protoRow['alias'] = $protoRow['pagetitle'];
			$values = join(',', array_values($protoRow));
			$rows .= "(". $values .")";
			$count--;
			$rows .= ($count==0) ? "" : ", ";
			$n_pcode++;
		}

		$fields = join(',', array_keys($protoRow));
		$sql = "INSERT INTO {$modx->getTableName('modResource')} (" . $fields . ") VALUES " . $rows;
		$q = $modx->prepare($sql);
		if(!$q->execute(array(0)))	{
			$modx->log(xPDO::LOG_LEVEL_ERROR, 'startNum.pcode snippet ERROR' . print_r($q->errorInfo(),true));
			return;
		}




		// Помещаем созданные ресурсы в группу ресурсов для ограничения доступа к ним
		// Для этого вначале находим ID-шники только что созданных ресурсов
		$findFrom = $pa_code . str_pad($new_start_pcode, 4, '0', STR_PAD_LEFT);
		$q = $modx->newQuery('modResource', array(
										'pagetitle:>=' => $findFrom,
										'parent' => $beeData['promo_action_resid'])
		);
		$q->select('id');
		$q->prepare();
		$sql = $q->toSQL();
		if(!$q->stmt->execute())	{
			$modx->log(xPDO::LOG_LEVEL_ERROR, 'startNum.pcode snippet ERROR' . print_r($q->errorInfo(),true));
			return;
		}
		$resIds = $q->stmt->fetchAll(PDO::FETCH_COLUMN);

		// Помещаем ID-шники вновь созданных ресурсов промо кодов в таблицу групп ресурсов
		$protoGroup = array(
			'document_group'=>'',
			'document'=>''
		);
		$fields = "";
		$values = "";
		$rows = "";
		$groups = array(1,2);
		$lastInd = count($resIds) - 1;
		// Формируем SQL запрос
		foreach($groups as $ind1 => $group)	{
			foreach($resIds as $ind2 => $id)	{
				$protoGroup['document_group'] = $group;
				$protoGroup['document'] = $id;
				$values = join(',', array_values($protoGroup));
				$rows .= "(". $values .")";
				$rows .= ($ind1 == 1 && $ind2 == $lastInd) ? "" : ", ";
			}
		}
		$fields = join(',', array_keys($protoGroup));
		$sql = "INSERT INTO {$modx->getTableName('modResourceGroupResource')} (" . $fields . ") VALUES " . $rows;
		$q = $modx->prepare($sql);
		if(!$q->execute(array(0)))	{
			$modx->log(xPDO::LOG_LEVEL_ERROR, 'startNum.pcode snippet ERROR' . print_r($q->errorInfo(),true));
			return;
		}
		break;
	}
	default:{}
}