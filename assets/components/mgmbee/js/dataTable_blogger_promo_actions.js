$(document).ready(function() {
	var selected = [];
	var table = $("#blogger_promo_actions").DataTable({
		select: {
			style: 'api'
		},
		"processing": true,
		"serverSide": true,
		responsive: true,
		'ajax': {
			'url': '/?id=5984',
			'type': 'POST',
			"data": function ( d ) {
				d.beeComm = $('#bee_comm').val();
				d.beeData = $('#bee_data').val();
				d.beeWhere = $('#bee_where').val();
				// etc
			}
		},
		dom: 'tpr',
		stateSave: false,
		language: {
			"processing": "Загрузка...",
			"paginate": {
				"next": "Вперед",
				"previous": "Назад"
			},
			"sZeroRecords": "Нет записей",
			"infoEmpty": "Показано 0 записей.",
			"search": "Поиск:",
			"info": "Показано с _START_ по _END_ из _TOTAL_ записей",
			"infoFiltered": "(отфильтровано из _MAX_ записей)"
		}
	});

	/** Для скрытых таблиц (внутри Tabs) делаем recalc() **/
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		table.columns.adjust().responsive.recalc();
	});

} );


