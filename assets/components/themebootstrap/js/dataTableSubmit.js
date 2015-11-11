$(document).ready(function() {
	var selected = [];
	var table = $("#example").DataTable({
		"processing": true,
		"serverSide": true,
		'ajax': {
			'url': '/?id=111',
			'type': 'GET',
			"data": function ( d ) {
				d.beeComm = $('#bee_comm').val();
				console.log(d);
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
			[ 10, 25, 50, 100, -1 ],
			[ '10 строк', '25 строк', '50 строк', '100 строк', 'Все' ]
		],
		stateSave: true,
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
				text: 'Удалить отмеченные',
				enabled: true,
				action: function ( e, dt, node, config ) {
					$('#bee_comm').val('delete');
					table.ajax.url( '/?id=111' ).load();
				}
			},
			'selectAll',
			'selectNone'
		],
		language: {
			buttons: {
				selectAll: "Отметить все",
				selectNone: "Сброс"
			}
		}
	});

	$('#example tbody').on('click', 'tr', function () {
		var id = this.id;
		var index = $.inArray(id, selected);

		if ( index === -1 ) {
			selected.push( id );
		} else {
			selected.splice( index, 1 );
		}

		$('#bee_data').val(selected);
		$('#bee_comm').val(""); // удаляем любые комманды с данными для того чтобы они не выполниись от случайного запроса
		$(this).toggleClass('selected');
	} );

	//table.on( 'select', function () {
	//	var selectedRows = table.rows( { selected: true } ).count();
	//	table.button( 3 ).enable( selectedRows > 0 );
	//} );

} );