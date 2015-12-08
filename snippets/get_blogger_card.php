<?php
$field = $scriptProperties['field'];
$out = "";
$user = $modx->user;
$profile = $user->getOne('Profile');
if ($profile) {
	$extended = $profile->get('extended');
	if($extended['blogger_card'] != NULL)	{
		$card_number = $extended['blogger_card']['number'];
		$number = sprintf("%s xxxx xxxx %s",
			substr($card_number, 0, 4),
			substr($card_number, 11, 4));
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