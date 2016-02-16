$(document).ready(function() {
	var selected = [];
	var table = $("#blogger_pcode_activations").DataTable({
		select: {
			style: 'api'
		},
		"searching": false,
		"processing": true,
		"serverSide": true,
		responsive: true,
		'ajax': {
			'url': '/?id=5986',
			'type': 'POST',
			"data": function ( d ) {
				d.beeComm = $('#bee_comm').val();
				d.beeData = $('#bee_data').val();
				d.beeWhere = $('#bee_where').val();
				// etc
			}
		},
		dom: 'tpr',
		"paging": true,
		"pagingType": "simple_numbers",
		"pageLength": 10,
		"language": {
			"paginate": {
				"next": "Вперед",
				"previous": "Назад"
			},
			"processing": "Загрузка...",
		},
		stateSave: false
	});
} );