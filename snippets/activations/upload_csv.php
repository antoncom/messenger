<?php
/*
 * Upload файла активаций
 * Разбор массива:
 * - замена промо-кода на его ID,
 * - получение ID блоггера для промо-кода
 * - получение ID промо-акции
 * - формализация даты активации
 *
 */
	$error = "";
	$lines = array();
	$sqlBody ="";
	$protoRes = array(
		'pagetitle'=>"",
		'alias'=>"",
		'template'=>13,
		'published'=>1,
		'hidemenu'=>1,
		'parent'=>4837
	);

	$i=0;
	$lines = array();
	$fh = fopen($_FILES['myfile']['tmp_name'], 'r+');
	while( ($row = fgetcsv($fh, 8192, ";")) !== FALSE ) {
		$lines[] = $row;
		if(count($lines) < 2) continue; // пропускаем заголовочную строку CSV-файла


		list($day, $month, $year) = explode('.', $row[0]);
		$actDate = mktime(0, 0, 0, $month, $day, $year);

		//$actDate = strtotime($row[0]);
		$abonent = $row[1];
		$pcode = $row[2];

		// получаем ID промо-кода, промо-акции и блогера
		$pcodeData = json_decode($modx->runSnippet('getPcodeId', array('pagetitle' => $pcode)), true);
		if(isset($pcodeData['error']))	{
			$error .= "<br/>" . $pcodeData['error'];
			continue;
		}

		// получаем последнюю цену бонуса с тем, чтобы прописать ее в TV bonus_size_set для каждой активации
		$lastBonusSize = $modx->runSnippet('getLastBonusSize', array('pa_id' => $pcodeData['pa_id']));

		// формируем массивы ресурсов, ТВ и групп ресурсов
		$alias = $abonent . "-" . $pcode;
		$protoRes['pagetitle'] = "'" . $abonent . "'";
		$protoRes['alias'] = "'" . $alias . "'";

		$tvsAll[$alias] = array(
			'act_date'=>$actDate,
			'blogger_id' =>$pcodeData['blogger_id'],
			'pa_id'=>$pcodeData['pa_id'],
			'pc_id'=>$pcodeData['id'],
			'bonus_size_set'=>$lastBonusSize);

		$grpsAll[$alias] = array(1,2);

		$resAll[$alias] = $protoRes;
		$i++;
	}

$modx->log(xPDO::LOG_LEVEL_ERROR, print_r($tvsAll, true));

	// Выполняем массовое добавление
	$count = $modx->runSnippet('addResourcesWithTVandGroup', array(
					'resources' => json_encode($resAll, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE),
					'tvs' => json_encode($tvsAll, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE),
					'grps' => json_encode($grpsAll, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE)
				)
	);

	$message = "<strong>CSV:</strong> обработано строк: " . ++$i . ".   <strong>БД:</strong> добавлено/обновлено строк: " . $count . ".";
	if(!empty($error))	{
		$message = $message . "[Error]" . $error;
	}

	echo $message;

	// TODO
	// Код ниже переносим в сниппет addResourcesWithTVandGroup

//	// Добавляем в БД ресурсы (активации) одним запросом
//	if(strlen($sqlBody) > 0) {
//		$sqlHead = "INSERT INTO modx_site_content (" . implode(',', array_keys($protoRes)) . ") VALUES ";
//		$sqlFooter = " ON DUPLICATE KEY UPDATE id=id, pagetitle=pagetitle, alias=alias";
//		$sqlBody = rtrim($sqlBody, ",");
//		$sql = $sqlHead . $sqlBody . $sqlFooter;
//		$modx->log(xPDO::LOG_LEVEL_ERROR, "SQL INSERT = ". $sql);
//
//		// Отправляем активации одним SQL-запросом в БД
//		$q = $modx->prepare($sql);
//		if(!$q->execute(array(0)))	{
//			$modx->log(xPDO::LOG_LEVEL_ERROR, 'upload_csv snippet ERROR' . print_r($q->errorInfo(),true));
//			return;
//		}

		// Получаем массив соовтетствия pagetitle => id

		/*$resourcesJson = $modx->runSnippet('getResIDbyPagetitle', array('pagetitle'=> explode(",", $restoGroups)));

		// Добавляем значения TV-полей
		$modx->runSnippet('addTVValues', array('abonents' => $resourcesJson,
				'act_dates' => json_encode($actDates),
				'bloggers' => json_encode($bloggers),
				'promo_actions' => json_encode($promo_actions),
				'pcodes' => json_encode($pcodes))
		);

		// Добавляем в группы ресурсов и получаем json-массив ID добавленных ресурсов
		$modx->runSnippet('addtoResourceGroup', array('resources' => json_encode($restoGroups),
													  'groups' => json_encode(array(1,2)),
			  										  'parents' => json_encode(array(4837)))
		);*

		echo "Добавлено записей об активации: " . $i . "<br /> " . $error;
	}
	else	{
		echo "Ничего не добавлено.<br /> " . $pcodeData['error'];
	}
		*/

//	echo "<pre>";
//	echo print_r($lines,true);
//echo "</pre>";

	//echo "Uploaded File :".$_FILES["myfile"]["name"] . "content = " . print_r($csv, true);