$(document).ready(function() {
	// profile
	$(document).on('as_complete', document, function(e,d) {
		// для поля mobilephone применяем маску после загрузки поля при помощи AjaxSnippet
		if(d.key == 'b282751839ab5fe8ce666b8864a01dcc4e2d3712')	{
			$("input[name=bee_ajax_mobilephone]").mask("(999) 999-9999");
		}
	});

	// обнуляем поле даты в том случае если у пользователя в профиле не указна дата родждения
	if($('input[id="dob_hidden"]').val() == 0)	{
		$('input[name="bee_ajax_dob"]').val('');
	}

	$("#update_profile").submit(function() {
		// убираем маску в поле movilephone перед сабмитом
		$("input[name=bee_ajax_mobilephone]").val($("input[name=bee_ajax_mobilephone]").mask());

		// преобразуем дату рождения в unix time
		//$utime = Date(year, month, date[, hours, minutes, seconds, ms] )
		utime = Date.parse($("input[name=bee_ajax_dob]").val()) / 1000;
		console.log(utime);
		$("input[name=bee_ajax_dob]").val(utime);
	});


});
