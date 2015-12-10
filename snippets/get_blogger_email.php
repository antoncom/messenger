<?php
$profile = $modx->user->getOne('Profile');
return $profile ? $profile->get('email') : '';