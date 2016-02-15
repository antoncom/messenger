$(document).ready(function() {
	var selected = [];
	var pcData = []; // Данные о выбранных промо-кодах. Используется при удалении.
	var table = $("#blg_statistics").DataTable({
		select: {
			style: 'os'
		},
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
		dom: 'Bfrtip',
		lengthMenu: [
			[ 10, 25, 50, 100 ],
			[ '10', '25', '50', '100' ]
		],
		stateSave: false,
		buttons: [
			'pageLength',
			{
				extend: 'selectAll',
				text: 'Отметить все'
			},
			{
				extend: 'selectNone',
				text: 'Сброс'
			},
			{
				extend: 'pdfHtml5',
				text: 'PDF',
				exportOptions: {
					modifier: {
						selected: true,
						search: 'applied',
						order: 'applied'
					},
					columns: ':visible'
				}
			}
		],
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