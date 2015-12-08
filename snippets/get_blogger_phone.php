<?php
$user = $modx->user;
$profile = $user->getOne('Profile');
if ($profile) {
	$tel = $profile->get('mobilephone');
	if (!empty($tel)) {
		$out = '+7' . sprintf("(%s) %s-%s",
			substr($tel, 0, 3),
			substr($tel, 3, 3),
			substr($tel, 6, 4));
	} else {
		$out = '<a class="change_phone">[ввести данные]</a>';
	}
	return $out;
}