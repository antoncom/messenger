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
				d.beeWhere = $('#bee_where').val();
				d.beeJoin = $('#bee_join').val();
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
			'selectNone'
		],
		columnDefs: [
			{
				"targets": 0,
				"searchable": true,
				"width": "250px"
			},
			{
				"targets": 1,
				"searchable": true
			},
			{
				"targets": 2,
				"searchable": true
			},
			{
				"targets": 3,
				"searchable": false
			}
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
			"sZeroRecords": "Нет записей"
		}
	});
	// Datepicker
	$( ".datepicker" ).datepicker({
		//dateFormat: "dd-mm-yy",
		dateFormat: "yy-mm-dd",
		showOn: "both",
		showAnim: 'slideDown',
		showButtonPanel: true ,
		autoSize: true,
		buttonImage: "//jqueryui.com/resources/demos/datepicker/images/calendar.gif",
		buttonImageOnly: false,
		buttonText: "Select date",
		closeText: "Clear"
	});
	$(document).on("click", ".ui-datepicker-close", function(){
		$('.datepicker').val("");
		$('#bee_join').val("");
		table.ajax.url( '/?id=4815' ).load();
	});

	var startDate = 0;
	var endDate = 0;

	$('#date_from').on( 'keyup click change', function () {
		var i =$(this).attr('id');  // getting column index
		var v =$(this).val();  // getting search input value
		startDate = Date.parse(v)/1000;
		if(startDate == 0 && endDate == 0) return;
		if(endDate > 0)	{
			period = "viewAct.act_date BETWEEN " + startDate + " AND " + endDate;
		}
		else {
			period = "viewAct.act_date>" + startDate;
		}
		console.log(period);

		$('#bee_join').val(period);
		table.ajax.url( '/?id=4815' ).load();
	} );

	$('#date_to').on( 'keyup click change', function () {
		var i =$(this).attr('id');  // getting column index
		var v =$(this).val();  // getting search input value
		endDate = Date.parse(v)/1000;
		if(startDate == 0 && endDate == 0) return;
		if(startDate > 0)	{
			period = "viewAct.act_date BETWEEN " + startDate + " AND " + endDate;
		}
		else {
			period = "viewAct.act_date<" + endDate;
		}
		console.log(period);
		$('#bee_join').val(period);
		table.ajax.url( '/?id=4815' ).load();
	} );
} );