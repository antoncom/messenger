new Card({
	form: document.querySelector('form'),
	container: '.card-wrapper'
});

function refreshModal(modal_id)	{
	switch(modal_id)	{
		case('accepting_payment'):
			$('#tab_phone').hide();
			$('#tab_card').hide();
			$('#card_pay_method').toggleClass('disabled', false);
			$('#phone_pay_method').toggleClass('disabled', false);
			$('#ok_card').toggleClass('glyphicon glyphicon-ok', false);
			$('#ok_card').toggleClass('blank', true);
			$('#ok_phone').toggleClass('glyphicon glyphicon-ok', false);
			$('#ok_phone').toggleClass('blank', true);
			$('.text_card').toggleClass('disactive', false);
			$('.text_phone').toggleClass('disactive', false);

			break;

		case('extract_promocode'):
			labelId = $('input[name=extract_promocode_to]:checked', '#extract_promocode_form').parent().attr('id');
			$('#'+labelId).parent().find('.ok-check').toggleClass('blank', true);
			$('#'+labelId).parent().find('.ok-check').toggleClass('glyphicon glyphicon-ok', false);
			$('#'+labelId).parent().find('.text').toggleClass('disactive', false);
			$('#'+labelId).parent().find('label').toggleClass('active', false);


		default: ;
	}
}


$(document).ready(function() {
	$('#bonus_to_card').change(function () {

		if($(this).val() == "card")	{
			$('#ok_card').toggleClass('glyphicon glyphicon-ok', true);
			$('#ok_card').toggleClass('blank', false);
			$('#ok_phone').toggleClass('glyphicon glyphicon-ok', false);
			$('#ok_phone').toggleClass('blank', true);
			$('.text_card').toggleClass('disactive', false);
			$('.text_phone').toggleClass('disactive', true);
		}
	});


	$('#bonus_to_phone').change(function () {
		if($(this).val() == "phone")	{
			$('#ok_card').toggleClass('glyphicon glyphicon-ok', false);
			$('#ok_card').toggleClass('blank', true);
			$('#ok_phone').toggleClass('glyphicon glyphicon-ok', true);
			$('#ok_phone').toggleClass('blank', false);
			$('.text_card').toggleClass('disactive', true);
			$('.text_phone').toggleClass('disactive', false);
		}
	});

	$('#change_phone').click(function(event){
		event.stopImmediatePropagation();
		$('#tab_card').hide();
		$('#tab_phone').show();
		$('#card_pay_method').toggleClass('disabled', true);
	});

	$('#change_card').click(function(event){
		event.stopImmediatePropagation();
		$('#tab_phone').hide();
		$('#tab_card').show();
		$('#phone_pay_method').toggleClass('disabled', true);
	});

	jQuery(function($){
		$("#blogger_phone").mask("(999) 999-9999",{completed: function(){
				// если код валидный
				$("#send_sms_btn").toggleClass('disabled', false);
			}});
	});

	$('#send_sms_btn').on('click', function () {
		var $btn = $(this).button('loading')
		// business logic...
		//$btn.button('reset');
	});

	$('#apply_join_promoaction').on('click', function () {
		$('#bonus_method').submit();
	});

	$('#accepting_payment').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget) // Button that triggered the modal
		var recipient = button.data('whatever') // Extract info from data-* attributes
		// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		var modal = $(this);
		modal.find('#bee_ajax_pa_id').val(recipient);
	});

	$('#accepting_payment').on('hide.bs.modal', function (event) {
		refreshModal('accepting_payment');
	});

	// Extract promocode Modal
	$('#extract_promocode_form input').on('change', function() {
		refreshModal('extract_promocode');
		$('#'+labelId + ' .ok-check').toggleClass('glyphicon glyphicon-ok', true);
		$('#'+labelId + ' .ok-check').toggleClass('blank', false);
	});
	$('#extract_promocode').on('hide.bs.modal', function (event) {
		refreshModal('extract_promocode');
	});

	$(document).on('as_complete', document, function(e,d) {
		console.log(d);
		$('#pcode').text(d.output);
		$('#extract_promocode').modal('show');
	});

});




//var card = new Card({
//	// a selector or DOM element for the form where users will
//	// be entering their information
//	form: 'form', // *required*
//	// a selector or DOM element for the container
//	// where you want the card to appear
//	container: '.card-wrapper', // *required*
//
//	formSelectors: {
//		numberInput: 'input#number', // optional — default input[name="number"]
//		expiryInput: 'input#expiry', // optional — default input[name="expiry"]
//		cvcInput: 'input#cvc', // optional — default input[name="cvc"]
//		nameInput: 'input#name' // optional - defaults input[name="name"]
//	},
//
//	width: 200, // optional — default 350px
//	formatting: true, // optional - default true
//
//	// Strings for translation - optional
//	messages: {
//		validDate: 'valid\ndate', // optional - default 'valid\nthru'
//		monthYear: 'mm/yyyy', // optional - default 'month/year'
//	},
//
//	// Default placeholders for rendered fields - optional
//	placeholders: {
//		number: '•••• •••• •••• ••••',
//		name: 'Full Name',
//		expiry: '••/••',
//		cvc: '•••'
//	},
//
//	// if true, will log helpful messages for setting up Card
//	debug: false // optional - default false
//});