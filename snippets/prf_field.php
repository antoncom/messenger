<?php
/**
 * Get regular field in profile
 * $field_name - name attribute for input field. It has to be equal modUser profile field
 * $provider - snippet name which provide data for the field
 * $field_header - for fuelux glass
 * $field_footer - for fuelux glass
 */

if(!empty($field_name))	{
	$modx->getService('lexicon','modLexicon');
	$modx->setOption('cultureKey', 'ru');
	$modx->lexicon->load('beecore:default');

	$prefix = 'bee_ajax_'; // prefix for input field name, which is used in 'bee_ajax' snippet
	$user = $modx->user;
	$profile = $user->getOne('Profile');
	if ($profile) {
		$extended = $profile->get('extended');
	}

	// run appropriative snippet to get data
	switch($provider)	{
		case('get_blogger_phone'):

			break;
		case('get_blogger_card'):

			break;

		default:
			// if provider is not defined then get regular modUser field
			switch($field_name)	{
				case('photo'):
					$out = $modx->getChunk('profile_photo', array('field_value' => $profile->get($field_name)));
					break;
				case('mobilephone'):
					$phone = $profile->get('mobilephone');
					$out = $modx->getChunk('profile_fuelux_header', array(
							'field_name' => $prefix . $field_name,
							'field_label' => $modx->lexicon('beecore.' . $field_name),
							'field_footer' => 'Прислать SMS с кодом',
							'field_value' => $phone));
					break;

				case('dob'):
					//$modx->log(xPDO::LOG_LEVEL_ERROR, 'Field: ' . $profile->get($field_name));
					// добавляем hidden-поле даты рождения для того, чтобы можно было обнулить основное поле даты
					// в том случае когда дата отсутствует
					$out = '<input type="hidden" id="dob_hidden" value = "'. $profile->get($field_name) .'"/>';
					$out .= $modx->getChunk('profile_text_input', array(
							'field_name' => $prefix . $field_name,
							'field_label' => $modx->lexicon('beecore.' . $field_name),
							//'field_value' => ($profile->get($field_name) > 0) ? date('%d %m %Y', $profile->get
					//($field_name)) : ''));
							'field_value' => ($profile->get($field_name) > 0) ? '10/10/2000' : ''
					break;

				default:
					// if provider is not set
					// then get data from modUser normal field or profile
					$from_profile = (in_array($field_name, array('username', 'password')) == FALSE);
					$out = $modx->getChunk('profile_text_input', array(
							'field_name' => $prefix . $field_name,
							'field_label' => $modx->lexicon('beecore.' . $field_name),
							'field_value' => ($from_profile) ? $profile->get($field_name) : $user->get($field_name)));
			}

	}
	return $out;
}