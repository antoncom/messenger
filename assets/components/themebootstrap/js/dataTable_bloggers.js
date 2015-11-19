$(document).ready(function() {
	var selected = [];
	var table = $("#bloggers").DataTable({
		select: {
		            style: 'os'
		},
		"processing": true,
		"serverSide": true,
		responsive: true,
		'ajax': {
			'url': '/?id=4815',
			'type': 'POST',
			"data": function ( d ) {
				d.beeComm = $('#bee_comm').val();
				d.beeData = $('#bee_data').val();
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
				exportOptions: {
					columns: [ 0, ':visible' ]
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
				table.ajax.url( '/?id=4815' ).load();
				$('#bee_comm').val('');
				$('#bee_data').val('');
			    }
			},
			'selectAll',
			'selectNone',
		],
		columnDefs: [
			{ "width": "250px", "targets": 0 }
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
} );