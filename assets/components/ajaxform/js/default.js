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
					$(document).trigger('af_complete', response);
					if (!response.success) {
						AjaxForm.Message.error(response.message);
						if (response.data) {
							var key, value;
							for (key in response.data) {
								if (response.data.hasOwnProperty(key)) {
									value = response.data[key];
									form.find('.error_' + key).html(value).addClass('error');
									form.find('[name="' + key + '"]').addClass('error');
								}
							}
						}
					}
					else {
						AjaxForm.Message.success(response.message);
						form.find('.error').removeClass('error');
						form[0].reset();

						// MediaPublish addon
						// Обновляем HTML участия в акции
						snip = form.find('input[type="hidden"][name="bee_ajax_snippet"]').val();
						pa_id = form.find('input[type="hidden"][name="bee_ajax_pa_id"]').val();
						switch(snip)	{
							case('pa_join_status'):
								// Callback addon from MediaPublish
								// Обновляем ссылку-активатор "Подключиться к промо-акции"
								$('#accepting_payment').modal('hide');
								asa = $( 'a[data-target="#accepting_payment"][data-whatever="'+pa_id+'"]' ).parent().attr('id');
								var spinner = $('#'+asa).find(".as_spinner");
								spinner.css("display","block");
								$.post("/promo-akczii/", {as_action: asa}, function(response) {
									if (typeof response.output !== "undefined") {
										$('#'+asa).html(response.output);
										spinner.css("display","none");

										// Активируем ссылку "Извлечь промо-код"
										$('a[data-whatever="'+pa_id+'"]').attr('data-target','#extract_promocode');
										$('a[data-whatever="'+pa_id+'"]').toggleClass('disactive',false);
									}
								}, "json");
								// end of callback addon

								break;

							case('extract_promocode'):
								// Обновляем ссылку-активатор "Извлечь промо-код"
								$('#extract_promocode').modal('hide');
								asa = $( 'a[data-target="#extract_promocode"][data-whatever="'+pa_id+'"]' ).parent().attr('id');
								var spinner = $('#'+asa).find(".as_spinner");
								spinner.css("display","block");
								$.post("/promo-akczii/", {as_action: asa}, function(response) {
									if (typeof response.output !== "undefined") {
										$('#'+asa).html(response.output);
										spinner.css("display","none");

										// Обновляем статус подключения к промо-акции
										asa = $( 'span[data-target="#accepting_payment"][data-whatever="'+pa_id+'"]' ).parent().attr('id');
										$.post("/promo-akczii/", {as_action: asa}, function(response) {
											if (typeof response.output !== "undefined") {
												$('#'+asa).html(response.output);
											}
										}, "json");
									}
								}, "json");
								// end of callback addon





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
