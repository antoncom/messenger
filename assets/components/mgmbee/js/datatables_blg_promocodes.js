$(document).ready(function() {
	var selected = [];
	var table = $("#blogger_promo_codes").DataTable({
		select: {
		            style: 'api'
		},
		"processing": true,
		"serverSide": true,
		responsive: true,
		'ajax': {
			'url': '/?id=5985',
			'type': 'POST',
			"data": function ( d ) {
				d.beeComm = $('#bee_comm').val();
				d.beeData = $('#bee_data').val();
				d.beeWhere = $('#bee_where').val();
				d.beeWhereConstant = $('#bee_where_constant').val();
				// etc
			}
		},
		dom: 'tp',
		language: {
			"processing": "Загрузка...",
			"sZeroRecords": "Нет записей",
			"paginate": {
				"next": "Вперед",
				"previous": "Назад"
			}
		}
	});

	$('#promo_action_resid').change(function() {
		var id = $(this).children(":selected").attr("id");

		if(id > 0)	{
			// Filtering by promo-action id
			$('#bee_where').val('pa_id='+id);
			table.ajax.url( '/?id=5985' ).load();
		}
		else	{
			$('#bee_where').val('');
			table.ajax.url( '/?id=5985' ).load();
		}
	});
} );