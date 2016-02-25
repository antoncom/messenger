<?php
$field = $scriptProperties['field'];
$mask_num = $scriptProperties['mask_num'];
$user_id = $scriptProperties['user_id'];
$out = "";
$user = (!empty($user_id)) ? $modx->getObject('modUser', array('id' => $user_id)) : $modx->user;
$profile = $user->getOne('Profile');
if ($profile) {
	$extended = $profile->get('extended');
	if($extended['blogger_card'] != NULL)	{
		$card_number = $extended['blogger_card']['number'];
		if($mask_num == '1')	{
			$number = sprintf("%s xxxx xxxx %s",
					substr($card_number, 0, 4),
					substr($card_number, 12, 4));
		}
		else	{
			$number = sprintf("%s %s %s %s",
					substr($card_number, 0, 4),
					substr($card_number, 4, 4),
					substr($card_number, 8, 4),
					substr($card_number, 12, 4));
		}
		$name = $extended['blogger_card']['name'];
		$expiry = $extended['blogger_card']['expiry'];
		// если на входе указано поле
		switch($field)	{
			case('number'):
				$out = $number;
				break;

			case('name'):
				$out = $name;
				break;

			case('expiry'):
				$out = $expiry;
				break;

			default:
				$out = $number;
		}
	}
	// если поле не указано и карты у блоггера еще нет
	else if(empty($field))	{
		$out = '<a class="change_card">[ввести данные]</a>';
	}
	return $out;
}