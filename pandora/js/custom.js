$(document).ready(function () {

	$('body').on('click','#classic_login_form_button',function(e){
		e.preventDefault();

		$('#user_email').parent().find('.form_warning_msg').removeClass('form_warning_msg_show');
		$('#user_email').parent().find('.form_warning_msg_email_format').removeClass('form_warning_msg_show');

		var user_email = $('#user_email').val();
		var user_password = $('#user_password').val();

		var continue_flag = 0;

		if(user_email.length == 0){
			$('#user_email').parent().find('.form_warning_msg').addClass('form_warning_msg_show');
			var continue_flag = 0;
		}
		else{
			$('#user_email').parent().find('.form_warning_msg').removeClass('form_warning_msg_show');
			var continue_flag = continue_flag + 1;
		}

		if(user_password.length == 0){
			$('#user_password').parent().find('.form_warning_msg').addClass('form_warning_msg_show');
			var continue_flag = 0;
		}
		else{
			$('#user_password').parent().find('.form_warning_msg').removeClass('form_warning_msg_show');
			var continue_flag = continue_flag + 1;
		}

		if(continue_flag > 1){
			//console.log('we can go');
			$('#classic_login_form').submit();
		}
		else{
			//console.log('errors found, can\'t continue');
		}

	});



	$('#forgot_password_button').on('click', function (e) {
		e.preventDefault();
		swal({
			html:
				'<div class="custom_popup">' +
					'<div class="popup_title">Forgot password</div>' +
					'<div class="forgotten_password_form">' +
						'<form action="index.php" method="POST" id="forgotten_password_form">' +
							'<div class="form-line">' +
								'<input type="text" name="username">' +
								'<label for="username">Username / e-mail adress</label>' +
							'</div>' +
							'<button type="submit" id="forgotten_password_form_button" class="form_button" form="forgotten_password_form" value="Anmelden">Reset password</button>' +
						'</form>' +
					'</div>' +
				'</div>',
			showCloseButton: true,
			showCancelButton: false,
			showConfirmButton: false,
			animation: true
		});
		
	});


	$(".lang_value_input").focus(function() {
	}).blur(function() {
		var lang_val_id = $(this).attr('id');
		var lang_val_class = $(this).attr('class');
		var lang_val = $(this).val();
		var source_lang_val = $('#source_'+lang_val_id).val();

		if(lang_val != source_lang_val){

			if(lang_val_class == 'lang_value_input lang_field'){
				$.ajax({
					url: "includes/pages/translations/ajax.php",
					type: 'POST',
					data: {
						lang_val: lang_val,
						lang_val_id_lo: lang_val_id
					},
					cache: false,
					success: function(html){
						$('#source_'+lang_val_id).val(lang_val);
						$("#lang_result").empty();
						$("#lang_result").append(html);
						$("#lang_result").animate({
							right: "40px",
						}, 500,'swing', function() {
							// Animation complete.
							$(this).delay(1500).animate({
								right: "-240px",
							});
						});
					}
				});
			}
			else{
				$.ajax({
					url: "includes/pages/translations/ajax.php",
					type: 'POST',
					data: {
						lang_val: lang_val,
						lang_val_id: lang_val_id
					},
					cache: false,
					success: function(html){
						if(html != 'Same, not saved'){
							$('#source_'+lang_val_id).val(lang_val);
							$("#lang_result").empty();
							$("#lang_result").append(html);
							$("#lang_result").animate({
								right: "40px",
							}, 500,'swing', function() {
								// Animation complete.
								$(this).delay(1500).animate({
									right: "-240px",
								});
							});
						}			
					}
				});
			}

		}
	});


	$('.add_new_lang_button').on('click', function (e) {
		e.preventDefault();
		swal({
			html:
				'<div class="custom_popup">' +
					'<div class="popup_title">Add new language</div>' +
					'<div class="forgotten_password_form">' +
						'<div id="forgotten_password_form">' +
							'<div class="form-line">' +
								'<input type="text" name="new_lang_value" id="new_lang_value">' +
								'<label for="new_lang_value">Language name</label>' +
							'</div>' +
							'<div class="form-line">' +
								'<input type="text" name="new_iso_value" id="new_iso_value">' +
								'<label for="new_iso_value">Language ISO (example: DE, EN, IT)</label>' +
							'</div>' +
							'<button type="submit" id="add_new_language_button" class="form_button" form="forgotten_password_form">Add language</button>' +
						'</div>' +
					'</div>' +
				'</div>',
			showCloseButton: true,
			showCancelButton: false,
			showConfirmButton: false,
			animation: true
		});
	});


	$('.remove_lang_button').on('click', function (e) {
		e.preventDefault();

		$.ajax({
			url: "includes/pages/translations/ajax.php",
			type: 'POST',
			data: {
				get_langs_to_remove: true
			},
			cache: false,
			success: function(html){
				swal({
					html:
						'<div class="custom_popup">' +
							'<div class="popup_title">Remove language</div>' +
							'<div class="forgotten_password_form">' +
								'<div id="forgotten_password_form">' +
									'<div class="form-line">' +
										html +
									'</div>' +
								'</div>' +
							'</div>' +
						'</div>',
					showCloseButton: true,
					showCancelButton: false,
					showConfirmButton: false,
					animation: true
				});
			}
		});	
	});


	$('body').on('click','.lang_to_remove_button',function(e){
		e.preventDefault();

		var id_to_remove = $(this).attr('id').split('remove_lang_');
		var lang_id_to_remove = id_to_remove[1];

		
		$(".lang_remove_validation").animate({
			height: 0
		}, 500, function() {
			$(".lang_remove_validation").hide();

			$("#lang_remove_validation_"+lang_id_to_remove).show();
			$("#lang_remove_validation_"+lang_id_to_remove).animate({
				height: 150
			}, 500, function() {
				// Animation complete.
			});
			
		});

		
	});


	$('body').on('click','.lang_remove_validation_button',function(e){
		e.preventDefault();

		var id_to_remove = $(this).attr('id').split('lang_remove_validation_button_');
		var lang_id_to_remove = id_to_remove[1];

		var verification_text = $('#lang_remove_validation_field_'+lang_id_to_remove).val();

		if(verification_text == 'remove'){
			$.ajax({
				type: 'POST',
				url: "/pandora/includes/pages/translations/ajax.php",
				data: {
					language_to_remove: lang_id_to_remove
				},
				cache: false,
				success: function(html){
					swal.close();
					location.reload();
				}
			});
		}
		else{
			$('#lang_remove_validation_error_'+lang_id_to_remove).show();
			$('#lang_remove_validation_error_'+lang_id_to_remove).animate({
				opacity: 1
			}, 500, function() {
				setTimeout(
					function() {
						$('#lang_remove_validation_error_'+lang_id_to_remove).animate({
							opacity: 0
						}, 500, function() {
							$('#lang_remove_validation_error_'+lang_id_to_remove).hide();
						});
					}, 1500);
			});
		}		
	});


	$('body').on('click','#add_new_language_button',function(e){
		e.preventDefault();

		var new_lang_value = $('#new_lang_value').val();
		var new_iso_value = $('#new_iso_value').val();

		$.ajax({
			url: "includes/pages/translations/ajax.php",
			type: 'POST',
			data: {
				new_lang_name: new_lang_value,
				new_lang_iso: new_iso_value
			},
			cache: false,
			success: function(html){
				location.reload();
			}
		});
	});


	$('body').on('click','.arrow_step_back',function(e){
		
		var step_to_dec = $(this).attr('id');
		var step_to = step_to_dec.substring(6);
		var step_from = $(this).parent().attr('id');

		var step_2_selection = $('.step_2_selection.selection_active').attr('id');
		var step_3_selection = $('.step_3_selection.selection_active').attr('id');

		if(step_to == 'new_repository_item_step_8'){
			if(step_2_selection == 'step_2_safety_datasheet'){
				var prefix_1 = 's';
			}
			else{
				var prefix_1 = 'p';
			}
			if(step_3_selection == 'step_3_material_datasheet'){
				var prefix_2 = 'm';
			}
			else{
				var prefix_2 = 'b';
			}
			var new_file_name = prefix_1+prefix_2+Date.now()+".pdf";

			window.fileUploader = new Dropzone("div#pdf_documents", { 
				url: "/pandora/classes/upload.php",
				paramName: "file",
				dictDefaultMessage: '<div class="dropzone_label"><i class="pe-7s-cloud-upload"></i>Datei für Upload hier her ziehen</div>',
				thumbnailWidth: 80,
				createImageThumbnails: false,
				acceptedFiles: 'application/pdf',
				addRemoveLinks: true,
				dictInvalidFileType: 'Wrong file, only PDFs are allowed',
				dictRemoveFile: 'Remove file',
				maxFiles: 1,
				renameFilename: function renameFilename(file) {
					//console.log('name from fileUploader: '+new_file_name);
					return new_file_name;
				},
				init: function() {
					this.on("addedfile", function() {
						if (this.files[1]!=null){
							this.removeFile(this.files[0]);
						}
					});

					this.on("success", function() {
						$('#temp_file_url').val(new_file_name);
						$('#new_repository_links_new').val('http://ttal.loc/'+new_file_name);
						//console.log('file uploaded: '+new_file_name);
					});

					this.on("removedfile", function(file) {
						$.ajax({
							type: 'POST',
							url: "/pandora/classes/upload.php",
							data: 'file_remove='+new_file_name,
							cache: false,
							success: function(html){
								//console.log(html);
								$('#temp_file_url').val('');
								$('#new_repository_links_new').val('');
							}
						});
					});
				}
			});

		}
		else{
			if(typeof(fileUploader) !== 'undefined'){
				//fileUploader.off();
				fileUploader.files = [];
				fileUploader.destroy();
			}
		}

		$('#'+step_from).removeClass('current_step');

		$('#'+step_from).animate({
				opacity: 0,
			}, 250, function() {
				$('#'+step_from).hide();
				$('#'+step_to).show();
				$('#'+step_to).animate({
						opacity: 1
				}, 250);
				$('#'+step_to).addClass('current_step');
		});
	});

	var repository_popup_content = 
	'<div class="custom_popup new_repository_item_popup_container">' +
		'<button type="button" class="swal2-close" aria-label="Close this dialog" style="display: flex;">×</button>' +
		'<div id="new_repository_item_step_1" class="step_container">' +
			'<div class="popup_title">Name</div>' +
			'<div class="popup_description">Tragen SIe nachfolgend den Namen des SDB oder des PDB ein. Dieser Name ist im Front End beim Kunden sichtbar.</div>' +
			'<div class="new_repository_item_form">' +
				'<div id="new_repository_item_form">' +
					'<div class="form-line">' +
						'<input type="text" name="new_repository_item_name" id="new_repository_item_name">' +
					'</div>' +
					'<div class="form_warning_msg">' +
						'This field can not be empty' +
					'</div>' +
					'<button type="submit" class="form_button goto_step_2">Speichern</button>' +
				'</div>' +
			'</div>' +
		'</div>' +

		'<div id="new_repository_item_step_2" class="step_container">' +
			'<div id="go_to_new_repository_item_step_1" class="arrow_step_back"><i class="pe-7s-angle-left-circle"></i></div>' +
			'<div class="popup_title">Art</div>' +
			'<div class="popup_description">Wählen Sie nachfolgend die Art des Datenblattes. Die Art ist sichtbar im Front End als Tag.</div>' +
			'<div class="new_repository_item_form">' +
				'<div id="new_repository_item_form">' +
					'<div class="step_2_selection_buttons">' +
						'<div id="step_2_safety_datasheet" class="step_2_selection selection_active"><strong>SICHERHEITS</strong>Datenblatt</div>' +
						'<div id="step_2_product_datasheet" class="step_2_selection"><strong>PRODUKT</strong>Datenblatt</div>' +
						'<div id="step_2_gasgemisch_datasheet" class="step_2_selection"><strong>GASGEMISCH</strong>Datenblatt</div>' +
					'</div>' +
					'<button type="submit" class="form_button goto_step_3">Speichern</button>' +
				'</div>' +
			'</div>' +
		'</div>' +

		'<div id="new_repository_item_step_3" class="step_container">' +
			'<div id="go_to_new_repository_item_step_2" class="arrow_step_back"><i class="pe-7s-angle-left-circle"></i></div>' +
			'<div class="popup_title">Typ</div>' +
			'<div class="popup_description">Wählen Sie nachfolgend die Typ des Datenblattes. Datenblätter werden nach Typ getrennt in der Tabelle in der Sektion “Übersicht” dargestellt.</div>' +
			'<div class="new_repository_item_form">' +
				'<div id="new_repository_item_form">' +
					'<div class="step_3_selection_buttons">' +
						'<div id="step_3_material_datasheet" class="step_3_selection selection_active">Stoff</div>' +
						'<div id="step_3_brandgas_datasheet" class="step_3_selection">Markengas</div>' +
					'</div>' +
					'<button type="submit" class="form_button goto_step_4">Speichern</button>' +
				'</div>' +
			'</div>' +
		'</div>' +

		'<div id="new_repository_item_step_4" class="step_container">' +
			'<div id="go_to_new_repository_item_step_3" class="arrow_step_back"><i class="pe-7s-angle-left-circle"></i></div>' +
			'<div class="popup_title">Verbunden mit</div>' +
			'<div class="popup_description">Geben Sie nachfolgend den Namen des Datenblattes ein und wählen Sie anschließend aus. Damit ein Produktdatenblatt auf ein Sicherheitsdatenblatt zeigen kann, muss das SDB bereits vorher hochgeladen worden sein. Es handelt sich um eine EINWEG-Verknüpfung</div>' +
			'<div class="new_repository_item_associates_form">' +
				'<div id="new_repository_item_associates_form">' +
					'<div class="form-line">' +
						'<input type="text" name="new_repository_item_associates" id="new_repository_item_associates">' +
						'<input type="text" name="new_repository_item_associates_final" id="new_repository_item_associates_final">' +
						'<i class="pe-7s-search step_4_search_icon"></i>' +
					'</div>' +
					'<div id="relations_selected"></div>' +
					'<button type="submit" class="form_button goto_step_5">Speichern</button>' +
				'</div>' +
			'</div>' +
		'</div>' +

		'<div id="new_repository_item_step_5" class="step_container">' +
			'<div id="go_to_new_repository_item_step_4" class="arrow_step_back"><i class="pe-7s-angle-left-circle"></i></div>' +
			'<div class="popup_title">Änderungsdatum</div>' +
			'<div class="popup_description">Geben Sie nachfolgend das Änderungsdatum an. Dieses Datum ist bei jedem Datenblatt im Front End sichtbar</div>' +
			'<div class="new_repository_item_date_form">' +
				'<div id="new_repository_item_date_form">' +
					'<div class="form-line">' +
						'<input type="text" data-toggle="datepicker" id="new_repository_item_date">' +
						'<i class="pe-7s-date step_5_calendar_icon"></i>' +
					'</div>' +
					'<div class="form_warning_msg">' +
						'Incorrect date' +
					'</div>' +
					'<button type="submit" class="form_button goto_step_6">Speichern</button>' +
				'</div>' +
			'</div>' +
		'</div>' +

		'<div id="new_repository_item_step_6" class="step_container">' +
			'<div id="go_to_new_repository_item_step_5" class="arrow_step_back"><i class="pe-7s-angle-left-circle"></i></div>' +
			'<div class="popup_title">Materialnummer</div>' +
				'<div class="popup_description">Geben Sie nachfolgend die Materialnummer ein. Diese ist im Front End nicht ersichtlich, der Kunde kann jedoch trotzdem mit der Materialnummer nach dem Datenblattsuchen.</div>' +
			'<div class="new_repository_materialnumber_form">' +
				'<div id="new_repository_materialnumber_form">' +
					'<div class="form-line">' +
						'<input type="text" name="new_repository_materialnumber" id="new_repository_materialnumber">' +
					'</div>' +
					'<div class="form_warning_msg">' +
						'This field can not be empty' +
					'</div>' +
					'<button type="submit" class="form_button goto_step_7">Speichern</button>' +
				'</div>' +
			'</div>' +
		'</div>' +

		'<div id="new_repository_item_step_7" class="step_container">' +
			'<div id="go_to_new_repository_item_step_6" class="arrow_step_back"><i class="pe-7s-angle-left-circle"></i></div>' +
			'<div class="popup_title">Tag Beschreibung</div>' +
				'<div class="popup_description">Geben Sie nachfolgend die Tags an. Trennen SIe die Tags mit einem Komma. Tags werden im Front End nicht angezeigt. Kunden können trotzdem danach suchen.</div>' +
			'<div class="new_repository_tags_form">' +
				'<div id="new_repository_tags_form">' +
					'<div class="form-line">' +
						'<textarea rows="5" name="new_repository_tags" id="new_repository_tags"></textarea>' +
					'</div>' +
					'<button type="submit" class="form_button goto_step_8">Speichern</button>' +
				'</div>' +
			'</div>' +
		'</div>' +

		'<div id="new_repository_item_step_8" class="step_container">' +
			'<div id="go_to_new_repository_item_step_7" class="arrow_step_back"><i class="pe-7s-angle-left-circle"></i></div>' +
			'<div class="popup_title">Hochladen</div>' +
				'<div class="popup_description">Sie können das Datenblatt nachfolgend über den Upload aktualisieren.</div>' +
			'<div class="new_repository_links_files">' +
				'<div id="new_repository_links_files">' +
					'<div class="form-line">' +
						'<div id="pdf_documents" class="dropzone">' +
						'</div>' +
					'</div>' +
					'<button type="submit" class="form_button goto_step_9">Speichern</button>' +
					'<input type="text" name="temp_file_url" id="temp_file_url" style="display:none">' +
				'</div>' +
			'</div>' +
		'</div>' +

	'</div>';


	$('.add_new_repository_item_button').on('click', function (e) {
		e.preventDefault();

		swal({
			html:repository_popup_content,
			//showCloseButton: true,
			showCloseButton: false,
			showCancelButton: false,
			showConfirmButton: false,
			animation: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			customClass: 'repository_add_popup'
		});

		$('#new_repository_item_step_1').addClass('current_step');
		
	});


	$('body').on('click','.goto_step_2',function(e){
		e.preventDefault();

		//collecting data
		var new_repository_item_name = $('#new_repository_item_name').val();
		//console.log('step 1: '+new_repository_item_name);

		//validate previous step
		if(new_repository_item_name.length == 0){
			$('#new_repository_item_name').addClass('field_warning');
			$(this).parent().find('.form_warning_msg').addClass('form_warning_msg_show');
		}
		else{
			$('#new_repository_item_name').removeClass('field_warning');
			$(this).parent().find('.form_warning_msg').removeClass('form_warning_msg_show');
			
			$('#new_repository_item_step_1').removeClass('current_step');
			$('#new_repository_item_step_1').addClass('step_done');

			$('#new_repository_item_step_1').animate({
				opacity: 0,
			}, 250, function() {
				$('#new_repository_item_step_1').hide();
				$('#new_repository_item_step_2').show();
				$('#new_repository_item_step_2').animate({
						opacity: 1
					}, 250);

				$('#new_repository_item_step_2').addClass('current_step');
			});
		}
	});


	$('body').on('click','.step_2_selection',function(e){
		if(!$(this).hasClass('selection_active')){
			$('.step_2_selection').removeClass('selection_active');
			$(this).addClass('selection_active');
		}
	});


	$('body').on('click','.goto_step_3',function(e){
		e.preventDefault();

		//collecting data
		var step_2_selection = $('.step_2_selection.selection_active').attr('id');
		//console.log('step 2: '+step_2_selection);

		$('#new_repository_item_step_2').removeClass('current_step');
		$('#new_repository_item_step_2').addClass('step_done');

		$('#new_repository_item_step_2').animate({
			opacity: 0,
		}, 250, function() {
			$('#new_repository_item_step_2').hide();
			$('#new_repository_item_step_3').show();
			$('#new_repository_item_step_3').animate({
					opacity: 1
				}, 250);

			$('#new_repository_item_step_3').addClass('current_step');
		});

	});


	$('body').on('click','.step_3_selection',function(e){
		if(!$(this).hasClass('selection_active')){
			$('.step_3_selection').removeClass('selection_active');
			$(this).addClass('selection_active');
		}
	});


	$('body').on('click','.goto_step_4',function(e){
		e.preventDefault();

		//collecting data
		var step_3_selection = $('.step_3_selection.selection_active').attr('id');
		//console.log('step 3: '+step_3_selection);

		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/repository/ajax.php",
			data: 'get_relations=add',
			cache: false,
			success: function(html){
				var repositories = html.split('||');

				$('#new_repository_item_associates').autoComplete({
						minChars: 2,
						source: function(term, suggest){
							term = term.toLowerCase();
							var choices = repositories;
							var suggestions = [];
							for (i=0;i<choices.length;i++)
								if (~choices[i].toLowerCase().indexOf(term)) suggestions.push(choices[i]);
							suggest(suggestions);
						},
						renderItem: function (item, search){
							return '<div class="autocomplete-suggestion" data-name="'+item+'">'+item+'</div>';
						},
						onSelect: function(e, term, item){
							$('#relations_selected').css('display','flex');
							
							var current_relations = $('#new_repository_item_associates_final').val();

							//console.log('current_relations: '+current_relations);
							//console.log('new name: '+item.data('name'));
							//console.log('check result: '+current_relations.indexOf(item.data('name')));

							if(current_relations.indexOf(item.data('name')) == -1){
								$('#new_repository_item_associates_final').val(current_relations+item.data('name')+',');
								$('#relations_selected').append('<div class="relation_selected"><span>'+item.data('name')+'</span><i class="pe-7s-close-circle"></i></div>');
							}
						}
				});
			}
		});

		$('#new_repository_item_step_3').removeClass('current_step');
		$('#new_repository_item_step_3').addClass('step_done');

		$('#new_repository_item_step_3').animate({
			opacity: 0,
		}, 250, function() {
			$('#new_repository_item_step_3').hide();
			$('#new_repository_item_step_4').show();
			$('#new_repository_item_step_4').animate({
					opacity: 1
				}, 250);

			$('#new_repository_item_step_4').addClass('current_step');
		});

	});


	$('body').on('click','.goto_step_5',function(e){
		e.preventDefault();

		//collecting data
		var step_4_selection = $('#new_repository_item_associates_final').val();
		//console.log('step 4: '+step_4_selection);

		$('#new_repository_item_step_4').removeClass('current_step');
		$('#new_repository_item_step_4').addClass('step_done');

		$('#new_repository_item_step_4').animate({
			opacity: 0,
		}, 250, function() {
			$('#new_repository_item_step_4').hide();
			$('#new_repository_item_step_5').show();
			$('[data-toggle="datepicker"]').datepicker({
				language: 'de-DE',
				format: 'dd.mm.yyyy',
				autoPick: true
			});

			$('#new_repository_item_step_5').animate({
					opacity: 1
				}, 250);

			$('#new_repository_item_step_5').addClass('current_step');
		});
	});


	$('body').on('click','.goto_step_6',function(e){
		e.preventDefault();

		//collecting data
		var step_5_selection = $('#new_repository_item_date').val();

		//validate previous step
		if(step_5_selection.length == 0){
			$('#new_repository_item_date').addClass('field_warning');
			$(this).parent().find('.form_warning_msg').addClass('form_warning_msg_show');
		}
		else{
			//validate date
			var date_validated = step_5_selection.split('.');
			//console.log('array items: '+date_validated.length);
			//console.log('item 0: ' + $.isNumeric(date_validated[0]));
			//console.log('item 1: ' + $.isNumeric(date_validated[1]));
			//console.log('item 2: ' + $.isNumeric(date_validated[2]));
			if(
				(date_validated.length == 3) && 
				($.isNumeric(date_validated[0])) && 
				($.isNumeric(date_validated[1])) && 
				($.isNumeric(date_validated[2])) &&
				(date_validated[0] > 0) && (date_validated[0] <= 31) &&
				(date_validated[1] > 0) && (date_validated[1] <= 12) &&
				(date_validated[2] > 1900)
			){
				//console.log('step 5: '+step_5_selection)+' validate result: '+date_validated;

				$('#new_repository_item_date').removeClass('field_warning');
				$(this).parent().find('.form_warning_msg').removeClass('form_warning_msg_show');

				$('#new_repository_item_step_5').removeClass('current_step');
				$('#new_repository_item_step_5').addClass('step_done');

				$('#new_repository_item_step_5').animate({
					opacity: 0,
				}, 250, function() {
					$('#new_repository_item_step_5').hide();
					$('#new_repository_item_step_6').show();
					$('#new_repository_item_step_6').animate({
						opacity: 1
					}, 250);

					$('#new_repository_item_step_6').addClass('current_step');
				});
			}
			else{
				$('#new_repository_item_date').addClass('field_warning');
				$(this).parent().find('.form_warning_msg').addClass('form_warning_msg_show');
			}
		}
	});


	$('body').on('click','.goto_step_7',function(e){
		e.preventDefault();

		//collecting data
		var step_6_selection = $('#new_repository_materialnumber').val();
		//console.log('step 6: '+step_6_selection);

		$('#new_repository_item_step_6').removeClass('current_step');
		$('#new_repository_item_step_6').addClass('step_done');

		$('#new_repository_item_step_6').animate({
			opacity: 0,
		}, 250, function() {
			$('#new_repository_item_step_6').hide();
			$('#new_repository_item_step_7').show();

			$('[name=new_repository_tags]').tagify({
				duplicates : false,
				delimiters: ","
			});
					
			$('#new_repository_item_step_7').animate({
				opacity: 1
			}, 250);

			$('#new_repository_item_step_7').addClass('current_step');
		});
	});

	$('body').on('click','.goto_step_8',function(e){
		e.preventDefault();

		//collecting data
		var step_7_selection = $('#new_repository_tags').val();
		//console.log('step 7: '+step_7_selection);

		$('#new_repository_item_step_7').removeClass('current_step');
		$('#new_repository_item_step_7').addClass('step_done');

		$('#new_repository_item_step_7').animate({
			opacity: 0,
		}, 250, function() {
			$('#new_repository_item_step_7').hide();
			$('#new_repository_item_step_8').show();	

			var step_2_selection = $('.step_2_selection.selection_active').attr('id');
			var step_3_selection = $('.step_3_selection.selection_active').attr('id');
			if(step_2_selection == 'step_2_safety_datasheet'){
				var prefix_1 = 's';
			}
			else{
				var prefix_1 = 'p';
			}
			if(step_3_selection == 'step_3_material_datasheet'){
				var prefix_2 = 'm';
			}
			else{
				var prefix_2 = 'b';
			}
			var new_file_name = prefix_1+prefix_2+Date.now()+".pdf";

			//console.log('refreshed name: '+new_file_name);

			window.fileUploader = new Dropzone("div#pdf_documents", {
				url: "/pandora/classes/upload.php",
				paramName: "file",
				dictDefaultMessage: '<div class="dropzone_label"><i class="pe-7s-cloud-upload"></i>Datei für Upload hier her ziehen</div>',
				thumbnailWidth: 80,
				createImageThumbnails: false,
				acceptedFiles: 'application/pdf',
				addRemoveLinks: true,
				dictInvalidFileType: 'Wrong file, only PDFs are allowed',
				dictRemoveFile: 'Remove file',
				maxFiles: 1,
				renameFilename: function renameFilename(file) {
					//console.log('name from fileUploader: '+new_file_name);
					return new_file_name;
				},
				init: function() {
					this.on("addedfile", function() {
						if (this.files[1]!=null){
							this.removeFile(this.files[0]);
						}
					});

					this.on("success", function() {
						$('#temp_file_url').val(new_file_name);
						$('#new_repository_links_new').val('http://ttal.loc/documents/'+new_file_name);
						//console.log('file uploaded: '+new_file_name);
					});

					this.on("removedfile", function(file) {
						$.ajax({
							type: 'POST',
							url: "/pandora/classes/upload.php",
							data: 'file_remove='+new_file_name,
							cache: false,
							success: function(html){
								//console.log(html);
								$('#temp_file_url').val('');
								$('#new_repository_links_new').val('');
							}
						});
					});
				}
			});

			

							
			$('#new_repository_item_step_8').animate({
					opacity: 1
			}, 250);

			$('#new_repository_item_step_8').addClass('current_step');
		});
	});


	$('body').on('click','.goto_step_9',function(e){
		e.preventDefault();

		fileUploader.files = [];
		fileUploader.destroy();

		if($('#temp_file_url').val() != ''){
			var new_file_name = 'http://ttal.loc/documents/'+$('#temp_file_url').val();
		}
		else{
			var new_file_name = '';
		}
		
		//console.log(new_file_name);

		//collecting data
		var step_8_selection = $('#new_repository_tags').val();
		//console.log('step 8: '+step_8_selection);

		$('#new_repository_item_step_8').removeClass('current_step');
		$('#new_repository_item_step_8').addClass('step_done');

		$('#new_repository_item_step_8').animate({
			opacity: 0,
		}, 250, function() {
			$('#new_repository_item_step_8').hide();
			if($("#new_repository_item_step_9").length == 0) {
				//create section
				var step_9 = 
						'<div id="new_repository_item_step_9" class="step_container">' +
							'<div id="go_to_new_repository_item_step_8" class="arrow_step_back"><i class="pe-7s-angle-left-circle"></i></div>' +
							'<div class="popup_title">Link</div>' +
								'<div class="popup_description">Tragen Sie nachfolgend den Link ein. Der Link sollte sich von dem Namen zur alten Struktur nicht unterscheiden, da Kunden diesen Link eventuell noch gespeichert haben.</div>' +
							'<div class="new_repository_links_form">' +
								'<div id="new_repository_links_form">' +
									'<div class="form-line">' +
										'<input type="text" disabled class="custom_input_with_label" name="new_repository_links_new" id="new_repository_links_new" value="'+new_file_name+'">' +
										'<label class="custom_label_input">Link to NEW pdf file</label>' +
									'</div>' +
									'<div class="form-line">' +
										'<input type="text" class="custom_input_with_label" name="new_repository_links_old" id="new_repository_links_old">' +
										'<label class="custom_label_input">Enter link to OLD pdf file</label>' +
									'</div>' +
									'<button type="submit" class="form_button goto_step_10">Speichern und fertig</button>' +
								'</div>' +
							'</div>' +
						'</div>'
				;
				$('.new_repository_item_popup_container').append(step_9);
			}
			else{
				//section already exists
				$('#new_repository_item_step_9').show();
			}
				
			$('#new_repository_item_step_9').animate({
					opacity: 1
			}, 250);

			$('#new_repository_item_step_9').addClass('current_step');
		});
	});


	$('body').on('click','.goto_step_10',function(e){
		e.preventDefault();

		var step_9_selection_new_url = $('#new_repository_links_new').val();
		var step_9_selection_old_url = $('#new_repository_links_old').val();
		//console.log('step 9 new url: '+step_9_selection_new_url);
		//console.log('step 9 old url: '+step_9_selection_old_url);

		$('#new_repository_item_step_9').removeClass('current_step');
		$('#new_repository_item_step_9').addClass('step_done');

		$('#new_repository_item_step_9').animate({
			opacity: 0,
		}, 250, function() {
			$('#new_repository_item_step_9').hide();
			//create section
			var step_10 = 
					'<div id="new_repository_item_step_10" class="step_container">' +
						'<div class="popup_title">All done!</div>' +
							'<div class="popup_description">Document is now saved, you can edit it any time by clicking it on the list.</div>' +
						'<div class="new_repository_links_form">' +
							'<div id="new_repository_links_form">' +

								'<button type="submit" class="form_button close_repository_form">Close</button>' +
							'</div>' +
						'</div>' +
					'</div>'
			;
			$('.new_repository_item_popup_container').append(step_10);

				
			$('#new_repository_item_step_10').animate({
					opacity: 1
			}, 250);

			$('#new_repository_item_step_10').addClass('current_step');


			//collecting data
			var repository_item_name = $('#new_repository_item_name').val();
			var repository_art = $('.step_2_selection.selection_active').attr('id');
			var repository_typ = $('.step_3_selection.selection_active').attr('id');
			var repository_relations = $('#new_repository_item_associates_final').val();
			var repository_date = $('#new_repository_item_date').val();
			var repository_material_number = $('#new_repository_materialnumber').val();
			var repository_tags = $('#new_repository_tags').val();
			var repository_file_url = $('#new_repository_links_new').val();
			var repository_old_file_url = $('#new_repository_links_old').val();

			$.ajax({
				type: 'POST',
				url: "/pandora/includes/pages/repository/ajax.php",
				data: {
					repository_item_name: repository_item_name,
					repository_art: repository_art,
					repository_typ: repository_typ,
					repository_relations: repository_relations,
					repository_date: repository_date,
					repository_material_number: repository_material_number,
					repository_tags: repository_tags,
					repository_file_url: repository_file_url,
					repository_old_file_url: repository_old_file_url
				},
				cache: false,
				success: function(html){
					//console.log(html);

				}
			});
		});
	});


	$('body').on('click','.new_repository_item_popup_container .swal2-close',function(e){
		e.preventDefault();

		if(($('#new_repository_item_step_1').hasClass('current_step')) || ($('#new_repository_item_step_10').hasClass('current_step'))){
			swal.close();
		}
		else{
			if (confirm('Your changes won\'t be saved!')) {
				swal.close();
			} else {
				// Do nothing!
			}
		}
	});


	$('body').on('click','.close_repository_form',function(e){
		swal.close();
		location.reload();
	});


	$('body').on('click','.relation_selected i',function(e){
		$(this).parent('.relation_selected').remove();
		var phrase_to_remove = $(this).parent('.relation_selected').find('span').text();
		var current_relations = $('#new_repository_item_associates_final').val();
		var updated_relations = current_relations.replace(phrase_to_remove+',', '');
		$('#new_repository_item_associates_final').val(updated_relations);
	});


	$('body').on('click','.edit_relation_selected i',function(e){
		$(this).parent('.edit_relation_selected').remove();
		var phrase_to_remove = $(this).parent('.edit_relation_selected').find('span').text();
		var current_relations = $('#edit_repository_item_associates_final').val();
		var updated_relations = current_relations.replace(phrase_to_remove+',', '');
		$('#edit_repository_item_associates_final').val(updated_relations);
	});






	/* *********************************************************************************************************************************************************************** */
	/* **************************************************************************** REPOSITORY EDIT ************************************************************************** */
	/* *********************************************************************************************************************************************************************** */


	/* ******************************************************************************* */
	/* ******************************* EDIT: NAME ************************************ */
	/* ******************************************************************************* */
	$('.main_table_column.repository_col_1').on('click', function (e) {
		e.preventDefault();

		var repository_id = $(this).data("id");
		var repository_value = $(this).children("span").text();

		swal({
			html:
			'<div class="custom_popup edit_repository_item_popup_container">' +
				'<div id="edit_repository_item_step_1" class="step_container">' +
					'<div class="popup_title">Name</div>' +
					'<div class="popup_description">Tragen SIe nachfolgend den Namen des SDB oder des PDB ein. Dieser Name ist im Front End beim Kunden sichtbar.</div>' +
					'<div class="edit_repository_item_form">' +
						'<div id="edit_repository_item_form">' +
							'<div class="form-line">' +
								'<input type="text" name="edit_repository_item_value" id="edit_repository_item_value" value="'+repository_value+'">' +
								'<input type="text" name="edit_repository_item_id" id="edit_repository_item_id" value="'+repository_id+'" style="display:none;">' +
							'</div>' +
							'<div class="form_warning_msg">' +
								'This field can not be empty' +
							'</div>' +
							'<button type="submit" class="form_button save_edit_step_1">Speichern und fertig</button>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>',
			showCloseButton: true,
			showCancelButton: false,
			showConfirmButton: false,
			allowOutsideClick: false,
			allowEscapeKey: false,
			animation: true
		}); 	
	});

	$('body').on('click','.save_edit_step_1',function(e){
		e.preventDefault();

		//collecting data
		var edit_repository_item_value = $('#edit_repository_item_value').val();
		var edit_repository_item_id = $('#edit_repository_item_id').val();

		//validate previous step
		if(edit_repository_item_value.length == 0){
			$('#edit_repository_item_value').addClass('field_warning');
			$(this).parent().find('.form_warning_msg').addClass('form_warning_msg_show');
		}
		else{
			$('#edit_repository_item_value').removeClass('field_warning');
			$(this).parent().find('.form_warning_msg').removeClass('form_warning_msg_show');
			
			$.ajax({
				type: 'POST',
				url: "/pandora/includes/pages/repository/ajax.php",
				data: {
					edit_repository_item_name: edit_repository_item_value,
					edit_repository_item_id: edit_repository_item_id
				},
				cache: false,
				success: function(html){
					//console.log(html);
					swal.close();
					location.reload();
				}
			});
		}

	});


	/* ******************************************************************************* */
	/* ******************************** EDIT: ART ************************************ */
	/* ******************************************************************************* */
	$('.main_table_column.repository_col_2').on('click', function (e) {
		e.preventDefault();

		var repository_id = $(this).data("id");
		var repository_value = $(this).children("span").text();

		if(repository_value == 'SDB'){
			var selection = '<div id="step_2_safety_datasheet" class="step_2_selection selection_active"><strong>SICHERHEITS</strong>Datenblatt</div>' +
											'<div id="step_2_product_datasheet" class="step_2_selection"><strong>PRODUKT</strong>Datenblatt</div>' +
											'<div id="step_2_gasgemisch_datasheet" class="step_2_selection"><strong>GASGEMISCH</strong>Datenblatt</div>';
		}
		else if(repository_value == 'PDB'){
			var selection = '<div id="step_2_safety_datasheet" class="step_2_selection"><strong>SICHERHEITS</strong>Datenblatt</div>' +
											'<div id="step_2_product_datasheet" class="step_2_selection selection_active"><strong>PRODUKT</strong>Datenblatt</div>' +
											'<div id="step_2_gasgemisch_datasheet" class="step_2_selection"><strong>GASGEMISCH</strong>Datenblatt</div>';
		}
		else{
			var selection = '<div id="step_2_safety_datasheet" class="step_2_selection"><strong>SICHERHEITS</strong>Datenblatt</div>' +
											'<div id="step_2_product_datasheet" class="step_2_selection"><strong>PRODUKT</strong>Datenblatt</div>' +
											'<div id="step_2_gasgemisch_datasheet" class="step_2_selection selection_active"><strong>GASGEMISCH</strong>Datenblatt</div>';
		}

		swal({
			html:
			'<div class="custom_popup edit_repository_item_popup_container">' +
				'<div id="edit_repository_item_step_2" class="step_container">' +
					'<div class="popup_title">Art</div>' +
					'<div class="popup_description">Wählen Sie nachfolgend die Art des Datenblattes. Die Art ist sichtbar im Front End als Tag.</div>' +
					'<div class="edit_repository_item_form">' +
						'<div id="edit_repository_item_form">' +
							'<div class="step_2_selection_buttons">' +
								selection +
								'<input type="text" name="edit_repository_item_id" id="edit_repository_item_id" value="'+repository_id+'" style="display:none;">' +
							'</div>' +
							'<button type="submit" class="form_button save_edit_step_2">Speichern und fertig</button>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>',
			showCloseButton: true,
			showCancelButton: false,
			showConfirmButton: false,
			allowOutsideClick: false,
			allowEscapeKey: false,
			animation: true
		}); 	
	});

	$('body').on('click','.save_edit_step_2',function(e){
		e.preventDefault();

		//collecting data
		var step_2_selection = $('.step_2_selection.selection_active').attr('id');
		var edit_repository_item_id = $('#edit_repository_item_id').val();
			
		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/repository/ajax.php",
			data: {
				step_2_selection: step_2_selection,
				edit_repository_item_id: edit_repository_item_id
			},
			cache: false,
			success: function(html){
				//console.log(html);
				swal.close();
				location.reload();
			}
		});

	});


	/* ******************************************************************************* */
	/* ******************************** EDIT: TYP ************************************ */
	/* ******************************************************************************* */
	$('.main_table_column.repository_col_3').on('click', function (e) {
		e.preventDefault();

		var repository_id = $(this).data("id");
		var repository_value = $(this).children("span").text();

		if(repository_value == 'Stoff'){
			var selection = '<div id="step_3_material_datasheet" class="step_3_selection selection_active">Stoff</div>' +
											'<div id="step_3_brandgas_datasheet" class="step_3_selection">Markengas</div>';
		}
		else{
			var selection = '<div id="step_3_material_datasheet" class="step_3_selection">Stoff</div>' +
											'<div id="step_3_brandgas_datasheet" class="step_3_selection selection_active">Markengas</div>';
		}

		swal({
			html:
			'<div class="custom_popup edit_repository_item_popup_container">' +
				'<div id="edit_repository_item_step_3" class="step_container">' +
					'<div class="popup_title">Typ</div>' +
					'<div class="popup_description">Wählen Sie nachfolgend die Typ des Datenblattes. Datenblätter werden nach Typ getrennt in der Tabelle in der Sektion “Übersicht” dargestellt.</div>' +
					'<div class="edit_repository_item_form">' +
						'<div id="edit_repository_item_form">' +
							'<div class="step_3_selection_buttons">' +
								selection +
								'<input type="text" name="edit_repository_item_id" id="edit_repository_item_id" value="'+repository_id+'" style="display:none;">' +
							'</div>' +
							'<button type="submit" class="form_button save_edit_step_3">Speichern und fertig</button>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>',
			showCloseButton: true,
			showCancelButton: false,
			showConfirmButton: false,
			allowOutsideClick: false,
			allowEscapeKey: false,
			animation: true
		}); 	
	});

	$('body').on('click','.save_edit_step_3',function(e){
		e.preventDefault();

		//collecting data
		var step_3_selection = $('.step_3_selection.selection_active').attr('id');
		var edit_repository_item_id = $('#edit_repository_item_id').val();
			
		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/repository/ajax.php",
			data: {
				step_3_selection: step_3_selection,
				edit_repository_item_id: edit_repository_item_id
			},
			cache: false,
			success: function(html){
				//console.log(html);
				swal.close();
				location.reload();
			}
		});

	});


	/* ******************************************************************************* */
	/* ****************************** EDIT: RELATIONS ******************************** */
	/* ******************************************************************************* */
	$('.main_table_column.repository_col_4').on('click', function (e) {
		e.preventDefault();

		var repository_id = $(this).data("id");
		var repository_value = $(this).children("span").text();
				
		swal({
			html:
			'<div class="custom_popup edit_repository_item_popup_container">' +
				'<div id="edit_repository_item_step_4" class="step_container">' +
					'<div class="popup_title">Verbunden mit</div>' +
					'<div class="popup_description">Geben Sie nachfolgend den Namen des Datenblattes ein und wählen Sie anschließend aus. Damit ein Produktdatenblatt auf ein Sicherheitsdatenblatt zeigen kann, muss das SDB bereits vorher hochgeladen worden sein. Es handelt sich um eine EINWEG-Verknüpfung</div>' +
					'<div class="edit_repository_item_associates_form">' +
						'<div id="edit_repository_item_associates_form">' +
							'<div class="form-line">' +
								'<input type="text" name="edit_repository_item_associates" id="edit_repository_item_associates">' +
								'<input type="text" name="edit_repository_item_associates_final" id="edit_repository_item_associates_final">' +
								'<input type="text" name="edit_repository_item_id" id="edit_repository_item_id" value="'+repository_id+'" style="display:none;">' +
								'<i class="pe-7s-search step_4_search_icon"></i>' +
							'</div>' +
							'<div id="edit_relations_selected"></div>' +
							'<button type="submit" class="form_button save_edit_step_4">Speichern und fertig</button>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>',
			showCloseButton: true,
			showCancelButton: false,
			showConfirmButton: false,
			allowOutsideClick: false,
			allowEscapeKey: false,
			animation: true
		});

		if(repository_value != ''){
			$('#edit_relations_selected').css('display','flex');
			$('#edit_repository_item_associates_final').val(repository_value+',');

			var repository_values_to_selected_box = repository_value.split(",");
			var i;
			for (i = 0; i < repository_values_to_selected_box.length; ++i) {
				$('#edit_relations_selected').append('<div class="edit_relation_selected"><span>'+repository_values_to_selected_box[i]+'</span><i class="pe-7s-close-circle"></i></div>');
			}
		
		}

		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/repository/ajax.php",
			data: 'get_relations=add&current_id='+repository_id,
			cache: false,
			success: function(html){
				//console.log(html);
				var repositories = html.split('||');

				$('#edit_repository_item_associates').autoComplete({
						minChars: 2,
						source: function(term, suggest){
							term = term.toLowerCase();
							var choices = repositories;
							var suggestions = [];
							for (i=0;i<choices.length;i++)
								if (~choices[i].toLowerCase().indexOf(term)) suggestions.push(choices[i]);
							suggest(suggestions);
						},
						renderItem: function (item, search){
							return '<div class="autocomplete-suggestion" data-name="'+item+'">'+item+'</div>';
						},
						onSelect: function(e, term, item){
							$('#edit_relations_selected').css('display','flex');
							
							var current_relations = $('#edit_repository_item_associates_final').val();

							if(current_relations.indexOf(item.data('name')) == -1){
								$('#edit_repository_item_associates_final').val(current_relations+item.data('name')+',');
								$('#edit_relations_selected').append('<div class="edit_relation_selected"><span>'+item.data('name')+'</span><i class="pe-7s-close-circle"></i></div>');
							}
						}
				});
			}
		});

	});

	$('body').on('click','.save_edit_step_4',function(e){
		e.preventDefault();

		//collecting data
		var step_4_selection = $('#edit_repository_item_associates_final').val();
		var edit_repository_item_id = $('#edit_repository_item_id').val();
			
		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/repository/ajax.php",
			data: {
				step_4_selection: step_4_selection,
				edit_repository_item_id: edit_repository_item_id
			},
			cache: false,
			success: function(html){
				//console.log(html);
				swal.close();
				location.reload();
			}
		});

	});


	/* ******************************************************************************* */
	/* ******************************* EDIT: DATE ************************************ */
	/* ******************************************************************************* */
	$('.main_table_column.repository_col_5').on('click', function (e) {
		e.preventDefault();

		var repository_id = $(this).data("id");
		var repository_value = $(this).children("span").text();

		swal({
			html:
			'<div class="custom_popup edit_repository_item_popup_container">' +
				'<div id="edit_repository_item_step_5" class="step_container">' +
					'<div class="popup_title">Änderungsdatum</div>' +
					'<div class="popup_description">Geben Sie nachfolgend das Änderungsdatum an. Dieses Datum ist bei jedem Datenblatt im Front End sichtbar</div>' +
					'<div class="edit_repository_item_date_form">' +
						'<div id="edit_repository_item_date_form">' +
							'<div class="form-line">' +
								'<input type="text" data-toggle="edit_datepicker" id="edit_repository_item_date" value="'+repository_value+'">' +
								'<input type="text" name="edit_repository_item_id" id="edit_repository_item_id" value="'+repository_id+'" style="display:none;">' +
								'<i class="pe-7s-date step_5_calendar_icon"></i>' +
							'</div>' +
							'<div class="form_warning_msg">' +
								'Incorrect date' +
							'</div>' +
							'<button type="submit" class="form_button save_edit_step_5">Speichern und fertig</button>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>',
			showCloseButton: true,
			showCancelButton: false,
			showConfirmButton: false,
			allowOutsideClick: false,
			allowEscapeKey: false,
			animation: true,
			onOpen: function() { 
				$('[data-toggle="edit_datepicker"]').datepicker({
					language: 'de-DE',
					format: 'dd.mm.yyyy',
					autoPick: true
				});
			}
		}); 	
	});

	
	$('body').on('click','.save_edit_step_5',function(e){
		e.preventDefault();

		//collecting data
		var step_5_selection = $('#edit_repository_item_date').val();
		var edit_repository_item_id = $('#edit_repository_item_id').val();

		//validate previous step
		if(step_5_selection.length == 0){
			$('#edit_repository_item_date').addClass('field_warning');
			$(this).parent().find('.form_warning_msg').addClass('form_warning_msg_show');
		}
		else{
			//validate date
			var date_validated = step_5_selection.split('.');
			if(
				(date_validated.length == 3) && 
				($.isNumeric(date_validated[0])) && 
				($.isNumeric(date_validated[1])) && 
				($.isNumeric(date_validated[2])) &&
				(date_validated[0] > 0) && (date_validated[0] <= 31) &&
				(date_validated[1] > 0) && (date_validated[1] <= 12) &&
				(date_validated[2] > 1900)
			){
				$('#edit_repository_item_date').removeClass('field_warning');
				$(this).parent().find('.form_warning_msg').removeClass('form_warning_msg_show');

				$.ajax({
					type: 'POST',
					url: "/pandora/includes/pages/repository/ajax.php",
					data: {
						step_5_selection: step_5_selection,
						edit_repository_item_id: edit_repository_item_id
					},
					cache: false,
					success: function(html){
						//console.log(html);
						swal.close();
						location.reload();
					}
				});

			}
			else{
				$('#edit_repository_item_date').addClass('field_warning');
				$(this).parent().find('.form_warning_msg').addClass('form_warning_msg_show');
			}
		}
			
	});


	/* ******************************************************************************* */
	/* ************************** EDIT: MATERIAL NUMBER ****************************** */
	/* ******************************************************************************* */
	$('.main_table_column.repository_col_6').on('click', function (e) {
		e.preventDefault();

		var repository_id = $(this).data("id");
		var repository_value = $(this).children("span").text();

		swal({
			html:
			'<div class="custom_popup edit_repository_item_popup_container">' +
				'<div id="edit_repository_item_step_6" class="step_container">' +
					'<div class="popup_title">Materialnummer</div>' +
						'<div class="popup_description">Geben Sie nachfolgend die Materialnummer ein. Diese ist im Front End nicht ersichtlich, der Kunde kann jedoch trotzdem mit der Materialnummer nach dem Datenblattsuchen.</div>' +
					'<div class="edit_repository_materialnumber_form">' +
						'<div id="edit_repository_materialnumber_form">' +
							'<div class="form-line">' +
								'<input type="text" name="edit_repository_materialnumber" id="edit_repository_materialnumber" value="'+repository_value+'">' +
								'<input type="text" name="edit_repository_item_id" id="edit_repository_item_id" value="'+repository_id+'" style="display:none;">' +
							'</div>' +
							'<div class="form_warning_msg">' +
								'This field can not be empty' +
							'</div>' +
							'<button type="submit" class="form_button save_edit_step_6">Speichern und fertig</button>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>',
			showCloseButton: true,
			showCancelButton: false,
			showConfirmButton: false,
			allowOutsideClick: false,
			allowEscapeKey: false,
			animation: true
		}); 	
	});

	$('body').on('click','.save_edit_step_6',function(e){
		e.preventDefault();

		//collecting data
		var step_6_selection = $('#edit_repository_materialnumber').val();
		var edit_repository_item_id = $('#edit_repository_item_id').val();

		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/repository/ajax.php",
			data: {
				step_6_selection: step_6_selection,
				edit_repository_item_id: edit_repository_item_id
			},
			cache: false,
			success: function(html){
				//console.log(html);
				swal.close();
				location.reload();
			}
		});		
	});


	/* ******************************************************************************* */
	/* ********************************** EDIT: TAGS ********************************* */
	/* ******************************************************************************* */
	$('.main_table_column.repository_col_7').on('click', function (e) {
		e.preventDefault();

		var repository_id = $(this).data("id");
		var repository_value = $(this).children("span").text();

		swal({
			html:
			'<div class="custom_popup edit_repository_item_popup_container">' +
				'<div id="edit_repository_item_step_7" class="step_container">' +
					'<div class="popup_title">Tag Beschreibung</div>' +
						'<div class="popup_description">Geben Sie nachfolgend die Tags an. Trennen SIe die Tags mit einem Komma. Tags werden im Front End nicht angezeigt. Kunden können trotzdem danach suchen.</div>' +
					'<div class="edit_repository_tags_form">' +
						'<div id="edit_repository_tags_form">' +
							'<div class="form-line">' +
								'<textarea rows="5" name="edit_repository_tags" id="edit_repository_tags" >'+repository_value+'</textarea>' +
								'<input type="text" name="edit_repository_item_id" id="edit_repository_item_id" value="'+repository_id+'" style="display:none;">' +
							'</div>' +
							'<button type="submit" class="form_button save_edit_step_7">Speichern und fertig</button>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>',
			showCloseButton: true,
			showCancelButton: false,
			showConfirmButton: false,
			allowOutsideClick: false,
			allowEscapeKey: false,
			animation: true,
			onOpen: function() { 
				$('[name=edit_repository_tags]').tagify({
					duplicates : false,
					delimiters: ","
				});
			}
		}); 	
	});

	$('body').on('click','.save_edit_step_7',function(e){
		e.preventDefault();

		//collecting data
		var step_7_selection = $('#edit_repository_tags').val();
		var edit_repository_item_id = $('#edit_repository_item_id').val();

		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/repository/ajax.php",
			data: {
				step_7_selection: step_7_selection,
				edit_repository_item_id: edit_repository_item_id
			},
			cache: false,
			success: function(html){
				//console.log(html);
				swal.close();
				location.reload();
			}
		});
	});


	/* ******************************************************************************* */
	/* ********************************** EDIT: LINK ********************************* */
	/* ******************************************************************************* */
	$('.main_table_column.repository_col_9').on('click', function (e) {
		e.preventDefault();

		var repository_id = $(this).data("id");
		var repository_value = $(this).children("span").text();
		var pdf_old = $(this).children(".old_url").text();

		swal({
			html:
			'<div class="custom_popup edit_repository_item_popup_container">' +
				'<div id="edit_repository_item_step_9" class="step_container">' +
					'<div class="popup_title">Link</div>' +
						'<div class="popup_description">Tragen Sie nachfolgend den Link ein. Der Link sollte sich von dem Namen zur alten Struktur nicht unterscheiden, da Kunden diesen Link eventuell noch gespeichert haben.</div>' +
					'<div class="edit_repository_links_form">' +
						'<div id="edit_repository_links_form">' +
							'<div class="form-line">' +
								'<input type="text" disabled class="custom_input_with_label" name="edit_repository_links_new" id="edit_repository_links_new" value="'+repository_value+'">' +
								'<label class="custom_label_input">Link to NEW pdf file</label>' +
							'</div>' +
							'<div class="form-line">' +
								'<input type="text" class="custom_input_with_label" name="edit_repository_links_old" id="edit_repository_links_old" value="'+pdf_old+'">' +
								'<label class="custom_label_input">Enter link to OLD pdf file</label>' +
							'</div>' +
							'<input type="text" name="edit_repository_item_id" id="edit_repository_item_id" value="'+repository_id+'" style="display:none;">' +
							'<button type="submit" class="form_button save_edit_step_9">Speichern und fertig</button>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>',
			showCloseButton: true,
			showCancelButton: false,
			showConfirmButton: false,
			allowOutsideClick: false,
			allowEscapeKey: false,
			animation: true,
			onOpen: function() { 
				
			}
		}); 	
	});

	$('body').on('click','.save_edit_step_9',function(e){
		e.preventDefault();

		//collecting data
		var step_9_selection = $('#edit_repository_links_old').val();
		var edit_repository_item_id = $('#edit_repository_item_id').val();

		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/repository/ajax.php",
			data: {
				step_9_selection: step_9_selection,
				edit_repository_item_id: edit_repository_item_id
			},
			cache: false,
			success: function(html){
				//console.log(html);
				swal.close();
				location.reload();
			}
		});
	});


	/* ******************************************************************************* */
	/* ******************************* EDIT: UPLOAD PDF ****************************** */
	/* ******************************************************************************* */
	$('.main_table_column.repository_col_10').on('click', function (e) {
		e.preventDefault();

		var repository_id = $(this).data("id");
		var repository_value = $(this).children("span").text();
		var pdf_old = $(this).children(".old_url").text();

		swal({
			html:
			'<div id="edit_repository_item_step_10" class="step_container">' +
				'<div class="popup_title">Hochladen</div>' +
					'<div class="popup_description">Sie können das Datenblatt nachfolgend über den Upload aktualisieren.</div>' +
				'<div class="edit_repository_links_files">' +
					'<div id="edit_repository_links_files">' +
						'<div class="form-line">' +
							'<div class="existing_file"><span class="remove_existing_file_btn" data-id="'+repository_id+'">Remove file</span></div>' +
							'<div id="edit_pdf_documents" class="dropzone">' +
							'</div>' +
						'</div>' +
						'<button type="submit" class="form_button save_edit_step_10">Speichern und fertig</button>' +
						'<input type="text" name="temp_file_url" id="temp_file_url" style="display:none">' +
					'</div>' +
				'</div>' +
			'</div>',
			showCloseButton: true,
			showCancelButton: false,
			showConfirmButton: false,
			allowOutsideClick: false,
			allowEscapeKey: false,
			animation: true,
			onOpen: function() { 
				var step_2_selection = $('.repository_col_2[data-id="'+repository_id+'"]').children("span").text();
				var step_3_selection = $('.repository_col_3[data-id="'+repository_id+'"]').children("span").text();
				if(step_2_selection == 'SDB'){
					var prefix_1 = 's';
				}
				else{
					var prefix_1 = 'p';
				}
				if(step_3_selection == 'Marken gas'){
					var prefix_2 = 'm';
				}
				else{
					var prefix_2 = 'b';
				}
				var new_file_name = prefix_1+prefix_2+Date.now()+".pdf";

				window.fileUploader = new Dropzone("div#edit_pdf_documents", {
					url: "/pandora/classes/upload.php",
					paramName: "file",
					dictDefaultMessage: '<div class="dropzone_label"><i class="pe-7s-cloud-upload"></i>Datei für Upload hier her ziehen</div>',
					thumbnailWidth: 80,
					createImageThumbnails: false,
					acceptedFiles: 'application/pdf',
					addRemoveLinks: true,
					dictInvalidFileType: 'Wrong file, only PDFs are allowed',
					dictRemoveFile: 'Remove file',
					maxFiles: 1,
					autoProcessQueue: false,
					renameFilename: function renameFilename(file) {
						//console.log('name from fileUploader: '+new_file_name);
						return new_file_name;
					},
					init: function() {
						$.ajax({
							type: 'POST',
							url: "/pandora/includes/pages/repository/ajax.php",
							data: {
								get_repository_file: repository_id
							},
							cache: false,
							success: function(html){
								//console.log(html);
								if((html != 'empty')&&(html != '')){
									$('.existing_file').prepend(html).css('display','flex');
								}
							}
						});


						this.on("addedfile", function() {
							if (this.files[1]!=null){
								this.removeFile(this.files[0]);
							}
						});

						this.on("success", function() {
							$('#temp_file_url').val(new_file_name);
							$('#edit_repository_links_new').val('http://ttal.loc/documents/'+new_file_name);
							//console.log('file uploaded: '+new_file_name);

							$.ajax({
								type: 'POST',
								url: "/pandora/includes/pages/repository/ajax.php",
								data: {
									remove_old_file: repository_id
								},
								cache: false,
								success: function(html){
									//console.log(html);
								}
							});

							$.ajax({
								type: 'POST',
								url: "/pandora/includes/pages/repository/ajax.php",
								data: {
									file_uploaded: 'http://ttal.loc/documents/'+new_file_name,
									edit_repository_item_id: repository_id
								},
								cache: false,
								success: function(html){
									//console.log(html);
								}
							});

						});

						this.on("removedfile", function(file) {
							$.ajax({
								type: 'POST',
								url: "/pandora/classes/upload.php",
								data: 'file_remove='+new_file_name,
								cache: false,
								success: function(html){
									console.log(html);
									$('#temp_file_url').val('');
									$('#edit_repository_links_new').val('');
								}
							});
						});

						this.on("queuecomplete", function() {
							setTimeout(
								function() {
									swal.close();
									location.reload();
								}, 1000);
						});

					}
				});
			}
		}); 	
	});


	$('body').on('click','.save_edit_step_10',function(e){
		e.preventDefault();

		window.fileUploader.processQueue();

	});


	$('body').on('click','.remove_existing_file_btn',function(e){
		e.preventDefault();

		var repository_id = $(this).data("id");
		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/repository/ajax.php",
			data: {
				remove_old_file_and_db_entry: repository_id
			},
			cache: false,
			success: function(html){
				$('.existing_file').hide();
				$('.repository_col_9[data-id="'+repository_id+'"]').children("span").empty();
			}
		});

	});


	








	$('.repository_remove_checkbox').change(function() {
		if($(".repository_remove_checkbox:checked").length > 0){
			$('#repository_remove_batch').show();
		}
		else{
			$('#repository_remove_batch').hide();
		}
	}); 


	$('#repository_remove_batch').on('click', function (e) {
		e.preventDefault();
		swal({
			html:
				'<div class="custom_popup">' +
					'<div class="popup_title">Repository remove</div>' +
					'<div class="popup_remove">' +
						'<span>Are you sure you want to remove seleced repository / repositories?</span>' +
						'<div class="popup_remove_buttons">' +
							'<button type="submit" id="popup_remove_cancel" class="form_button">No, cancel</button>' +
							'<button type="submit" id="popup_remove_confirm" class="form_button">Yes, I confirm</button>' +
						'</div>' +
					'</div>' +
				'</div>',
			showCloseButton: true,
			showCancelButton: false,
			showConfirmButton: false,
			animation: true
		});
	});


	$('body').on('click','#popup_remove_cancel',function(e){
		swal.close();
	});


	$('body').on('click','#popup_remove_confirm',function(e){

		var repositories_to_remove_array = $(".repository_remove_checkbox:checkbox:checked").map(function(){
			return $(this).val();
		}).get();

		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/repository/ajax.php",
			data: {
				repositories_to_remove: repositories_to_remove_array
			},
			cache: false,
			success: function(html){
				location.reload();
				// console.log(html);
			}
		});
		
	});



	/* ******************************************************************************* */
	/* ************************************ USERS ************************************ */
	/* ******************************************************************************* */
	$('.account_edit_user').on('click', function (e) {
		e.preventDefault();
		
		var user_id = $(this).data("id");
		window.location.href = 'account.php?user='+user_id;
	});


	$('body').on('click','.save_login_with_google_email_address',function(e){

		var email_address = $('#login_with_google_email_address').val();
		var email_address_old = $('#login_with_google_email_address_old').val();
		var user_id = $('#login_with_google_user_id').val();
		var google_login_status = $('#login_with_google_login_type_status').val();

		//console.log(google_login_status);

		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/account/ajax.php",
			data: {
				save_login_with_google_email_address: true,
				email_address: email_address,
				email_address_old: email_address_old,
				user_id: user_id,
				google_login_status: google_login_status
			},
			cache: false,
			success: function(html){
				window.location.href = 'account.php';
				//console.log(html);
			}
		});
	});


	$('body').on('click','.save_login_regular',function(e){
		e.preventDefault();

		var firstname = $('#login_regular_firstname').val();
		var lastname = $('#login_regular_lastname').val();
		var password = $('#login_regular_password_1').val();
		var password2 = $('#login_regular_password_2').val();
		var user_id = $('#login_regular_user_id').val();
		var regular_login_type_status = $('#regular_login_type_status').val();

		if(password != password2){
			$('#login_regular_password_2').addClass('show_error_input');
		}
		else{
			$('#login_regular_password_2').removeClass('show_error_input');
			
			$.ajax({
				type: 'POST',
				url: "/pandora/includes/pages/account/ajax.php",
				data: {
					save_login_regular: true,
					firstname: firstname,
					lastname: lastname,
					password: password,
					user_id: user_id,
					regular_login_type_status: regular_login_type_status
				},
				cache: false,
				success: function(html){
					window.location.href = 'account.php';
					//console.log(html);
				}
			});
		}

	});


	$('body').on('click','.select-items div',function(e){
		if($(this).text() == 'AKTIV'){
			$(this).parent().parent().find('.select-selected').removeClass('selected-inactive');
			$(this).parent().parent().find('.select-selected').addClass('selected-active');
		}
		else{
			$(this).parent().parent().find('.select-selected').removeClass('selected-active');
			$(this).parent().parent().find('.select-selected').addClass('selected-inactive');
		}
	});


	$(".select-selected").each(function() {
		if($(this).text() == 'AKTIV'){
			$(this).removeClass('selected-inactive');
			$(this).addClass('selected-active');
		}
		else{
			$(this).removeClass('selected-active');
			$(this).addClass('selected-inactive');
		}
	});


	$('body').on('click','.user_approval',function(e){
		var user_id = $(this).data("id");
		$('.user_approval_selection').not('#user_approval_'+user_id).hide();
		$('#user_approval_'+user_id).toggle();
	});


	
	$('body').on('click','.approve_accounts_button',function(e){
		
		var checkedVals = $('[type="checkbox"].users_approve_checkbox_action:checked').map(function() {
			return this.value;
		}).get();
		var approve_table = checkedVals.join(",");
		//console.log('table init: '+approve_table);

		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/account/ajax.php",
			data: {
				approve_users: true,
				users_id: approve_table
			},
			cache: false,
			success: function(html){
				window.location.href = 'account.php';
			}
		});

	});



	$("#batch_upload").change(function() {
		var ext = $("#batch_upload").val().split(".").pop().toLowerCase();

		if($.inArray(ext, ["csv"]) == -1) {
			alert('CSV only');
			return false;
		}
		else{
			$("#fupForm").submit();
		}
		
	});

	$("#fupForm").on('submit', function(e){
		e.preventDefault();

		//console.log($('#batch_upload').prop('files')[0]);

		var fileInput = document.getElementById('batch_upload');
		var file = fileInput.files[0];
		var reader = new FileReader();

		reader.onload = function(e) {
			//console.log(reader.result);
			
			$.ajax({
				type: 'POST',
				url: "/pandora/includes/pages/account/ajax.php",
				data: {
					batch_users: true,
					users: reader.result
				},
				cache: false,
				success: function(response){
					console.log(response);
					//window.location.href = 'account.php';
				}
			});
		}
		reader.readAsText(file);
		
	});
	


	$("#users_approve_all_checkboxes").click(function(){
		$('.users_approve_checkbox').not(this).prop('checked', this.checked);
	});


	$('body').on('click','.user_approval_approved',function(e){

		var user_id = $(this).data("id");

		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/account/ajax.php",
			data: {
				approve_user: true,
				user_id: user_id
			},
			cache: false,
			success: function(html){
				window.location.href = 'account.php';
			}
		});
	});


	$('body').on('click','.user_approval_blocked',function(e){

		var user_id = $(this).data("id");

		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/account/ajax.php",
			data: {
				block_user: true,
				user_id: user_id
			},
			cache: false,
			success: function(html){
				window.location.href = 'account.php';
			}
		});
	});



	/* ******************************************************************************* */
	/* ****************************** LANG HANDLING ********************************** */
	/* ******************************************************************************* */
	$('body').on('click','.lang_name',function(e){

		//console.log('clicked');

		var lang_id = $(this).data("lang-id");

		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/translations/ajax.php",
			data: {
				change_language: true,
				lang_id: lang_id
			},
			cache: false,
			success: function(html){
				console.log('changed');
				location.reload();
			}
		});
	});




	/* ******************************************************************************* */
	/* ****************************** COOKIE BANNER ********************************** */
	/* ******************************************************************************* */
	$('body').on('click','.cookie_banner_save_button',function(e){

		var lang_id = $(this).data("lang-id");
		var cookie_banner_title = $('#cookie_banner_title').val();
		var cookie_banner_text = $('#cookie_banner_text').val();
		var cookie_banner_text_link_1 = $('#cookie_banner_text_link_1').val();
		var cookie_banner_url_link_1 = $('#cookie_banner_url_link_1').val();
		var cookie_banner_text_link_2 = $('#cookie_banner_text_link_2').val();
		var cookie_banner_url_link_2 = $('#cookie_banner_url_link_2').val();
		var cookie_banner_option_1 = $('#cookie_banner_option_1').val();
		var cookie_banner_option_2 = $('#cookie_banner_option_2').val();
		var cookie_banner_small_text = $('#cookie_banner_small_text').val();
		var cookie_banner_declaration = $('#cookie_banner_declaration').val();
		var cookie_banner_title_second = $('#cookie_banner_title_second').val();
		var cookie_banner_text_second = tinymce.get("cookie_banner_text_second").getContent();
		var cookie_banner_back = $('#cookie_banner_back').val();

		var g_analytics = $('#g_analytics_input').val();
		var hotjar = $('#hotjar_input').val();
		
		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/cookie_banner/ajax.php",
			data: {
				update_translations: true,
				lang_id: lang_id,
				cookie_banner_title: cookie_banner_title,
				cookie_banner_text: cookie_banner_text,
				cookie_banner_text_link_1: cookie_banner_text_link_1,
				cookie_banner_url_link_1: cookie_banner_url_link_1,
				cookie_banner_text_link_2: cookie_banner_text_link_2,
				cookie_banner_url_link_2: cookie_banner_url_link_2,
				cookie_banner_option_1: cookie_banner_option_1,
				cookie_banner_option_2: cookie_banner_option_2,
				cookie_banner_small_text: cookie_banner_small_text,
				cookie_banner_declaration: cookie_banner_declaration,
				cookie_banner_title_second: cookie_banner_title_second,
				cookie_banner_text_second: cookie_banner_text_second,
				cookie_banner_back: cookie_banner_back,
				g_analytics: g_analytics,
				hotjar: hotjar
			},
			cache: false,
			success: function(html){
				location.reload();
			}
		});
		
	});


	$('body').on('click','.btr-accept-ui',function(e){
		if($(".btr-accept-ui").hasClass('agreement_checked')){
			$(".btr-accept-ui").removeClass('agreement_checked');
			$.ajax({
				type: 'POST',
				url: "/pandora/includes/pages/cookie_banner/ajax.php",
				data: {
					cookie_banner_off: true,
				},
				cache: false,
				success: function(html){

				}
			});
		}
		else{
			$(".btr-accept-ui").addClass('agreement_checked');
			$.ajax({
				type: 'POST',
				url: "/pandora/includes/pages/cookie_banner/ajax.php",
				data: {
					cookie_banner_on: true,
				},
				cache: false,
				success: function(html){

				}
			});
		}
	});






	/* *********************************************************************************************************************************************************************** */
	/* ******************************************************************************** FEEDBACK ***************************************************************************** */
	/* *********************************************************************************************************************************************************************** */


	/* ******************************************************************************* */
	/* ****************************** NOTIFICATIONS ********************************** */
	/* ******************************************************************************* */
	$('.notifications_popup_button').on('click', function (e) {
		e.preventDefault();

		$.ajax({
			url: "/pandora/includes/pages/feedback/ajax.php",
			type: 'POST',
			data: {
				notifications: true
			},
			cache: false,
			success: function(html){
				swal({
					html:
						'<div class="custom_popup">' +
							'<div class="popup_title">Feedback Notifications</div>' +
							'<div class="popup_description">Tragen Sie nachfolgend die E-Mail Adresse ein. Diese erhalten dann das Kundenfeedback als mail2action zugeschickt.</div>' +
							'<div class="feedback_users_form">' +
								'<div id="feedback_users_form">' +
									'<div class="form-line">' +
										html +
									'</div>' +
								'</div>' +
							'</div>' +
						'</div>',
					showCloseButton: true,
					showCancelButton: false,
					showConfirmButton: false,
					animation: true,
					width: '820px'
				});
			}
		});	

	});



	$('body').on('click','.feedback_new_user_email_button',function(e){

		var feedback_new_user_email = $('#feedback_new_user_email').val();
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

		if(!feedback_new_user_email.match(re)) {
		  alert('Invalid email');
		  return false;
		}
		else{
			$.ajax({
				type: 'POST',
				url: "/pandora/includes/pages/feedback/ajax.php",
				data: {
					feedback_new_user_email: feedback_new_user_email
				},
				cache: false,
				success: function(html){
					$('.feedback_new_user_email_button_confirmation').css('display','flex');
					$('#swal2-content .main_table_container').empty();
					$('#swal2-content .main_table_container').append(html);
				}
			});
		}

	});



	$('body').on('click','.remove_feedback_user',function(e){

		var feedback_user_id = $(this).data("id");

		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/feedback/ajax.php",
			data: {
				feedback_user_id_to_remove: feedback_user_id
			},
			cache: false,
			success: function(html){
				$('#swal2-content .main_table_container').empty();
				$('#swal2-content .main_table_container').append(html);
			}
		});

	});




	/* ******************************************************************************* */
	/* ********************************** FOOTER ************************************* */
	/* ******************************************************************************* */
	$('body').on('click','.footer_save_button',function(e){

		var lang_id = $(this).data("lang-id");
		var footer_copyright = $('#footer_copyright').val();
		var footer_text_link_1 = $('#footer_text_link_1').val();
		var footer_url_link_1 = $('#footer_url_link_1').val();
		var footer_text_link_2 = $('#footer_text_link_2').val();
		var footer_url_link_2 = $('#footer_url_link_2').val();
		var footer_text_link_3 = $('#footer_text_link_3').val();
		var footer_url_link_3 = $('#footer_url_link_3').val();
		var footer_text_link_4 = $('#footer_text_link_4').val();
		var footer_url_link_4 = $('#footer_url_link_4').val();

		var g_analytics = $('#g_analytics_input').val();
		var hotjar = $('#hotjar_input').val();
		
		$.ajax({
			type: 'POST',
			url: "/pandora/includes/pages/footer/ajax.php",
			data: {
				update_translations: true,
				lang_id: lang_id,
				footer_copyright: footer_copyright,
				footer_text_link_1: footer_text_link_1,
				footer_url_link_1: footer_url_link_1,
				footer_text_link_2: footer_text_link_2,
				footer_url_link_2: footer_url_link_2,
				footer_text_link_3: footer_text_link_3,
				footer_url_link_3: footer_url_link_3,
				footer_text_link_4: footer_text_link_4,
				footer_url_link_4: footer_url_link_4
			},
			cache: false,
			success: function(html){
				location.reload();
			}
		});
		
	});

});
