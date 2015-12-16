$(document).ready(function() {
	// profile
	$(document).on('as_complete', document, function(e,d) {
		// для поля mobilephone применяем маску после загрузки поля при помощи AjaxSnippet
		if(d.key == 'b282751839ab5fe8ce666b8864a01dcc4e2d3712')	{
			$("input[name=bee_ajax_mobilephone]").mask("(999) 999-9999");
		}
	});

	$(document).on('af_complete', function(event, response) {
		var form = response.form;
		// Если у формы определённый id
		if (form.attr('id') == 'update_profile') {
			var drp = $("input[name=dob_helper]").data('daterangepicker');
			$("input[name=dob_helper]").val('444');
			console.log($("input[name=dob_helper]").val());
		}
		// Иначе печатаем в консоль весь ответ
		else {
			console.log(response)
		}
	});

	// обнуляем поле даты в том случае если у пользователя в профиле не указна дата родждения
	if($('input[id="bee_ajax_dob"]').val() == 0)	{
		$('input[name="dob_helper"]').val('');
	}

	$("#update_profile").submit(function() {
		// убираем маску в поле mobilephone перед сабмитом
		$("input[name=bee_ajax_mobilephone]").val($("input[name=bee_ajax_mobilephone]").mask());

		var drp = $("input[name=dob_helper]").data('daterangepicker');
		$("input[name=bee_ajax_dob]").val(drp.startDate._d.getTime()/1000);
	});
});

