/**
 * Created by good on 25.03.2016.
 */


var verifyCallback = function(response) {
	alert(response);
};
var widget_login_popover;
var widget_login_page;
var widget_feedback_page;

var onloadCallback = function() {
	// Renders the HTML element with id 'example1' as a reCAPTCHA widget.
	// The id of the reCAPTCHA widget is assigned to 'widget_login_popover'.
	if($('#recapcha_login_popover').length > 0)	{

		$('#recapcha_login_popover').html('');
		widget_login_popover = grecaptcha.render('recapcha_login_popover', {
			'sitekey' : '6LcprhsTAAAAAHG-18ar4fTE6qbwdlQWZfXXQh23',
			'theme' : 'light'
		});
	}
	if($('#recapcha_login_page').length > 0) {

		if($('#recapcha_login_page').html() ==='') {
			widget_login_page = grecaptcha.render(document.getElementById('recapcha_login_page'), {
				'sitekey': '6LcprhsTAAAAAHG-18ar4fTE6qbwdlQWZfXXQh23',
				'theme': 'light'
			});
		}
	}
	if($('#recapcha_feedback_page').length > 0) {
		if($('#recapcha_feedback_page').html() ==='') {
			widget_feedback_page = grecaptcha.render(document.getElementById('recapcha_feedback_page'), {
				'sitekey': '6LcprhsTAAAAAHG-18ar4fTE6qbwdlQWZfXXQh23',
				'theme': 'light'
			});
		}
	}

	if($('#recapcha_register_page').length > 0) {
		if($('#recapcha_register_page').html() ==='') {
			widget_feedback_page = grecaptcha.render(document.getElementById('recapcha_register_page'), {
				'sitekey': '6LcprhsTAAAAAHG-18ar4fTE6qbwdlQWZfXXQh23',
				'theme': 'light'
			});
		}
	}

};

