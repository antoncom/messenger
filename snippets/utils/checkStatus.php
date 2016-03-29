<?php
/* CheckStatus snippet */

if (! $modx->user->hasSessionContext($modx->context->get('key'))) {
	$url = $modx->makeUrl($scriptProperties['loginId'], "", "", "full");
	$modx->sendRedirect($url);
}

return "";