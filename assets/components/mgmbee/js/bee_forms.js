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
		$('input[name=bee_ajax_mobilephone_confirmed]').val('');
	});
});

