$(document).ready(function() {
//	$('#profile_wizard').wizard('selectedItem', { step: 2 });
	$('#profile_wizard').wizard();
	$('#profile_wizard').find('ul.steps li').toggleClass('complete', true);

	$('#profile_wizard').on('changed.fu.wizard', function (evt, data) {
		$('#profile_wizard').find('ul.steps li').toggleClass('complete', true);
		console.log('here');
	});
	//d = $('#myDatepickerInput').val();

	//d = '1449834498';
	//console.log(d);
	//console.log(parseInt(d));
	//dd = new Date(parseInt(d));
	//console.log(dd);
	//
	//aa = new Date('December 17, 1995 03:24:00');
	//
	//$('#myDatepicker').datepicker('setDate', aa);

});