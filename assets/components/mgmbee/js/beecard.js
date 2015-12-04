new Card({
	form: document.querySelector('form'),
	container: '.card-wrapper'
});


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
	})


	$('#bonus_to_phone').change(function () {
		if($(this).val() == "phone")	{
			$('#ok_card').toggleClass('glyphicon glyphicon-ok', false);
			$('#ok_card').toggleClass('blank', true);
			$('#ok_phone').toggleClass('glyphicon glyphicon-ok', true);
			$('#ok_phone').toggleClass('blank', false);
			$('.text_card').toggleClass('disactive', true);
			$('.text_phone').toggleClass('disactive', false);
		}
	})
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

	//$('#blogger_phone').inputmask("phone", {
	//	url: "Scripts/jquery.inputmask/phone-codes/phone-codes.json",
	//	onKeyValidation: function () { //show some metadata in the console
	//		console.log($(this).inputmask("getmetadata")["city"]);
	//	}
	//});

	//$("#blogger_phone").mask("(999) 999-9999");
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