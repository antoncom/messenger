$(document).ready(function() {
	// profile
	$(document).on('as_complete', document, function(e,d) {
		// для поля mobilephone применяем маску после загрузки поля при помощи AjaxSnippet
		if(d.key == 'b282751839ab5fe8ce666b8864a01dcc4e2d3712')	{
			$("input[name=bee_ajax_mobilephone]").mask("(999) 999-9999");
		}
	});

	// После сабмита формы сниппетом AjaxForm делаем различные действия в зависимости от id формы.
	$(document).on('af_complete', function(event, response) {
		var form = response.form;

		// После обновления профайла
		if (form.attr('id') == 'update_profile') {
			// Форматируем дату для отображения значения поля после сабмита
			var drp = $("input[name=bee_ajax_dob]").data('daterangepicker');
			$("input[name=bee_ajax_dob]").val(drp.startDate.format('DD/MM/YYYY'));
		}
		// Иначе печатаем в консоль весь ответ
		else {
			//
		}
	});

	// обнуляем поле даты в том случае если у пользователя в профиле не указна дата родждения
	if($('input[id="bee_ajax_dob"]').val() == 0)	{
		$('input[name="dob_helper"]').val('');
	}

	$("#update_profile").submit(function() {
		// убираем маску в поле mobilephone перед сабмитом
		//$("input[name=bee_ajax_mobilephone]").val($("input[name=bee_ajax_mobilephone]").mask());

		// Превращаем дату в unixtime перед сабмитом
		var drp = $("input[name=bee_ajax_dob]").data('daterangepicker');
		$("input[name=bee_ajax_dob]").val(drp.startDate._d.getTime()/1000);
	});

	// Для страницы "Профиль". Если телефон был изменен и не прошел подтверждения
	$('#bee_ajax_blogger_phone').on('change', function () {
		$("#send_confirm_code").toggleClass('inactive', false);
		$("#send_confirm_code").toggleClass('disabled', false);

		$("#apply_confirm_code").toggleClass('disabled', true);
		$("#apply_confirm_code").toggleClass('inactive', true);
	});

	$('#bee_ajax_blogger_phone').on('keyup', function() {
		$('input[name=bee_ajax_mobilephone_confirmed]').val('');

		// При редактировании телефона деактивируем кнопки
		var ph_input = $('input[name=bee_ajax_blogger_phone]').val();
		var re = /^\(\d{3}\)\s\d{3}-\d{4}$/;
		if (!re.test( ph_input ) ) {
			$("#send_confirm_code").toggleClass('inactive', true);
			$("#send_confirm_code").toggleClass('disabled', true);
			$("#send_confirm_code").prop('disabled', false);

			$("#apply_confirm_code").toggleClass('disabled', true);
			$("#apply_confirm_code").toggleClass('inactive', true);
		}
		else {
			$("#send_confirm_code").toggleClass('inactive', false);
			$("#send_confirm_code").toggleClass('disabled', false);
			$("#send_confirm_code").prop('disabled', false);
		}
	});

	$('#bee_ajax_blogger_phone').on('focus', function() {
		$('input[name=bee_ajax_mobilephone_confirmed]').val('');
		// При редактировании телефона деактивируем кнопки
		var ph_input = $('input[name=bee_ajax_blogger_phone]').val();
		var re = /^\(\d{3}\)\s\d{3}-\d{4}$/;
		if (re.test( ph_input ) ) {
			$("#send_confirm_code").toggleClass('inactive', false);
			$("#send_confirm_code").toggleClass('disabled', false);
			$("#send_confirm_code").prop('disabled', false);
			$("#bee_ajax_blogger_confirmcode").prop('disabled', true);

			$("#apply_confirm_code").toggleClass('disabled', true);
			$("#apply_confirm_code").toggleClass('inactive', true);
			$("#apply_confirm_code").prop('disabled', true);

		}
	});

	 // Для графы "Телефон" блогера в профайле
	$("#bee_ajax_blogger_phone").mask("(999) 999-9999",{completed: function(){
		// если код валидный
		$("#send_confirm_code").toggleClass('disabled', false);
		$("#send_confirm_code").toggleClass('inactive', false);
	}});


	// При нажатии ENTER в поле "Телефон" в профайле инициируем клик по кнопке "Прислать SMS  с кодом
	$("#myplacard").placard({
		explicit: true,
		onAccept: function (helpers) {
			var re = /^"\(\d{3}\)\s\d{3}-\d{4}"$/;
			if (!re.test(helpers.value)) {
				$("#send_confirm_code").trigger("click");
			}
		}
	});

	$(document).click(function() {
		$("#bee_ajax_blogger_confirmcode").prop('disabled', true);
		$('#bee_ajax_blogger_confirmcode').val('');
		$("#myplacard").placard('hide');
	});

	$("#myplacard").click(function(event) {
		//alert('clicked inside');
		event.stopPropagation();
	});



	// Если в поле кода подтверждения нажат Enter
	$("#bee_ajax_blogger_confirmcode").keypress(function(event) {
		if (event.which == 13) {
			event.preventDefault();
			$( "#apply_confirm_code" ).trigger( "click" );
		}
	});

	// Если код подтверждения введен и валиден
	// то делаем активной кнопку "Подтвердить"
	$("#bee_ajax_blogger_confirmcode").mask("999999",{completed: function(){
		$("#apply_confirm_code").toggleClass('disabled', false);
		$("#apply_confirm_code").toggleClass('inactive', false);
	}});
	//$("div[data-initialize=placard]").placard({externalClickExceptions: ['#bee_ajax_blogger_phone']});
});


