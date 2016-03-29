$(document).ready(function() {
	var selected = [];
	var table = $("#activations").DataTable({
		select: {
			style: 'os'
		},
		"processing": true,
		"serverSide": true,
		responsive: true,
		'ajax': {
			'url': '/?id=4841',
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
					table.ajax.url( '/?id=4841' ).load();
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

		}
	});


	$('#promo_action_resid').change(function() {
		var id = $(this).children(":selected").attr("id");

		// Enable/Disable "Add promocode" button
		if(id > 0)	{
			table.button( '.addButton' ).enable();
			// Filtering by promo-action id
			$('#bee_where').val('pa_id='+id);
			table.ajax.url( '/?id=4841' ).load();
		}
		else	{
			table.button( '.addButton' ).disable();
			$('#bee_where').val('');
			table.ajax.url( '/?id=4841' ).load();
		}
	});


	var options = {
		beforeSend: function () {
			$("#progress").show();
			//clear everything
			$("#bar").width('0%');
			$("#message").html("");
			$("#percent").html("0%");
		},
		uploadProgress: function (event, position, total, percentComplete) {
			$("#bar").width(percentComplete + '%');
			$("#percent").html(percentComplete + '%');

		},
		success: function () {
			$("#bar").width('100%');
			$("#percent").html('100%');

		},
		complete: function (response) {

			// custom error extracting
			respArr = response.responseText.split('[Error]');
			if(respArr.length > 1)	{
				customError = respArr[1];
				$("#message_error").html('' +
					'<div class="alert alert-dismissable alert-danger">' +
						'<button type="button" class="close" data-dismiss="alert">×</button>' +
						'' + customError +
					'</div>');
			}

			$("#message").html('' +
				'<div class="alert alert-dismissable alert-success">' +
					'<button type="button" class="close" data-dismiss="alert">×</button>' +
					'' + respArr[0] +
				'</div>');


			table.ajax.url( '/?id=4841' ).load();
			$("#progress").hide();
		},
		error: function () {
			$("#message").html("<font color='red'> ERROR: unable to upload files</font>");

		}
	}

	$("#myForm").ajaxForm(options);
	$("#progress").hide();

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
		$('#bee_where').val("");
		table.ajax.url( '/?id=4841' ).load();
	});

	var startDate = 0;
	var endDate = 0;

	$('#date_from').on( 'keyup click change', function () {
		var i =$(this).attr('id');  // getting column index
		var v =$(this).val();  // getting search input value
		startDate = Date.parse(v)/1000;
		if(startDate == 0 && endDate == 0) return;
		if(endDate > 0)	{
			period = "act_date BETWEEN " + startDate + " AND " + endDate;
		}
		else {
			period = "act_date>" + startDate;
		}

		$('#bee_where').val(period);
		table.ajax.url( '/?id=4841' ).load();
	} );

	$('#date_to').on( 'keyup click change', function () {
		var i =$(this).attr('id');  // getting column index
		var v =$(this).val();  // getting search input value
		endDate = Date.parse(v)/1000;
		if(startDate == 0 && endDate == 0) return;
		if(startDate > 0)	{
			period = "act_date BETWEEN " + startDate + " AND " + endDate;
		}
		else {
			period = "act_date<" + endDate;
		}

		$('#bee_where').val(period);
		table.ajax.url( '/?id=4841' ).load();
	} );
} );