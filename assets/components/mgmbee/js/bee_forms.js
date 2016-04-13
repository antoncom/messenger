$(document).ready(function() {

	// Регулярное выражение для предотвращения ввода ненужных символов
	//no_xss = /[^а-яА-Яa-zA-Z0-9@\-_\.@\s*]/g;
	// разрешается вводить все
	no_xss = /^[.*]/g;

	// profile
	$(document).on('as_complete', document, function (e, d) {
		// для поля mobilephone применяем маску после загрузки поля при помощи AjaxSnippet
		if (d.key == 'b282751839ab5fe8ce666b8864a01dcc4e2d3712') {
			$("input[name=bee_ajax_mobilephone]").mask("(999) 999-9999");
		}
	});

	// После сабмита формы сниппетом AjaxForm делаем различные действия в зависимости от id формы.
	$(document).on('af_complete', function (event, response) {
		var form = response.form;

		// После обновления профайла
		if (form.attr('id') == 'update_profile') {
			// Форматируем дату для отображения значения поля после сабмита
			var drp = $("input[name=bee_ajax_dob]").data('daterangepicker');
			$("input[name=bee_ajax_dob]").val(drp.startDate.format('DD/MM/YYYY'));
		}
		// Иначе печатаем в консоль весь ответ
		else {
			//
		}
	});

	// обнуляем поле даты в том случае если у пользователя в профиле не указна дата родждения
	if ($('input[id="bee_ajax_dob"]').val() == 0) {
		$('input[name="dob_helper"]').val('');
	}

	$("#update_profile").submit(function () {
		// убираем маску в поле mobilephone перед сабмитом
		//$("input[name=bee_ajax_mobilephone]").val($("input[name=bee_ajax_mobilephone]").mask());

		// Превращаем дату в unixtime перед сабмитом
		var drp = $("input[name=bee_ajax_dob]").data('daterangepicker');
		$("input[name=bee_ajax_dob]").val(drp.startDate._d.getTime() / 1000);
	});

	// Для страницы "Профиль". Если телефон был изменен и не прошел подтверждения
	$('#bee_ajax_blogger_phone').on('change', function () {
		$("#send_confirm_code").toggleClass('inactive', false);
		$("#send_confirm_code").toggleClass('disabled', false);

		$("#apply_confirm_code").toggleClass('disabled', true);
		$("#apply_confirm_code").toggleClass('inactive', true);
	});

	$('#bee_ajax_blogger_phone').on('keyup', function () {
		$('input[name=bee_ajax_mobilephone_confirmed]').val('');

		// При редактировании телефона деактивируем кнопки
		var ph_input = $('input[name=bee_ajax_blogger_phone]').val();
		var re = /^\(\d{3}\)\s\d{3}-\d{4}$/;
		if (!re.test(ph_input)) {
			$("#send_confirm_code").toggleClass('inactive', true);
			$("#send_confirm_code").toggleClass('disabled', true);
			$("#send_confirm_code").prop('disabled', false);

			$("#apply_confirm_code").toggleClass('disabled', true);
			$("#apply_confirm_code").toggleClass('inactive', true);
		}
		else {
			$("#send_confirm_code").toggleClass('inactive', false);
			$("#send_confirm_code").toggleClass('disabled', false);
			$("#send_confirm_code").prop('disabled', false);
		}
	});

	$('#bee_ajax_blogger_phone').on('focus', function () {
		$('input[name=bee_ajax_mobilephone_confirmed]').val('');
		// При редактировании телефона деактивируем кнопки
		var ph_input = $('input[name=bee_ajax_blogger_phone]').val();
		var re = /^\(\d{3}\)\s\d{3}-\d{4}$/;
		if (re.test(ph_input)) {
			$("#send_confirm_code").toggleClass('inactive', false);
			$("#send_confirm_code").toggleClass('disabled', false);
			$("#send_confirm_code").prop('disabled', false);
			$("#bee_ajax_blogger_confirmcode").prop('disabled', true);

			$("#apply_confirm_code").toggleClass('disabled', true);
			$("#apply_confirm_code").toggleClass('inactive', true);
			$("#apply_confirm_code").prop('disabled', true);

		}
	});

	// Для графы "Телефон" блогера в профайле
	$("#bee_ajax_blogger_phone").mask("(999) 999-9999", {
		completed: function () {
			// если код валидный
			$("#send_confirm_code").toggleClass('disabled', false);
			$("#send_confirm_code").toggleClass('inactive', false);
		}
	});


	// При нажатии ENTER в поле "Телефон" в профайле инициируем клик по кнопке "Прислать SMS  с кодом
	$("#myplacard").placard({
		explicit: true,
		onAccept: function (helpers) {
			var re = /^"\(\d{3}\)\s\d{3}-\d{4}"$/;
			if (!re.test(helpers.value)) {
				$("#send_confirm_code").trigger("click");
			}
		}
	});

	$(document).click(function () {
		$("#bee_ajax_blogger_confirmcode").prop('disabled', true);
		$('#bee_ajax_blogger_confirmcode').val('');
		$("#myplacard").placard('hide');
	});

	$("#myplacard").click(function (event) {
		//alert('clicked inside');
		event.stopPropagation();
	});


	// Если в поле кода подтверждения нажат Enter
	$("#bee_ajax_blogger_confirmcode").keypress(function (event) {
		if (event.which == 13) {
			event.preventDefault();
			$("#apply_confirm_code").trigger("click");
		}
	});

	// Если код подтверждения введен и валиден
	// то делаем активной кнопку "Подтвердить"
	$("#bee_ajax_blogger_confirmcode").mask("999999", {
		completed: function () {
			$("#apply_confirm_code").toggleClass('disabled', false);
			$("#apply_confirm_code").toggleClass('inactive', false);
		}
	});
	//$("div[data-initialize=placard]").placard({externalClickExceptions: ['#bee_ajax_blogger_phone']});

	// Автофокус; проверка на "пустоту" формы на стр. "Контакты"

	if ($('#contact_form').length > 0) {
		$('#message').bind("change keyup input", function () {
			// Защищаем поля ввода от XSS атак
			$(this).val( $(this).val().replace(no_xss,'') );

			var ifmessage = $('#message').val().length > 0;
			var ifname = $('#name').val().length > 0;
			var ifemail = $('#email').val().length > 0;
			if (ifmessage && ifname && ifemail) {
				$("#contact_form button[type=submit]").toggleClass('disabled', false);
				$("#contact_form button[type=submit]").toggleClass('inactive', false);
			}
		});

		$('input#email').bind("change keyup input", function () {
			// Защищаем поля ввода от XSS атак
			$(this).val( $(this).val().replace(no_xss,'') );

			var ifmessage = $('#message').val().length > 0;
			var ifname = $('#name').val().length > 0;
			var ifemail = $('#email').val().length > 0;
			if (ifmessage && ifname && ifemail) {
				$("#contact_form button[type=submit]").toggleClass('disabled', false);
				$("#contact_form button[type=submit]").toggleClass('inactive', false);
			}
		});

		$('input#name').bind("change keyup input", function () {
			// Защищаем поля ввода от XSS атак
			$(this).val( $(this).val().replace(no_xss,'') );

			var ifmessage = $('#message').val().length > 0;
			var ifname = $('#name').val().length > 0;
			var ifemail = $('#email').val().length > 0;
			if (ifmessage && ifname && ifemail) {
				$("#contact_form button[type=submit]").toggleClass('disabled', false);
				$("#contact_form button[type=submit]").toggleClass('inactive', false);
			}
		});

		// Сброс формы на стр. "Контакты"
		$("#reset_contact").click(function (event) {
			$('#message').val('');
			$('#name').val('');
			$('#email').val('');
		});
	}


	// ** Форма регистрации ** //
	if ($('#register_form').length > 0) {
		$('#register_form input').bind("change keyup input", function () {
			// Защищаем поля ввода от XSS атак
			$(this).val( $(this).val().replace(no_xss,'') );

			var ifname = $('#fullname').val().length > 0;
			var ifemail = $('#email').val().length > 0;
			var ifpassword = $('#password').val().length > 0;
			var ifconf_password = $('#password_confirm').val().length > 0;
			if (ifname && ifemail && ifpassword && ifconf_password) {
				$("#register_form input[type=submit]").toggleClass('disabled', false);
				$("#register_form input[type=submit]").toggleClass('inactive', false);
			}
		});

		$("#reset_register").click(function (event) {
			$('#fullname').val('');
			$('#email').val('');
			$('#password').val('');
			$('#password_confirm').val('');

			$("#register_form input[type=submit]").toggleClass('disabled', true);
			$("#register_form input[type=submit]").toggleClass('inactive', true);
		});

		// Автофокус на стр. "Регистрация"
		$('#register_form input[name=fullname]').focus();
		console.log('register');
	}

	// ** Страница с формой логина ** //
	activateLoginSubmit = function () { // используеся также в capcha2.js
		var iflogin = $('#username').val().length > 0;
		var ifpassword = $('#password').val().length > 0;
		var ifverified = ($('#recaptcha_verified').val() === 'yes');
		if (iflogin && ifpassword && ifverified) {
			$("#login_form_page input[type=submit]").toggleClass('disabled', false);
			$("#login_form_page input[type=submit]").toggleClass('inactive', false);
			$("#login_form_page input[type=submit]").prop('disabled', false);
		}
	};
	if ($('#login_form_page').length > 0) {
		$('#login_form_page input').bind("change keyup input", function () {
			// Защищаем поля ввода от XSS атак
			$(this).val( $(this).val().replace(no_xss,'') );

			activateLoginSubmit();
		});

		$("#reset_login").click(function (event) {
			$('#username').val('');
			$('#password').val('');
			$("#login_form_page input[type=submit]").toggleClass('disabled', true);
			$("#login_form_page input[type=submit]").toggleClass('inactive', true);
		});

		$('#login_form_page #username').focus();
	}


});

/*
	// Автофокус на стр. "Доступ в личный кабинет"
	$('#username').focus();
	if ($('#message').val().length > 0 &&
		$('#name').val().length > 0 &&
		$('#email').val().length > 0 &&
		$('#org_phone').val().length > 0) {
		$("#contact_form button[type=submit]").toggleClass('disabled', false);
		$("#contact_form button[type=submit]").toggleClass('inactive', false);
	}*/



