//
// Updates "Select all" control in a data table
//
function updateDataTableSelectAllCtrl(table){
	var $table             = table.table().node();
	var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
	var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
	var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

	// If none of the checkboxes are checked
	if($chkbox_checked.length === 0){
		chkbox_select_all.checked = false;
		if('indeterminate' in chkbox_select_all){
			chkbox_select_all.indeterminate = false;
		}

		// If all of the checkboxes are checked
	} else if ($chkbox_checked.length === $chkbox_all.length){
		chkbox_select_all.checked = true;
		if('indeterminate' in chkbox_select_all){
			chkbox_select_all.indeterminate = false;
		}

		// If some of the checkboxes are checked
	} else {
		chkbox_select_all.checked = true;
		if('indeterminate' in chkbox_select_all){
			chkbox_select_all.indeterminate = true;
		}
	}
}

//$.fn.dataTable.TableTools.defaults.aButtons = [ "copy", "csv", "xls" ];

$(document).ready(function (){
	// Array holding selected row IDs
	var rows_selected = [];
	var table = $('#example').DataTable({
		//tableTools: {
		//	"sSwfPath": "/assets/components/datatables/DataTables-1.10.10/swf/copy_csv_xls_pdf.swf"
		////},
		dom: 'Bfrtip',
		select: true,
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
					columns: [ 1, ':visible' ]
				}
			},
			{
				text: 'Удалить',
				action: function ( e, dt, node, config ) {
					console.log(JSON.stringify(rows_selected));
					alert(
						'Row data: '+
						JSON.stringify(rows_selected)
					);
				},
				enabled: false
			},
			'selectAll',
			'selectNone'
		],
		language: {
			buttons: {
				selectAll: "Отметить все",
				selectNone: "Сбросить отметку"
			}
		},
		"processing": true,
		"serverSide": true,
		'ajax': {
			'url': '/?id=111',
			'type': 'GET',
			'data': function ( d ) {
				d.myKey = "rows_selected";
				// d.custom = $('#myInput').val();
				// etc
			}
		},
		"rowCallback": function( row, data ) {
			if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
				$(row).addClass('selected');
			}
		},
		//'columns': [
		//	{ "data": "id" },
		//	{ "data": "pagetitle" },
		//	{ "data": "alias" },
		//	{ "data": "tv.pc_end_date" },
		//	{ "data": "tv.pc_porog" },
		//	{ "data": "tv.pc_bloger" },
		//	{ "data": "tv.pc_activations_count" }
		//],
		'columnDefs': [{
			'targets': 0,
			'searchable': false,
			'orderable': false,
			'className': 'dt-body-center',
			'render': function (data, type, full, meta){
				return '<input type="checkbox">';
			}
		}],
		'order': [[1, 'asc']],
		'rowCallback': function(row, data, dataIndex){
			// Get row ID
			var rowId = data[0];

			// If row ID is in the list of selected row IDs
			if($.inArray(rowId, rows_selected) !== -1){
				$(row).find('input[type="checkbox"]').prop('checked', true);
				$(row).addClass('selected');
			}
		}
	});



	// Handle click on checkbox
	$('#example tbody').on('click', 'input[type="checkbox"]', function(e){
		var $row = $(this).closest('tr');

		// Get row data
		var data = table.row($row).data();

		// Get row ID
		var rowId = data['DT_RowId'];

		// Determine whether row ID is in the list of selected row IDs
		var index = $.inArray(rowId, rows_selected);

		// If checkbox is checked and row ID is not in list of selected row IDs
		if(this.checked && index === -1){
			rows_selected.push(rowId);

			// Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
		} else if (!this.checked && index !== -1){
			rows_selected.splice(index, 1);
		}



		if(this.checked){
			$row.addClass('selected');
		} else {
			$row.removeClass('selected');
		}

		// Update state of "Select all" control
		updateDataTableSelectAllCtrl(table);

		// Prevent click event from propagating to parent
		e.stopPropagation();
	});

	// Handle click on table cells with checkboxes
	$('#example').on('click', 'tbody td, thead th:first-child', function(e){
		$(this).parent().find('input[type="checkbox"]').trigger('click');
	});

	// Handle click on "Select all" control
	$('#example thead input[name="select_all"]').on('click', function(e){
		if(this.checked){
			$('#example tbody input[type="checkbox"]:not(:checked)').trigger('click');
		} else {
			$('#example tbody input[type="checkbox"]:checked').trigger('click');
		}

		// Prevent click event from propagating to parent
		e.stopPropagation();
	});

	// Handle table draw event
	table.on('draw', function(){
		// Update state of "Select all" control
		updateDataTableSelectAllCtrl(table);
	});

	table.on( 'select', function () {
		var selectedRows = table.rows( { selected: true } ).count();
		table.button( 3 ).enable( selectedRows > 0 );
	} );

	// Handle form submission event
	$('#frm-example').on('submit', function(e){
		var form = this;

		// Iterate over all selected checkboxes
		$.each(rows_selected, function(index, rowId){
			// Create a hidden element
			$(form).append(
				$('<input>')
					.attr('type', 'hidden')
					.attr('name', 'id[]')
					.val(rowId)
			);
		});


		rows_selected = $(form).serialize();

		// FOR DEMONSTRATION ONLY

		/*// Output form data to a console
		$('#example-console').text($(form).serialize());
		console.log("Form submission", $(form).serialize());

		// Remove added elements
		$('input[name="id\[\]"]', form).remove();

		// Prevent actual form submission
		e.preventDefault();
		*/
	});

});