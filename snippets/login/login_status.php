<?php
/**
 * Показать пикторамму статуса и popover авторизации в правом верхнем углу меню
 * Если пользователь не залогинен, то выводим серую пиктограмму с popover-ом авторизации
 * Если пользоатель уже залогинен, то выводим зеленую пиктограмму и popover состояния авторизации
 */

$user = $modx->getAuthenticatedUser('web');

// Если пользователь уже залогинен, то выводим его аватар и popover состояния авторизации
if ($user)
{
	$profile = $user->getOne('Profile');
	$login_avatar = $modx->getChunk('profile_photo', array('field_value' => $profile->get('photo')));
	$socicon = $modx->runSnippet('get_socicon', array('userid' => $user->get('id')));
	$popover_icon = 'login_active';
	$popover_title = 'Вы авторизованы';
	$popover_content = 'контент статуса авторизации';
	$popover_content = $modx->getChunk('popover_authorized', array(
			'login_avatar' => $login_avatar,
			'socicon' => $socicon,
			'fullname'=>$profile->get('fullname'),
			'username'=>$user->get('username'),
			'email'=>$profile->get('email')
	));

}
// Если пользователь не залогинен, то выводим серую пиктограмму с popover-ом авторизации
else{
	$popover_icon = 'login_inactive';
	$popover_title = 'Авторизация пользователя';
	$popover_content = $modx->getChunk('popover_login');
//	, array(
//			'login_avatar' => $login_avatar,
//			'socicon' => $socicon,
//			'fullname'=>$profile->get('fullname'),
//			'username'=>$user->get('username'),
//			'email'=>$profile->get('email')
//	));
}

$output = ''
	.'<a href="javascript:;" class="beelogin '.$popover_icon.'">&nbsp;</a>'
	.'<div class="popover_header hide">'.$popover_title.'</div>'
	.'<div class="popover_content hide">'.$popover_content.'</div>';
return $output;

