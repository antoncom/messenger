$(document).ready(function() {
//	$('#profile_wizard').wizard('selectedItem', { step: 2 });
	$('#profile_wizard').wizard();
	$('#profile_wizard').find('ul.steps li').toggleClass('complete', true);

	$('#profile_wizard').on('changed.fu.wizard', function (evt, data) {
		$('#profile_wizard').find('ul.steps li').toggleClass('complete', true);
	});
});