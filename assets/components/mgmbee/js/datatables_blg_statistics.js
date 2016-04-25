$(document).ready(function() {
	var selected = [];
	var pcData = []; // Данные о выбранных промо-кодах. Используется при удалении.
	var table = $("#blg_statistics").DataTable({
		"processing": true,
		"serverSide": true,
		responsive: true,
		'ajax': {
			'url': '/?id=5843',
			'type': 'POST',
			"data": function ( d ) {
				d.beeComm = $('#bee_comm').val();
				d.beeData = $('#bee_data').val();
				d.beeWhere = $('#bee_where').val();
				// etc
			}
		},
		"rowCallback": function( row, data ) {
			if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
				$(row).addClass('selected');
			}
		},
		dom: 'tpr',
		stateSave: false,
/*		buttons: [
		],*/
		columnDefs: [
			{ "width": "150px", "targets": 4 }
		],
		language: {
			buttons: {
				selectAll: "Отметить все",
				selectNone: "Сброс",
				colvis: "Колонки",
				processing: "Обновление..",
				pageLength: {
					_: "Показать строки: %d"
				}
			},
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


	$('#promo_action_resid').change(function() {
		var id = $(this).children(":selected").attr("id");

		// Enable/Disable "Add promocode" button
		if(id > 0)	{
			table.button( '.addButton' ).enable();
			// Filtering by promo-action id
			$('#bee_where').val('pa_id='+id);
			table.ajax.url( '/?id=5843' ).load();
		}
		else	{
			table.button( '.addButton' ).disable();
			$('#bee_where').val('');
			table.ajax.url( '/?id=5843' ).load();
		}
	});
} );