<?php
$type = $scriptProperties['type'];
$code = $scriptProperties['code'];
$user = $modx->user;
$profile = $user->getOne('Profile');
if ($profile) {
	$tel = $profile->get('mobilephone');
	if (!empty($tel)) {
		$out = sprintf("(%s) %s-%s",
			substr($tel, 0, 3),
			substr($tel, 3, 3),
			substr($tel, 6, 4));
		if(!empty($code)) $out = $code . $out;

	} else {
		if($type == 'link') $out = '<a class="change_phone">[ввести данные]</a>';
		else $out = '';
	}
	return $out;
}