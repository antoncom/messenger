$(document).ready(function() {
	var selected = [];
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
			        var rows = dt.rows( { selected: true } ).data();
			        for(row in rows)	{
			    	    selected.push( rows[row]['DT_RowId'] );
			        }
			        $('#bee_comm').val('delete');
			        $('#bee_data').val(selected);
				table.ajax.url( '/?id=111' ).load();
				$('#bee_comm').val('');
				$('#bee_data').val('');
			    }
			},
			{
				text: 'Генерировать 10',
				enabled: false,
				className: 'addButton',
				action: function ( e, dt, node, config ) {
					var act_id = $('#promo_action_resid').children(":selected").attr("id");
					$('#bee_comm').val('add');
					$('#bee_data').val('{"promo_action_resid":'+act_id+',"count":10}');
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
				processing: "Обновление.."
			}
		}
	});


	$('#promo_action_resid').change(function() {
		var id = $(this).children(":selected").attr("id");

		// Enable/Disable "Add promocode" button
		if(id > 0)	{
			table.button( '.addButton' ).enable();
			// Filtering by promo-action id
			$('#bee_where').val('parent='+id);
			table.ajax.url( '/?id=111' ).load();
		}
		else	{
			table.button( '.addButton' ).disable();
			$('#bee_where').val('');
			table.ajax.url( '/?id=111' ).load();
		}
	});
} );