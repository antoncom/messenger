<?php
/**
 * Created by PhpStorm.
 * User: well
 * Date: 09.11.15
 * Time: 14:16
 */


$modx->log(xPDO::LOG_LEVEL_ERROR, 'AJAX: ' .print_r($_REQUEST, true));
$output='{
		"data": [
					[
						"1",
						"010001-00.00.0000",
						"",
						"00.00.0000",
						"",
						"",
						"0"
					],
				]
		}';

return $output;