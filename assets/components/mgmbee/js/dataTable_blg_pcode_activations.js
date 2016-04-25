$(document).ready(function() {
	var selected = [];
	var table = $("#blogger_pcode_activations").DataTable({
		select: {
			style: 'api'
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		"responsive": true,
		"ajax": {
			'url': '/?id=5986',
			'type': 'POST',
			"data": function ( d ) {
				d.beeComm = $('#bee_comm').val();
				d.beeData = $('#bee_data').val();
				d.beeWhere = $('#bee_where').val();
				// etc
			}
		},
		"dom": 'tpi',
		"paging": true,
		"pagingType": "simple_numbers",
		"pageLength": 10,
		"language": {
			"paginate": {
				"next": "Вперед",
				"previous": "Назад"
			},
			"processing": "Загрузка...",
			"sZeroRecords": "Нет записей",
			"infoEmpty": "Показано 0 записей.",
			"search": "Поиск:",
			"info": "Показано с _START_ по _END_ из _TOTAL_ записей",
			"infoFiltered": "(отфильтровано из _MAX_ записей)"
		},
		stateSave: false
	});

	/** Для скрытых таблиц (внутри Tabs) делаем recalc() **/
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		table.columns.adjust().responsive.recalc();
	});
} );