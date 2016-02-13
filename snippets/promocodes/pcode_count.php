<?php
$id = $scriptProperties['id'];
return $modx->runSnippet('pdoResources', array(
		'parents' => 4837,
		'limit' => 0,
		'includeTVs' => 'pc_id',
		'tvFilters'=> 'pc_id==' . $id,
		'processTVs' => 'pc_id',
		'tpl' => '@INLINE [[+total]]'
));