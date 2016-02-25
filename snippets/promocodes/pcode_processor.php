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
			'uri'=>"",
			'template'=>8,
			'published'=>1,
			'hidemenu'=>1,
			'parent'=>5135, //$beeData['promo_action_resid']
		);


		// Получаем код промоакции, напр. 01, 02, хранимый в TV 'pa_code'
		if(!$promo_action_code = $modx->runSnippet('pdoField', array(
													'id'=>$beeData['pa_id'],
													'field'=>'pa_code')))	{
			$modx->log(xPDO::LOG_LEVEL_ERROR, 'pcode_processor: No promo_action_code for res [' . $beeData['pa_id'] . ']');
			return false;
		}
		$pa_code = str_pad($promo_action_code, 2, '0', STR_PAD_LEFT);

		// Получаем ближайший стартовый номер для генерации промо-кодов, напр. 1612
		$new_start_pcode = $modx->runSnippet('startNum.pcode', array(
													'pa_code' => $pa_code)
		);


		// формируем массивы объектов на добавление множества промо-кодов вида 011612, 011613
		$count = $beeData['count'];
		$n_pcode = $new_start_pcode;
		while ($count > 0)	{
			$pagetitle = $pa_code . str_pad($n_pcode, 4, '0', STR_PAD_LEFT);
			$protoRow['pagetitle'] = "'" . $pagetitle . "'";
			$protoRow['alias'] = $protoRow['pagetitle'];
			$protoRow['uri'] = "'" . $pagetitle . ".html'";

			$alias = $pagetitle;
			$tvsAll[$alias] = array(
					'pa_id'=>$beeData['pa_id']);

			$grpsAll[$alias] = array(1,2);
			$resAll[$alias] = $protoRow;

			$count--;
			$n_pcode++;
		}


		// Выполняем массовое добавление
		$count = $modx->runSnippet('addResourcesWithTVandGroup', array(
						'resources' => json_encode($resAll, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE),
						'tvs' => json_encode($tvsAll, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE),
						'grps' => json_encode($grpsAll, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE)
				)
		);


		break;
	}
	default:{}
}