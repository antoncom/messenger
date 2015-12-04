
$(document).ready(function() {
	// bind 'myForm' and provide a simple callback function
	//$('#bonus_method').ajaxForm(function() {
	//	alert("Thank you for your comment!");
	//});

	$('#apply_join_promoaction').on('click', function () {
		$('#bonus_method').submit();
	});

	//$('#bonus_method').submit(function() {
		// inside event callbacks 'this' is the DOM element so we first
		// wrap it in a jQuery object and then invoke ajaxSubmit
		//$(this).ajaxSubmit({
		//	success: function() {
		//		alert("Thank you for your comment!");
		//	}
		//});
		// !!! Important !!!
		// always return false to prevent standard browser submit and page navigation
	//	return true;
	//});

	$('#accepting_payment').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget) // Button that triggered the modal
		var recipient = button.data('whatever') // Extract info from data-* attributes
		// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		var modal = $(this)
		modal.find('#bee_ajax_pa_id').val(recipient);
	})

});
