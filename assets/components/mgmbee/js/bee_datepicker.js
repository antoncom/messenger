$(document).ready(function() {

	// Ограничиваем выбор дат с 1960 по текущее время минус 16 лет
	var now = new Date();
	var formatted_end_date = "12/31/" + (now.getFullYear() - 16);

	// Инициализация datepicker для страницы "Профайл"
	$('input[name="bee_ajax_dob"]').daterangepicker({
		"singleDatePicker": true,
		"showDropdowns": true,
		"autoApply": true,
		"minDate": "12/01/1960",
		"maxDate": formatted_end_date,
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
});