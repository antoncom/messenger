/**
 * Created by well on 27.02.16.
 */

// общие настройки popover-ов
var popover_general_options = {
	animation: false,
	html: true,
	placement: 'auto',
	title: function () {
		return $(this).next('.popover_header').html();
	},
	content: function(){
		return $(this).next().next('.popover_content').html();
	},
	template: '<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
};

// настройки popover-ов для страницы "Промо-акции" лич. кабинета блогера
var pc_status_options = popover_general_options;

// настройки popover для логина
var login_options = popover_general_options;
login_options.placement = 'auto';


$(document).ready(function() {
	pc_status_options.placement = function() {
		return $(window).width() < 993 ? 'bottom' : 'left';
	};

	$('.beelogin').popover(login_options);
	$('.pc_status').popover(pc_status_options);

	// hide popovers when click anywhere
	$('body').on('click', function (e) {
		$('.pc_status').each(function () {
			//the 'is' for buttons that trigger popups
			//the 'has' for icons within a button that triggers a popup
			if (!$(this).is(e.target) && $(this).has(e.target).length === 0) {
				$(this).popover('hide');
			}
		});
		$('.beelogin').each(function () {
			if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
				$(this).popover('hide');
			}
		});
	});
});