$(document).ready(function() {
	
	$('#fullpage').fullpage({
		autoScrolling:true,
		scrollHorizontally: true,
		normalScrollElements: '.autocomplete-suggestions, #search_ajax_results',
		licenseKey: '497E3AB4-23D6449B-B214FCEA-37171995',
		anchors: ['suche', 'ubersicht', 'kontakt'],
		menu: '.navbar_buttons',
		onLeave: function(index, nextIndex, direction) {
			$('.mouse_scroll').animate({
				opacity: 0
			}, 100, function() {
				$('.mouse_scroll_search').hide();
				$('.mouse_scroll_overview').hide();
			});
			
		},
		afterLoad: function(anchorLink, index){
				if(index.anchor == 'suche'){
					$('.mouse_scroll_search').show();
					$('.mouse_scroll').animate({
						opacity: 1
					}, 500, function() {
						
					});
				}
				if(index.anchor == 'ubersicht'){
					$('.mouse_scroll_overview').show();
					$('.mouse_scroll').animate({
						opacity: 1
					}, 500, function() {
						
					});
				}
				if(index.anchor == 'kontakt'){
					
				}
			}
	});	


	$('body').on('click','.top_link ',function(e){
		//$('#main_container').foggy();
	});


	$('body').on('click','.main_search_label ',function(e){
		$('.main_search_label').hide();
		$('#main_search').focus();

		
		var search_position = $('.first_page_headline_container').offset();
		var move_to_top_amount = 0 - search_position.top + 110;

		$('.first_page_headline_container').animate({
			top: move_to_top_amount
		}, {
			duration: 300,
			easing: "swing",
			complete: function(){

				$('.first_page_titles').animate({
					opacity: 0
				}, {
					duration: 150,
					easing: "swing",
					complete: function(){

						$('.first_page_titles').hide();
						$('.second_page_titles').show();

						$('.second_page_titles').animate({
							opacity: 1
						}, {
							duration: 150,
							easing: "swing",
							complete: function(){

								

							}
						});

					}
				});

			}
		});


	});


	/* ************************** SEARCH ENGINE **************************** */
	/*
	$.ajax({
		type: 'POST',
		url: "/includes/search/ajax.php",
		data: 'get_repositories=get_by_names',
		cache: false,
		success: function(html){
			var repositories = html.split('||');
		

			$('#main_search').autoComplete({
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
						$.fn.fullpage.setAllowScrolling(false);
						return '<div class="autocomplete-suggestion" data-name="'+item+'">'+item+'</div>';
					},
					onSelect: function(e, term, item){
						
					}
			});
		}
	});
	*/
	$('#main_search').keyup(function() {
		var keyword_typed = $(this).val();

		if(keyword_typed.length >= 2){
			$.ajax({
				url: "/includes/search/ajax.php",
				type: 'post',
				data: {"keyword": keyword_typed},
				success: function(response) {
					$.fn.fullpage.setAllowScrolling(false);

					$('#search_ajax_results').show();

					var searchResultsHeight = $('body').outerHeight() * 0.6;
					$('#search_ajax_results').css('height',searchResultsHeight+"px");
					$('#search_ajax_results .search_content').html(response);

					var lineHeight = 36;
					var itemsTotalHeight = (lineHeight * $('.autocomplete-suggestion').length) + (42 * $('.search_title').length);
					if(itemsTotalHeight < searchResultsHeight){
						$('#search_ajax_results').css('height',itemsTotalHeight+"px");
					}
					else{
						$('#search_ajax_results').css('height',searchResultsHeight+"px");
					}
				}
			});
		}
		else{
			$('#search_ajax_results').hide();
			$.fn.fullpage.setAllowScrolling(true);
		}
	});


	//************************************ PDF VIEWER ***************************************//

	$('body').on('click','.open_in_pdf_viewer',function(e){
		e.preventDefault();

		var repository_id = $(this).data("search_repository_id");

		$.ajax({
			url: "includes/common/ajax.php",
			type: 'POST',
			data: {
				open_in_pdf_viewer: true,
				repository_id: repository_id
			},
			cache: false,
			success: function(pdf_link){

				$("#pdf_viewer_window").flipBook({
					pdfUrl:pdf_link,
					lightBox:true,
					lightBoxOpened:true,
					skin:"dark"
				});

			}
		});	
	});



	//************************************ DIRECT PDF DOWNLOAD ***************************************//
	$('body').on('click','.direct_download',function(e){
		e.preventDefault();

		var repository_id = $(this).data("search_repository_id");

		$.ajax({
			url: "includes/common/ajax.php",
			type: 'POST',
			async: false,
			data: {
				direct_pdf_download: true,
				repository_id: repository_id
			},
			cache: false,
			success: function(pdf_link){
				$('#main_container').removeClass('swal-overview-details-bg');

				$(document).ready(function(){
					
					/* 1. Visualizing things on Hover - See next part for action on click */
					$('#stars li').on('mouseover', function(){
						var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
					 
						// Now highlight all the stars that's not after the current hovered star
						$(this).parent().children('li.star').each(function(e){
							if (e < onStar) {
								$(this).addClass('hover');
							}
							else {
								$(this).removeClass('hover');
							}
						});
						
					}).on('mouseout', function(){
						$(this).parent().children('li.star').each(function(e){
							$(this).removeClass('hover');
						});
					});

					/* 2. Action to perform on click */
					$('#stars li').on('click', function(){
						var onStar = parseInt($(this).data('value'), 10); // The star currently selected
						var stars = $(this).parent().children('li.star');

						for (i = 0; i < stars.length; i++) {
							$(stars[i]).removeClass('selected');
						}
						for (i = 0; i < onStar; i++) {
							$(stars[i]).addClass('selected');
						}

					});

					/* 3. Action to perform on send button click */
					$('body').on('click','.rating_button',function(e){

						var rating_comment = $('#swal2-content #rating_comment_text').val();

						var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
						if (ratingValue > 1) {
							rating = ratingValue;
						}
						else {
							rating = 0;
						}

						//console.log(rating_comment+' / '+rating);

						$.ajax({
							url: "includes/common/ajax.php",
							type: 'POST',
							data: {
								send_rating: true,
								rating_comment: rating_comment,
								rating: rating
							},
							cache: false,
							success: function(html){
								$("#swal2-content #rating_popup_content").animate({
									opacity: 0,
								}, 500,'swing', function() {
									$("#swal2-content #rating_popup_content").hide();
									$("#swal2-content #rating_popup_sent").show();
									$("#swal2-content #rating_popup_sent").animate({
										opacity: 1,
									}, 500,'swing', function() {
										setTimeout(function(){
											swal.close();
										}, 1500);
									});
								});
							}
						});	

					});

				});






				if(pdf_link != 'no pdf for this repository'){
					//pol - popups must be allowed for this domain!
					var redirectWindow = window.open(pdf_link, '_blank');
							redirectWindow.location;
				}

				swal({
					html: $('#rating_popup').html(),
					customClass: 'swal-wide',
					showCloseButton: true,
					showCancelButton: false,
					showConfirmButton: false,
					animation: true
				});

			}
		});	


	});



	//************************************ ADD TO DOWNLOAD MANAGER ***************************************//
	$('body').on('click','.add_to_download_manager',function(e){
		e.preventDefault();

		var repository_id = $(this).data("search_repository_id");

		$.ajax({
			url: "includes/common/ajax.php",
			type: 'POST',
			data: {
				add_to_download_manager: true,
				repository_id: repository_id
			},
			cache: false,
			success: function(html){
				refresh_download_manager();
				$('.dmc_repositories_container').append(html);
			}
		});	
	});


	//************************************ REMOVE FROM DOWNLOAD MANAGER ***************************************//
	$('body').on('click','.remove_from_download_manager',function(e){
		e.preventDefault();

		var repository_id = $(this).data("search_repository_id");

		$.ajax({
			url: "includes/common/ajax.php",
			type: 'POST',
			data: {
				remove_from_download_manager: true,
				repository_id: repository_id
			},
			cache: false,
			success: function(html){
				refresh_download_manager();
				$('#dmi_'+repository_id).remove();
			}
		});	
	});


	//************************************ DOWNLOAD ZIPPED FROM DOWNLOAD MANAGER ***************************************//
	$('body').on('click','.download_zipped',function(e){
		e.preventDefault();

		$.ajax({
			url: "includes/common/ajax.php",
			type: 'POST',
			async: false,
			data: {
				download_zipped: true
			},
			cache: false,
			success: function(zip_link){
				var redirectWindow = window.open(zip_link, '_blank');
						redirectWindow.location;

				$('#main_container').removeClass('swal-overview-details-bg');

				$(document).ready(function(){
					
					/* 1. Visualizing things on Hover - See next part for action on click */
					$('#stars li').on('mouseover', function(){
						var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
					 
						// Now highlight all the stars that's not after the current hovered star
						$(this).parent().children('li.star').each(function(e){
							if (e < onStar) {
								$(this).addClass('hover');
							}
							else {
								$(this).removeClass('hover');
							}
						});
						
					}).on('mouseout', function(){
						$(this).parent().children('li.star').each(function(e){
							$(this).removeClass('hover');
						});
					});

					/* 2. Action to perform on click */
					$('#stars li').on('click', function(){
						var onStar = parseInt($(this).data('value'), 10); // The star currently selected
						var stars = $(this).parent().children('li.star');

						for (i = 0; i < stars.length; i++) {
							$(stars[i]).removeClass('selected');
						}
						for (i = 0; i < onStar; i++) {
							$(stars[i]).addClass('selected');
						}

					});

					/* 3. Action to perform on send button click */
					$('body').on('click','.rating_button',function(e){

						var rating_comment = $('#swal2-content #rating_comment_text').val();

						var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
						if (ratingValue > 1) {
							rating = ratingValue;
						}
						else {
							rating = 0;
						}

						//console.log(rating_comment+' / '+rating);

						$.ajax({
							url: "includes/common/ajax.php",
							type: 'POST',
							data: {
								send_rating: true,
								rating_comment: rating_comment,
								rating: rating
							},
							cache: false,
							success: function(html){
								$("#swal2-content #rating_popup_content").animate({
									opacity: 0,
								}, 500,'swing', function() {
									$("#swal2-content #rating_popup_content").hide();
									$("#swal2-content #rating_popup_sent").show();
									$("#swal2-content #rating_popup_sent").animate({
										opacity: 1,
									}, 500,'swing', function() {
										setTimeout(function(){
											swal.close();
										}, 1500);
									});
								});
							}
						});	

					});

				});

				swal({
					html: $('#rating_popup').html(),
					customClass: 'swal-wide',
					showCloseButton: true,
					showCancelButton: false,
					showConfirmButton: false,
					animation: true
				});
			}
		});	
	});


	//************************************ SHARE & SEND ***************************************//
	$('body').on('click','.share_and_send',function(e){
		e.preventDefault();

		$.ajax({
			url: "includes/common/ajax.php",
			type: 'POST',
			data: {
				share_and_send: true
			},
			cache: false,
			success: function(html){
				swal({
					html: html,
					customClass: 'swal-wide',
					showCloseButton: true,
					showCancelButton: false,
					showConfirmButton: false,
					animation: true
				});
			}
		});	
	});

	//***** COPY TO CLIPBOARD *****//
	$('body').on('click','.button_copy_url',function(e){

		$('#repository_set_url').focus();
		$('#repository_set_url').select();
		document.execCommand('copy');

		$('.button_copy_url_confirmation').css('display','flex');
	});

	//***** SEND MAIL *****//
	function IsEmail(email) {
		var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!regex.test(email)) {
			return false;
		}else{
			return true;
		}
	}

	$('body').on('click','.share_button_send',function(e){
		e.preventDefault();

		var repository_set_url = $('#repository_set_url').val();
		var share_recipients = $('#share_recipients').val().replace(/\s/g,'');
		var share_mail_text = $('#share_mail_text').val();

		var share_recipients_array = share_recipients.split(",");

		var send_flag = 0;

		share_recipients_array.forEach(function(share_recipient_email) {
			if(IsEmail(share_recipient_email) == false){
				$('.share_recipients_wrong_email').show();
				send_flag = 0;
			}
			else{
				send_flag = send_flag+1;
			}
		});

		if(send_flag == share_recipients_array.length){
			$('.share_recipients_wrong_email').hide();
			//console.log('sending');
			$.ajax({
				url: "includes/common/ajax.php",
				type: 'POST',
				data: {
					send_share_mail: true,
					repository_set_url: repository_set_url,
					share_recipients: share_recipients,
					share_mail_text: share_mail_text
				},
				cache: false,
				success: function(html){
					//console.log(html);
					$('.custom_popup_front').animate({
						opacity: 0
					}, 500, function() {
						$('.custom_popup_front').hide();
						$('.custom_popup_front_confirmation').show();
						$('.custom_popup_front_confirmation').animate({
							opacity: 1
						}, 500, function() {
							setTimeout(
								function() {
									swal.close();
								}, 2000);
						});
					});
				}
			});	
		}		
	});


	//************************************ OVERVIEW: LOAD ALL ***************************************//
	$('body').on('click','.overview_show_all_button',function(e){
		e.preventDefault();

		$.ajax({
			url: "includes/overview/ajax.php",
			type: 'POST',
			data: {
				load_all: true
			},
			cache: false,
			success: function(html){
				$('.overview_table_content').empty();
				$('.overview_show_all_button').hide();
				$('.overview_table_content').append(html);
			}
		});	
	});


	//************************************ OVERVIEW: REPOSITORY BLUR DETAILS ***************************************//
	$('body').on('click','.overview_table_line',function(e){
		e.preventDefault();

		var repository_id = $(this).data("overview_repository_id");

		function blursite(){
			$('#main_container').removeClass('swal-overview-details-bg');
		}

		$.ajax({
			url: "includes/overview/ajax.php",
			type: 'POST',
			data: {
				load_repository_details: true,
				repository_id: repository_id
			},
			cache: false,
			success: function(html){
				$('#main_container').addClass('swal-overview-details-bg');
				swal({
					html: html,
					customClass: 'swal-overview-details',
					customContainerClass: 'swal-overview-details-bg-color',
					allowEscapeKey: true,
					showCloseButton: false,
					background: 'none',
					showCancelButton: false,
					showConfirmButton: false,
					animation: false,
					onClose: blursite
				});
			}
		});	
	});

	$('body').on('click','.overview_details_close',function(e){
		swal.close();
		$('#main_container').removeClass('swal-overview-details-bg');
	});



});


//************************************ REFRESH DOWNLOAD MANAGER ***************************************//
function refresh_download_manager(){
	if(Cookies.get('dm_repos')){
		var dm_repos_content = JSON.parse(Cookies.get('dm_repos'));
		//console.log(dm_repos_content);
		var repos_in_dm = dm_repos_content.length;
		if(repos_in_dm > 0){
			$('#download_manager_counter').show();
			$("#download_manager_counter").empty();
			$("#download_manager_counter").append(repos_in_dm);
			$("#download_manager_content").addClass('dmc_visible');
		}
		else{
			$('#download_manager_counter').hide();
			$("#download_manager_counter").empty();
			$("#download_manager_content").removeClass('dmc_visible');
		}
	}
}


$(window).on('load', function(){

	refresh_download_manager();

	setTimeout(
		function() {
			$('.first_page_headline').animate({
				opacity: 1
			}, {
				duration: 500,
				easing: "swing",
				complete: function(){

					$('.first_page_subheadline').animate({
						opacity: 1
					}, {
						duration: 500,
						easing: "swing",
						complete: function(){

							$('#main_search').animate({
								opacity: 1,
								width: "1000px"
							}, {
								duration: 300,
								easing: "swing",
								complete: function(){

									$('.search_input i').css("opacity",1);
									
									var typed = new Typed('#typed', {
										typeSpeed: 50,
										startDelay: 500,
										showCursor: false,
										strings: ["Arcal^1000, ALbee^500, Helium^700, Argon..."]
									});

								}
							});

						}
					});

				}
			});
		}, 
	1000);
	
});


	//************************************ CONTACT PAGE ***************************************//
	$(document).ready(function() {
		$("#send").prop('disabled',true);
		$("#send").removeClass('button_active');

		$('body').on('click','.btr-accept-ui',function(e){
			if($(".btr-accept-ui").hasClass('agreement_checked')){
				$(".btr-accept-ui").removeClass('agreement_checked');
				$("#send").prop('disabled',true);
				$("#send").removeClass('button_active');
			}
			else{
				$(".btr-accept-ui").addClass('agreement_checked');
				$("#send").prop('disabled',false);
				$("#send").addClass('button_active');
			}
		});

		$('body').on('click','#send',function(e){

			var contact_name = $('#contact_name').val();
			var contact_mail = $('#contact_mail').val();
			var contact_title = $('#contact_title').val();
			var contact_phone = $('#contact_phone').val();
			var contact_msg = $('#contact_msg').val();

			$.ajax({
				url: "includes/common/ajax.php",
				type: 'POST',
				data: {
					send_contact_mail: true,
					contact_name: contact_name,
					contact_mail: contact_mail,
					contact_title: contact_title,
					contact_phone: contact_phone,
					contact_msg: contact_msg
				},
				cache: false,
				success: function(html){
					//console.log(html);
					$('#contact_content').animate({
						opacity: 0
					}, 500, function() {
						$('#contact_content').hide();
						$('#kontakt_form_confirmation').show();
						$('#kontakt_form_confirmation').animate({
							opacity: 1
						}, 500, function() {

						});
					});
				}
			});
		});
	});


$("#fullpage").on('scroll', function(){
	console.log('scrolling');
	if($('.search_section').hasClass('fp-completely')){
		console.log('search_section');
	}
	if($('.overview_section').hasClass('fp-completely')){
		console.log('overview_section ');
	}
	if($('.contact_section').hasClass('fp-completely')){
		console.log('contact_section');
	}
});