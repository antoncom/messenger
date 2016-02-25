//function fetchInput(inp) {
//	var form_data = inp.split('&');
//	var input     = {};
//
//	$.each(form_data, function(key, value) {
//		var data = value.split('=');
//		input[data[0]] = decodeURIComponent(data[1]);
//	});
//
//	return input;
//}

var AjaxForm = {

	initialize: function(afConfig) {
		if(!jQuery().ajaxForm) {
			document.write('<script src="'+afConfig.assetsUrl+'js/lib/jquery.form.min.js"><\/script>');
		}
		if(!jQuery().jGrowl) {
			document.write('<script src="'+afConfig.assetsUrl+'js/lib/jquery.jgrowl.min.js"><\/script>');
		}

		$(document).ready(function() {
			$.jGrowl.defaults.closerTemplate = '<div>[ '+afConfig.closeMessage+' ]</div>';
		});

		$(document).on('submit', afConfig.formSelector, function(e) {
			$(this).ajaxSubmit({
				dataType: 'json'
				,data: {pageId: afConfig.pageId}
				,url: afConfig.actionUrl
				,beforeSerialize: function(form, options) {
					form.find(':submit').each(function() {
						if (!form.find('input[type="hidden"][name="' + $(this).attr('name') + '"]').length) {
							$(form).append(
								$('<input type="hidden">').attr({
									name: $(this).attr('name'),
									value: $(this).attr('value')
								})
							);
						}
					})
				}
				,beforeSubmit: function(fields, form) {
					if (typeof(afValidated) != 'undefined' && afValidated == false) {
						return false;
					}
					form.find('.error').html('');
					form.find('.error').removeClass('error');
					form.find('input,textarea,select,button').attr('disabled', true);
					return true;
				}
				,success: function(response, status, xhr, form) {
					form.find('input,textarea,select,button').attr('disabled', false);
					response.form=form;

					// Added by MediaPublish
					bee_form = form.serialize();
					function fetchInput(inp) {
						var form_data = inp.split('&');
						var input     = {};

						$.each(form_data, function(key, value) {
							var data = value.split('=');
							input[data[0]] = decodeURIComponent(data[1]);
						});

						return input;
					}
					bee_form_data = fetchInput(bee_form);
					// End

					$(document).trigger('af_complete', response);
					if (!response.success) {
						AjaxForm.Message.error(response.message, true);
						if (response.data) {
							var key, value;
							for (key in response.data) {
								if (response.data.hasOwnProperty(key)) {
									value = response.data[key];
									console.log('.error_' + key + ' error = ' + value);
									form.find('.error_' + key).html(value).addClass('error');
									form.find('[name="' + key + '"]').addClass('error');
								}
							}
						}
						$( '#send_confirm_code' ).button('reset');
						$( '#apply_confirm_code' ).button('reset');
						$( '#send_card_update' ).button('reset');
					}
					else {
						AjaxForm.Message.success(response.message);
						form.find('.error').removeClass('error');
						//form[0].reset();

						// MediaPublish addon
						// Обновляем HTML участия в акции
						snip = form.find('input[type="hidden"][name="bee_ajax_snippet"]').val();
						pa_id = form.find('input[type="hidden"][name="bee_ajax_pa_id"]').val();
						switch(snip)	{
							case('pa_join_status'):
								// Callback addon from MediaPublish
								// Обновляем ссылку-активатор "Подключиться к промо-акции"
								$('#accepting_payment').modal('hide');
								//asa = $( 'a[data-target="#accepting_payment"][data-whatever="'+pa_id+'"]' ).parent().attr('id');
								asa = $( 'a[data-pa_id="'+pa_id+'"]' ).parent().attr('id');
								var spinner = $('#'+asa).find(".as_spinner");
								spinner.css("display","block");
								$.post("/promo-akczii/", {as_action: asa}, function(response) {
									if (typeof response.output !== "undefined") {
										$('#'+asa).html(response.output);
										spinner.css("display","none");

										$('[data-toggle="popover"]').popover();

										// Активируем ссылку "Извлечь промо-код"
										$('a[data-whatever="'+pa_id+'"]').attr('data-target','#extract_promocode');
										$('a[data-whatever="'+pa_id+'"]').toggleClass('disactive',false);

										// Обновляем способ получения бонуса в панели
										asa = $( 'span[data-target="#payment_method"][data-whatever="'+pa_id+'"]' ).parent().attr('id');
										$.post("/promo-akczii/", {as_action: asa}, function(response) {
											if (typeof response.output !== "undefined") {
												$('#'+asa).html(response.output);
											}
										}, "json");
									}
								}, "json");
								// end of callback addon

								break;

							case('extract_promocode'):
								// Обновляем ссылку-активатор "Извлечь промо-код"
								$('#extract_promocode').modal('hide');
								//asa = $( 'a[data-target="#extract_promocode"][data-whatever="'+pa_id+'"]' ).parent().attr('id');
								asa = $( 'a[data-pa_id="'+pa_id+'"]' ).parent().attr('id');
								var spinner = $('#'+asa).find(".as_spinner");
								spinner.css("display","block");
								$.post("/promo-akczii/", {as_action: asa}, function(response) {
									if (typeof response.output !== "undefined") {
										$('#'+asa).html(response.output);
										spinner.css("display","none");

										$('[data-toggle="popover"]').popover();

										// Обновляем статус подключения к промо-акции
										asa = $( 'span[data-target="#accepting_payment"][data-whatever="'+pa_id+'"]' ).parent().attr('id');
										$.post("/promo-akczii/", {as_action: asa}, function(response) {
											if (typeof response.output !== "undefined") {
												$('#'+asa).html(response.output);
											}
										}, "json");
									}
								}, "json");



								break;

							case('send_phone_confirmation'):
								$( '#send_confirm_code' ).button('reset');

								// восстанавливаем phone
								var phone = bee_form_data['bee_ajax_blogger_phone'].split('+').join(' ');
								$("#bee_ajax_blogger_phone").val(phone);
								$("#bee_ajax_blogger_phone").mask("(999) 999-9999");

								break;

							case('confirm_phone'):
								$( '#apply_confirm_code' ).button('reset');

								// ******* ДЛЯ МОДАЛЬНОГО ОКНА ПОДКЛЮЧЕНИЯ К АКЦИИ ****** //
								// Отмечаем галочкой способ доставки бонуса - телефон
								// Скрываем интерфейс ввода/подтверждения номера телефона
								$('#tab_phone').hide();
								$('#bonus_to_phone').click();
								$('#card_pay_method').toggleClass('disabled', false);
								$('#bonus_to_phone').filter('[value=phone]').prop('checked', true);
								$("#bee_ajax_blogger_phone").mask("(999) 999-9999");

								// восстанавливаем phone
								var phone = bee_form_data['bee_ajax_blogger_phone'].split('+').join(' ');
								$("#bee_ajax_blogger_phone").val(phone);
								$("#bee_ajax_blogger_phone").mask("(999) 999-9999");

								// прописываем телефон в radio
								var phone = $("#bee_ajax_blogger_phone").val();

								$('#phone_pay_method .text_phone').html('На баланс: ' + '+7' + phone);

								// ******* ДЛЯ СТРАНИЦЫ ПРОФАЙЛ БЛОГЕРА ******** //
								$('div.placard[data-initialize=placard]').placard('hide');
								// Для страницы "Профайл": если телефон подтвержден
								$('input[name=bee_ajax_mobilephone_confirmed]').val('yes');

								$('.error_mobilephone_confirmed').html('');

								break;

							case('card_update'):
								$('#send_card_update').button('reset');

								// Отмечаем галочкой способ доставки бонуса - телефон
								// Скрываем интерфейс ввода/подтверждения номера телефона
								$('#tab_card').hide();
								$('#bonus_to_card').click();
								$('#phone_pay_method').toggleClass('disabled', false);
								$('#bonus_to_card').filter('[value=card]').prop('checked', true);

								// прописываем карту в radio
								var card = bee_form_data['bee_ajax_number'].split('+').join(' ');
								$('#bee_ajax_card_number').val(card);
								$("#bee_ajax_card_number").mask("9999 9999 9999 9999");
								var regex = /\s\d{4}(?=\s)/g;
								$('#card_pay_method .text_card').html('На карту Билайн ' + $('#bee_ajax_card_number').val().replace(regex, ' xxxx'));

								// восстанавливаем name и expiry
								var name = bee_form_data['bee_ajax_name'].split('+').join(' ');
								$('#bee_ajax_card_name').val(name);
								var expiry = bee_form_data['bee_ajax_expiry'].split('+').join(' ');
								$('#bee_ajax_card_expiry').val(expiry);

								break;

							case('update_profile'):
								// Обновляем способ поле mobilephone
								//asa = 'b282751839ab5fe8ce666b8864a01dcc4e2d3712';
								//$.post("/moj-profil.html", {as_action: asa}, function(response) {
								//	if (typeof response.output !== "undefined") {
								//		$('#'+asa).html(response.output);
								//		$("input[name=bee_ajax_mobilephone]").mask("(999) 999-9999");
								//	}
								//}, "json");

									//console.log('UPATED');
								break;

							default: ;
						}
					}
				}
			});
			e.preventDefault();
			return false;
		});

		$(document).on('reset', afConfig.formSelector, function(e) {
			$(this).find('.error').html('');
			AjaxForm.Message.close();
		});
	}

};


AjaxForm.Message = {
	success: function(message, sticky) {
		if (message) {
			if (!sticky) {sticky = false;}
			$.jGrowl(message, {theme: 'af-message-success', sticky: sticky});
		}
	}
	,error: function(message, sticky) {
		if (message) {
			if (!sticky) {sticky = false;}
			$.jGrowl(message, {theme: 'af-message-error', sticky: sticky});
		}
	}
	,info: function(message, sticky) {
		if (message) {
			if (!sticky) {sticky = false;}
			$.jGrowl(message, {theme: 'af-message-info', sticky: sticky});
		}
	}
	,close: function() {
		$.jGrowl('close');
	}
};
