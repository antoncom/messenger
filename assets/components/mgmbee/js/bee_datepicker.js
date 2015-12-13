$(document).ready(function() {
	$('input[name="bee_ajax_dob"]').daterangepicker({
		"singleDatePicker": true,
		"showDropdowns": true,
		"autoApply": true,
		"setDate": null,
		"locale": {
			"format": "DD/MM/YYYY",
			"separator": "/",
			"applyLabel": "Применить",
			"cancelLabel": "Сброс",
			"fromLabel": "От",
			"toLabel": "До",
			"customRangeLabel": "Произвольно",
			"daysOfWeek": [
				"Вс",
				"Пн",
				"Вт",
				"Ср",
				"Чт",
				"Пт",
				"Сб"
			],
			"monthNames": [
				"Январь",
				"Февраль",
				"Март",
				"Апрель",
				"Май",
				"Июнь",
				"Июль",
				"Август",
				"Сентябрь",
				"Октярбрь",
				"Ноябрь",
				"Декабрь"
			],
			"firstDay": 1
		}
	},
	function(start, end, label) {
		//
	});

	// обнуляем поле даты в том случае сли у пользователя в профиле не указна дата родждения
	if($('input[id="dob_hidden"]').val() == 0)	{
		$('input[name="bee_ajax_dob"]').val('');
	}


});