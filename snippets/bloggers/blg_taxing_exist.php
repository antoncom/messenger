<?php
/**
 * Узнать есть ли налоговые данные в профиле пользователя
 */
$user_id = $scriptProperties['user_id'];
$user = (!empty($user_id)) ? $modx->getObject('modUser', array('id' => $user_id)) : $modx->user;
$profile = $user->getOne('Profile');
$extended = $profile->get('extended');

return (is_array($extended['taxing'])) ? '1' : '0';
//return json_encode($extended['taxing']);