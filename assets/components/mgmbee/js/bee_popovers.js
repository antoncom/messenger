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

	// *** Запускаем после выполнения AjaxSnippet *** //
	// Инициируем popover для кнопок-активаторов подключения к акции при первой загрузке стр. "Промо-акции"
	$(document).on('as_complete_init_pc_status_options', document, function(e,d) {
		$('.pc_status').popover(pc_status_options);
	});

	// После отработки AjaxSnippet на извлечения промо-кода
	$(document).on('as_complete_extract_promocode_status', document, function(e,d) {
		$('.pc_status').popover(pc_status_options);
		//$('#pcode').text(d.output);
		//$('#extract_promocode').modal('show');
	});

	// После отработки AjaxSnippet на подключение к акции
	$(document).on('as_complete_join_promoaction', document, function(e,d) {
		$('.pc_status').popover(pc_status_options);
	});

	// После отработки AjaxSnippet на подключение к акции -
	// обновляем состояние кнопки-активатора промо-кода на стр. "Промо-акции"
	$(document).on('as_complete_extract_promocode_from_modal', document, function(e,d) {
		console.log('----as_complete_extract_promocode_from_modal----');
		$('.pc_status').popover(pc_status_options);

		// Обновляем состояние кнопки-активатора промо-кода на стр. "Промо-акции"
		// Для этого вначале находим id кнопки-активатора
		var pa_id = $("#"+ d.key).closest(".modal#extract_promocode").attr('data-pa_id');
		var asa = $("a.pc_status[data-pa_id="+pa_id+"]").closest(".ajax-snippet").attr("id");
		var spinner = $('#'+asa).find(".as_spinner");
		spinner.css("display","block");




		// Обновляем состояние кнопки-активатора
		$.post("/promo-akczii/", {as_action: asa, as_complete: "as_complete_extract_promocode_status"}, function(response) {
			if (typeof response.output !== "undefined") {
				$('#'+asa).html(response.output);
				spinner.css("display","none");
				// Инициируем новое содержимое popover на стр. "Промо-акции"
				$(document).trigger("as_complete_extract_promocode_status", response);

			}
		}, "json");


		// Обновляем статус подключения к промо-акции в том случае,
		// если извлекание промо-кода осуществляется со страницы описания акции (из панели "Участие в акции")
/*		asa = $( 'span[data-target="#accepting_payment"][data-whatever="'+pa_id+'"]' ).closest(".ajax-snippet").attr('id');
		console.log("PANEL ASA = " + asa);
		if(asa !== "undefined") {
			$.post("/promo-akczii/", {as_action: asa}, function (response) {
				if (typeof response.output !== "undefined") {
					$('#' + asa).html(response.output);
				}
			}, "json");
		}
		asa = $( 'a[data-pa_id="'+pa_id+'"]' ).closest(".ajax-snippet").attr('id');
		console.log("PANEL ASA = " + asa);
		if(asa !== "undefined") {
			$.post("/promo-akczii/", {as_action: asa}, function (response) {
				if (typeof response.output !== "undefined") {
					$('#' + asa).html(response.output);
				}
			}, "json");
		}*/
	});

	// ** Login Popover ** //
	activateLoginPopoverSubmit = function()	{ // используеся также в capcha2.js
		var iflogin = $('#popover_login_form input[name=username]').val().length > 0;
		var ifpassword = $('#popover_login_form input[name=password]').val().length > 0;
		var ifverified = ($('#popover_login_form #popover_login_recaptcha_verified').val() === 'yes');
		if (iflogin && ifpassword && ifverified) {
			$("#popover_login_submit").toggleClass('disabled', false);
			$("#popover_login_submit").toggleClass('inactive', false);
			$("#popover_login_submit").prop('disabled', false);
		}
	};
	$('.beelogin').on('shown.bs.popover', function(){
		$('#popover_login_form input[name=username]').focus();
		if($('#popover_login_form').length > 0) {
			$('#popover_login_form input').bind("change keyup input", function () {
				// Защищаем поля ввода от XSS атак
				$(this).val( $(this).val().replace(/[^a-zA-Z0-9@\-_\.]/g,'') );

				activateLoginPopoverSubmit();
			});
		}
	});
});