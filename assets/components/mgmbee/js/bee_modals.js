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
			$('#extract_promocode_form input[type=radio]').parent().find('.ok-check').toggleClass('blank', true);
			$('#extract_promocode_form input[type=radio]').parent().find('.ok-check').toggleClass('glyphicon glyphicon-ok', false);
			$('#extract_promocode_form input[type=radio]').parent().find('.text').toggleClass('disactive', false);
			$('#extract_promocode_form input[type=radio]').parent().find('label').toggleClass('active', false);
			//$('#extract_promocode_form').parent(). label.btn').toggleClass('active', false);


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


	$('.change_phone').click(function(event){
		event.stopImmediatePropagation();
		$('#tab_card').hide();
		$('#tab_phone').show();
		$('#card_pay_method').toggleClass('disabled', true);
		$("#bee_ajax_blogger_phone").mask("(999) 999-9999");
	});

	$('.change_card').click(function(event){
		event.stopImmediatePropagation();
		$('#tab_phone').hide();
		$('#tab_card').show();
		$('#phone_pay_method').toggleClass('disabled', true);
	});

	jQuery(function($){
		$("#bee_ajax_blogger_phone").mask("(999) 999-9999",{completed: function(){
				// если код валидный
				$("#send_confirm_code").toggleClass('disabled', false);
			}});
	});



	$('#apply_join_promoaction').on('click', function () {
		$('#bonus_method').submit();
		//$('#extract_promocode').show();
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


	//$('#extract_promocode').on('show.bs.modal', function (event) {
	//	var button = $(event.relatedTarget) // Button that triggered the modal
	//	var recipient = button.data('whatever') // Extract info from data-* attributes
	//	// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
	//	// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
	//	var modal = $(this);
	//	modal.find('#bee_ajax_pa_id').val(recipient);
	//});

	$('#extract_promocode').on('shown.bs.modal', function(e)	{
		var button = $(e.relatedTarget);
		var pa_id = button.data('whatever');
		$(e.target).find('#extracted_promocode').load('/extract-promocode.html?pa_id=' + pa_id);
	});

	$('#extract_promocode').on('hide.bs.modal', function (event) {
		refreshModal('extract_promocode');
		$('#extract_promocode_form input[type=radio]').attr('checked', false);
		$('#extracted_promocode').html('');
	});

	
	$('#apply_extract_promocode').on('click', function() {
		choice = $('#extract_promocode_form').find('input[name=bee_ajax_extract_promocode_to]:checked').val();
		pcode = $('#extracted_promocode').text();
		$('#extract_promocode_form #promo_code').val(pcode);
		if(choice == 'clipboard')	{
			clipboard.copy({
				"text/plain": pcode
			});
		}
		$('#extract_promocode_form').submit();
	});

	$('#extract_promocode_form input[type=radio]').on('change', function () {
		refreshModal('extract_promocode');
		$(this).parent().find('.ok-check').toggleClass('glyphicon glyphicon-ok', true);
		$(this).parent().find('.ok-check').toggleClass('blank', false);
	});

	$(document).on('as_complete_extract_promocode_status', document, function(e,d) {
		//console.log(d);
		//$('#pcode').text(d.output);
		//$('#extract_promocode').modal('show');
	});


	$(document).on('as_complete_join_promoaction', document, function(e,d) {

	});




	// Подтверждение телефона по SMS
	$('#send_confirm_code').on('click', function() {
		var $btn = $(this).button('loading');
		$('#blg_phone_set input[name="bee_ajax_snippet"]').val('send_phone_confirmation');
		var phone = $('#bee_ajax_blogger_phone').val();
		var key = CryptoJS.SHA1(phone);
		$('#session_key').val(key.toString(CryptoJS.enc.Hex));
		$('#blg_phone_set').submit();
	});

	$('#apply_confirm_code').on('click', function() {
		var $btn = $(this).button('loading');
		$('#blg_phone_set input[name="bee_ajax_snippet"]').val('confirm_phone');
		$('#blg_phone_set').submit();
	});

	$("#blg_phone_set").submit(function() {
		// убираем маску перед сабмитом
		$("#bee_ajax_blogger_phone").val($("#bee_ajax_blogger_phone").mask());
	});

	$('#send_card_update').on('click', function () {
		var $btn = $(this).button('loading')
		$('#change_card').submit();
	});

	//$('#change_card').on('submit', function () {
	//
	//});
	$("#change_card").submit(function() {
		// добавляем к атрибуту name префикс "bee_ajax_" для того, чтобы наш сниппет корректно распознал передаваемые поля
		$(this).find('input[type=text]').attr('name', function(i, val){ return (val.indexOf('bee_ajax_') == -1) ? 'bee_ajax_' + val : val});
		// убираем маску перед сабмитом
		$("#bee_ajax_card_number").val($("#bee_ajax_card_number").val().split(' ').join(''));
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