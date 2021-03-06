$(document).ready(function() {
	var selected = [];
	var table = $("#pcode_activations").DataTable({
		select: {
			style: 'api'
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		responsive: true,
		'ajax': {
			'url': '/?id=5920',
			'type': 'POST',
			"data": function ( d ) {
				d.beeComm = $('#bee_comm').val();
				d.beeData = $('#bee_data').val();
				d.beeWhere = $('#bee_where').val();
				// etc
			}
		},
		dom: 'tpr',
		"paging": true,
		"pagingType": "simple_numbers",
		"pageLength": 10,
		"language": {
			"paginate": {
				"next": "Вперед",
				"previous": "Назад"
			},
			"processing": "Загрузка...",
			buttons: {
				selectAll: "Отметить все",
				selectNone: "Сброс",
				colvis: "Колонки",
				pageLength: {
					_: "Показать строки: %d"
				}
			},
			"sZeroRecords": "Нет записей"
		},
		stateSave: false
	});
	//table.on( 'order.dt search.dt', function () {
	//	table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	//		cell.innerHTML = i+1;
	//	} );
	//} ).draw();

} );