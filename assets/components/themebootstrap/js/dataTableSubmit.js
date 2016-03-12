$(document).ready(function() {
	var selected = [];
	var pcData = []; // Данные о выбранных промо-кодах. Используется при удалении.
	var table = $("#example").DataTable({
		select: {
		            style: 'os'
		},
		"processing": true,
		"serverSide": true,
		responsive: true,
		'ajax': {
			'url': '/?id=111',
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
		dom: 'Bfrtip',
		lengthMenu: [
			[ 10, 25, 50, 100, 500, 1000 ],
			[ '10', '25', '50', '100', '500', '1000' ]
		],
		stateSave: false,
		buttons: [
			'pageLength',
			'colvis',
			{
				extend: 'csvHtml5',
				fieldSeparator: ';',
				exportOptions: {
					columns: [ 0, ':visible' ],
				}
			},
			{
			    extend: 'selected',
			    text: 'Удалить отмеченные',
			    action: function ( e, dt, button, config ) {
					pcData = dt;
					// Выводим modal для подтверждения
					// см. определение ниже
			    }
			},
			{
				text: 'Генерировать 10',
				enabled: false,
				className: 'addButton',
				action: function ( e, dt, node, config ) {
					var act_id = $('#promo_action_resid').children(":selected").attr("id");
					$('#bee_comm').val('add');
					$('#bee_data').val('{"pa_id":'+act_id+',"count":10}');
					table.ajax.url( '/?id=111' ).load();
					$('#bee_comm').val('');
					$('#bee_data').val('');
				}
			},
			'selectAll',
			'selectNone',
		],
		columnDefs: [
			{ "width": "150px", "targets": 4 }
		],
		language: {
			buttons: {
				selectAll: "Отметить все",
				selectNone: "Сброс",
				colvis: "Колонки",
				pageLength: {
					_: "Показать строки: %d"
				}
			},
			"processing": "Загрузка...",
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
			table.ajax.url( '/?id=111' ).load();
		}
		else	{
			table.button( '.addButton' ).disable();
			$('#bee_where').val('');
			table.ajax.url( '/?id=111' ).load();
		}
	});

	// Выводим окно подтверждения при удалении промокодов
	// И удаляем в случае подтверждения пользователем
	table.button( 3 ).nodes().attr('data-toggle','modal');
	table.button( 3 ).nodes().attr('data-target','#confirm_deleting');
	$('#delete_promocode').on('click', function () {
		var rows = pcData.rows( { selected: true } ).data();
		for(row in rows)	{
			selected.push( rows[row]['DT_RowId'] );
		}
		$('#bee_comm').val('delete');
		$('#bee_data').val(selected);
		table.ajax.url( '/?id=111' ).load();
		$('#bee_comm').val('');
		$('#bee_data').val('');
	})

} );