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

	$user_id = $scriptProperties['user_id'];
	$user = (!empty($user_id)) ? $modx->getObject('modUser', array('id' => $user_id)) : $modx->user;

	$profile = $user->getOne('Profile');
	if ($profile) {
		$extended = $profile->get('extended');
	}

	// run appropriative snippet to get data
	switch($provider)	{
		case('taxing'):
			$out = $modx->getChunk('profile_text_input', array(
					'field_name' => $prefix . $field_name,
					'field_label' => $modx->lexicon('beecore.' . $field_name),
					'field_value' => $extended['taxing'][$field_name]));
			break;

		default:
			// if provider is not defined then get regular modUser field
			switch($field_name)	{
				case('photo'):
					$photo_src = ($profile->get('photo') !== '' )
							? $profile->get('photo')
							: '/images/avatar_blank.png';
					$out = $modx->getChunk('profile_photo', array('field_value' => $photo_src));
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
					$dob = date('d/m/Y', $profile->get($field_name));
					$out = $modx->getChunk('profile_text_input', array(
							'field_name' => $prefix . $field_name,
							'field_label' => $modx->lexicon('beecore.' . $field_name),
							'field_value' => ($profile->get($field_name) > 0) ? $dob : ''));
					break;

				case('gender'):
					$out = $modx->getChunk('profile_radio_input', array(
							'field_name' => $prefix . $field_name,
							'field_label' => $modx->lexicon('beecore.' . $field_name),
							'field_value' => $profile->get($field_name)));
					break;
				case('password'):
					$out = $modx->getChunk('profile_password_input', array(
							'field_name' => $prefix . $field_name,
							'field_label' => $modx->lexicon('beecore.' . $field_name)));
//							'field_value' => $profile->get($field_name)));
					break;

				default:
					// if provider is not set
					// then get data from modUser normal field or profile
					$from_profile = (in_array($field_name, array('username', 'password')) == FALSE);
					$out = $modx->getChunk('profile_text_input', array(
							'field_name' => $prefix . $field_name,
							'field_label' => $modx->lexicon('beecore.' . $field_name),
							'field_value' => ($from_profile) ? $profile->get($field_name) : $user->get($field_name),
							'field_disabled' => ($field_name === 'username') ? 'readonly="readonly"' : ''));
			}

	}
	return $out;
}