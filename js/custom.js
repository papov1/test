$(document).ready(function() {

	/* Helper function */
	function download_file(fileURL, fileName) {
			// for non-IE
			if (!window.ActiveXObject) {
					var save = document.createElement('a');
					save.href = fileURL;
					save.target = '_blank';
					var filename = fileURL.substring(fileURL.lastIndexOf('/')+1);
					save.download = fileName || filename;
					 if ( navigator.userAgent.toLowerCase().match(/(ipad|iphone|safari)/) && navigator.userAgent.search("Chrome") < 0) {
					document.location = save.href; 
	// window event not working here
				}else{
					//This is true only for IE,firefox
					if(document.createEvent){
						// To create a mouse event , first we need to create an event and then initialize it.
						evt = document.createEvent("MouseEvent");
						evt.initMouseEvent("click",true,true,window,0,0,0,0,0,false,false,false,false,0,null);
					}else{
						 evt = new MouseEvent('click', {
									'view': window,
									'bubbles': true,
									'cancelable': false
							});
					}

					save.dispatchEvent(evt);
					(window.URL || window.webkitURL).revokeObjectURL(save.href);

					if(navigator.userAgent.indexOf('MSIE')!==-1 || navigator.appVersion.indexOf('Trident/') > -1){
						window.open(save.href);
					}
				}
			}

			// for IE < 11
			else if ( !! window.ActiveXObject && document.execCommand)     {
					var _window = window.open(fileURL, '_blank');
					_window.document.close();
					_window.document.execCommand('SaveAs', true, fileName || fileURL)
					_window.close();
			}
	}

	if($('.dm_share_notification').length) {
		setTimeout(
			function() 
			{
				$('.dm_share_notification').show();
				$('.dm_share_notification').animate({
					opacity: 1
				}, 1500, function() {
										
				});
			}, 2000
		);
	}

	$('.download_manager_button').on('mouseover', function(){
		$('.dm_share_notification').animate({
			opacity: 0
		}, 500, function() {
			$('.dm_share_notification').hide();	
		});
	}).on('mouseout', function(){
		
	});
	
	/*
	$('#fullpage').fullpage({
		autoScrolling:true,
		scrollHorizontally: true,
		normalScrollElements: '.autocomplete-suggestions, #search_ajax_results, .overview_table_material, .overview_table_brandgas',
		licenseKey: '497E3AB4-23D6449B-B214FCEA-37171995',
		anchors: ['suche', 'ubersicht', 'kontakt', 'rechner', 'end'],
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
				if(index.anchor == 'rechner'){

				}
			}
	});	
	*/


	$('body').on('click','.top_link ',function(e){
		swal.close();
		$('#main_container').removeClass('swal-overview-details-bg');
		$('#navbar').animate({
			height: 80
		}, 300, function() {

		});
	});


	$('body').on('click touchstart','.main_search_label ',function(e){
		$('.main_search_label').hide();
		$('#main_search').focus();


		var full_section_height = $('.calculator_section').outerHeight();
		$('.search_section').animate({
			height: full_section_height
		}, {
			duration: 150,
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
								$("html, body").stop().animate({scrollTop:0}, 300);
								//$("html, body").stop().animate({ scrollTop: $('.search_section').offset().top -50 }, 300);

								$('.search_input i#cancel_search').css("opacity",1);
							}
						});
					}
				});

			}
		});

	});


	$('body').on('click','i#cancel_search ',function(e){
		$('#main_search').val('');
		$('.main_search_label').show();
		$('#search_ajax_results').hide();
		
		$('.search_input i#cancel_search').css("opacity",0);
		var small_section_height = $('.first_page_headline_container').outerHeight() + 37;

		$('.search_section').animate({
			height: small_section_height
		}, {
			duration: 150,
			easing: "swing",
			complete: function(){

				$('.first_page_titles').animate({
					opacity: 0
				}, {
					duration: 150,
					easing: "swing",
					complete: function(){
						$('.first_page_titles').show();
						$('.second_page_titles').hide();
						$('.first_page_titles').animate({
							opacity: 1
						}, {
							duration: 150,
							easing: "swing",
							complete: function(){
								$("html, body").stop().animate({scrollTop:0}, 300);
								//$("html, body").stop().animate({ scrollTop: $('.search_section').offset().top -50 }, 300);
							}
						});
					}
				});

			}
		});

	});


	/* ************************** SEARCH ENGINE **************************** */
	function searchKeyword(keyword_typed) {
		console.log('search start');
		$.ajax({
			url: "/includes/search/ajax.php",
			type: 'post',
			data: {"keyword": keyword_typed},
			success: function(response) {
				console.log('search result');
				//$.fn.fullpage.setAllowScrolling(false);

				$('#search_ajax_results').show();

				var searchResultsHeight = $('.calculator_section').outerHeight() * 0.6;
				//$('#search_ajax_results').css('height',searchResultsHeight+"px");
				$('#search_ajax_results .search_content').html(response);

				var lineHeight = 36;
				var itemsTotalHeight = (lineHeight * $('.autocomplete-suggestion').length) + (42 * $('.search_title').length);
				if(itemsTotalHeight < searchResultsHeight){
					//$('#search_ajax_results').css('height',itemsTotalHeight+"px");
				}
				else{
					//$('#search_ajax_results').css('height',searchResultsHeight+"px");
				}

				var searchResultsHeight = $('.calculator_section').outerHeight() * 0.6;
				$('#search_ajax_results').css('max-height',searchResultsHeight+"px");
			}
		});
	}

	var timer = null;
	$('#main_search').keyup(function() {
		clearTimeout(timer); 
		var keyword_typed = $(this).val();
		timer = setTimeout(function(){
			if(keyword_typed.length >= 2){
				searchKeyword(keyword_typed);
			}
			else{
				$('#search_ajax_results').hide();
			}
		}, 500);
	});

	$('body').on('click','.relation_closed ',function(e){
		console.log('clicked');
		$(this).removeClass('relation_closed').addClass('relation_opened');
		$(this).find('i').removeClass('pe-7s-angle-right').addClass('pe-7s-angle-down');

		$(this).parent().parent().find('.repository_related_container').css('height','auto');
	});

	$('body').on('click','.relation_opened ',function(e){
		console.log('clicked');
		$(this).removeClass('relation_opened').addClass('relation_closed');
		$(this).find('i').removeClass('pe-7s-angle-down').addClass('pe-7s-angle-right');

		$(this).parent().parent().find('.repository_related_container').css('height','0');
	});

	$('body').on('click','.has_relations.repository_name',function(e){
		$(this).removeClass('has_relations').addClass('has_relations_opened');

		$(this).parent().find('.repository_related_container').css('height','auto');
	});

	$('body').on('click','.has_relations_opened.repository_name',function(e){
		$(this).removeClass('has_relations_opened').addClass('has_relations');

		$(this).parent().find('.repository_related_container').css('height','0');
	});


	//************************************ PDF VIEWER ***************************************//

	$('body').on('click','.open_in_pdf_viewer',function(e){
		e.preventDefault();

		var repository_id = $(this).data("search_repository_id");
		var pdf_name = $(this).data("pdf-name");

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
					skin:"dark",
					btnDownloadPdf: {
						forceDownload:true,
						icon: "fa-download",
						icon2: "file_download",
						enabled:true,
						name: pdf_name
					}
				});

			}
		});	
	});



	//************************************ DIRECT PDF DOWNLOAD ***************************************//
	$('body').on('click','.direct_download',function(e){
		e.preventDefault();

		var repository_id = $(this).data("search_repository_id");
		var pdf_name = $(this).data("pdf-name");

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
				

				if(pdf_link != 'no pdf for this repository'){
					download_file(pdf_link, pdf_name);
					//pol - popups must be allowed for this domain!
					//var redirectWindow = window.open(pdf_link, '_blank');
					//		redirectWindow.location;
				}

				setTimeout(
				  function() 
				  {

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
				    	animation: true,
				    	onOpen: function() { 
				    		$('#swal2-content #rating_popup_content').show();
				    		$('#swal2-content #rating_popup_content').css("opacity",1);
				    	}
				    });
				  }, 1500);

				
				

			}
		});	


	});



	//************************************ ADD TO DOWNLOAD MANAGER ***************************************//
	$('body').on('click','.add_to_download_manager',function(e){
		e.preventDefault();

		var repository_id = $(this).data("search_repository_id");
		
		$(this).addClass('make_invisible');
		$(".added_to_download_manager[data-search_repository_id='"+repository_id+"']").removeClass('make_invisible');

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

		$(".add_to_download_manager[data-search_repository_id='"+repository_id+"']").removeClass('make_invisible');
		$(".added_to_download_manager[data-search_repository_id='"+repository_id+"']").addClass('make_invisible');

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

				//check if cookie consent is turned ON
				$.ajax({
					url: "includes/common/ajax.php",
					type: 'POST',
					data: {
						cookie_banner_check: true
					},
					cache: false,
					success: function(response){
						console.log(response);
						if(response == 'off'){
							var cookie_consent_status = 'off';
						}
						else{
							var cookie_consent_status = 'on';
						}

						if((Cookies.get('cookie_consent')) || (cookie_consent_status == 'off')){
							var cookie_consent = Cookies.get('cookie_consent');
							console.log(cookie_consent);

							if((cookie_consent == 'cookies_accepted') || (cookie_consent_status == 'off')){
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
									animation: true,
									onOpen: function() { 
										$('#swal2-content #rating_popup_content').show();
										$('#swal2-content #rating_popup_content').css("opacity",1);
									}
								});
							}
						}

					}
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

		var first_section_height = $(window).outerHeight();
		$('.overview_section').css('height',first_section_height);

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

				var section_height = $('.calculator_section').outerHeight();
				var max_table_height = section_height * 0.6;
				$('.overview_table_content').css('max-height',max_table_height);
				

				$(".overview_table_container_inner").mouseenter(function(){
					//$.fn.fullpage.setAllowScrolling(false);
					//console.log('scroll blocked');
				}).mouseleave(function(){
					//$.fn.fullpage.setAllowScrolling(true);
					//console.log('scroll allowed');
				});

				$("html, body").stop().animate({ scrollTop: $('.overview_section').offset().top -50 }, 500);
					
			}
		});	
	});


	//************************************ OVERVIEW: REPOSITORY BLUR DETAILS ***************************************//
	$('body').on('click','.overview_table_line',function(e){
		e.preventDefault();

		var repository_id = $(this).data("overview_repository_id");

		function blursite(){
			$('#main_container').removeClass('swal-overview-details-bg');
			$('#navbar').animate({
				height: 80
			}, 300, function() {

			});
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

				$('#navbar').animate({
					height: 65
				}, 300, function() {

				});

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
		$('#navbar').animate({
			height: 80
		}, 300, function() {

		});
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

	//set height for sections
	//var first_section_height = $('.search_section').outerHeight();
	var first_section_height = $(window).outerHeight();
	//$('.overview_section').css('height',first_section_height);
	$('.contact_section').css('height',first_section_height);
	$('.calculator_section').css('height',first_section_height);

	//check if pre is used
	if($('#main_search').val() != ''){
		var prefilled = $('#main_search').val();

		$('.main_search_label').hide();
		$('#main_search').focus();


		var full_section_height = $('.calculator_section').outerHeight();
		$('.search_section').animate({
			height: full_section_height
		}, {
			duration: 150,
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
								$("html, body").stop().animate({scrollTop:0}, 300);
								//$("html, body").stop().animate({ scrollTop: $('.search_section').offset().top -50 }, 300);

								$('.search_input i#cancel_search').css("opacity",1);
							}
						});
					}
				});

			}
		});


		var keyword_typed = prefilled;
		if(keyword_typed.length >= 2){
			$.ajax({
				url: "/includes/search/ajax.php",
				type: 'post',
				data: {"keyword": keyword_typed},
				success: function(response) {
					$('#search_ajax_results').show();

					var searchResultsHeight = $('.calculator_section').outerHeight() * 0.6;
					$('#search_ajax_results .search_content').html(response);

					var lineHeight = 36;
					var itemsTotalHeight = (lineHeight * $('.autocomplete-suggestion').length) + (42 * $('.search_title').length);

					var searchResultsHeight = $('.calculator_section').outerHeight() * 0.6;
					$('#search_ajax_results').css('max-height',searchResultsHeight+"px");
				}
			});
		}
		else{
			$('#search_ajax_results').hide();
		}

		//show immediatelly instead of smooth animations
		$('.main_search_label').hide();
		$('#main_search').val('');
		$('#main_search').val(prefilled).focus().val(prefilled);
		$('#main_search').css('width','100%');
		$('#main_search').css('opacity',1);
		var full_section_height = $('.calculator_section').outerHeight();
		$('.search_section').css('height',full_section_height);
		$('.first_page_titles').css('opacity',0);
		$('.first_page_titles').hide();
		$('.second_page_titles').show();
		$('.second_page_titles').css('opacity',1);
		$('.search_input i#cancel_search').css("opacity",1);
		$("html, body").stop().animate({scrollTop:0}, 300);
		$('.search_input i.pe-7s-search').css("opacity",1);
		$('.overview_section').css("opacity",1);

	}
	else{
		//load regular animations
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
									width: "100%"
								}, {
									duration: 300,
									easing: "swing",
									complete: function(){

										$('.search_input i.pe-7s-search').css("opacity",1);
										
										var typed = new Typed('#typed', {
											typeSpeed: 50,
											startDelay: 500,
											showCursor: false,
											strings: ["Arcal^1000, ALbee^500, Helium^700, Argon..."]
										});

										//aminate text under search
										$('.first_page_subheadline_bottom').animate({
											opacity: 1
										}, {
											duration: 500,
											easing: "swing",
											complete: function(){

												//animate ubersicht
												$('.overview_section').animate({
													opacity: 1
												}, {
													duration: 500,
													easing: "swing",
													complete: function(){
														
													}

												});

											}

										});

									}
								});

							}
						});

					}
				});
			}, 
		1000);
	}


	refresh_download_manager();

	
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


/* ******************************************************************************* */
/* ****************************** LANG HANDLING ********************************** */
/* ******************************************************************************* */
$(document).ready(function () {

	$('body').on('click','.select_lang_button',function(e){
		e.preventDefault;

		var lang_id = $(this).data("lang-id-nav");
		console.log('clicked '+lang_id);
		$.ajax({
			type: 'POST',
			url: "includes/common/ajax.php",
			data: {
				change_language: true,
				lang_id: lang_id
			},
			cache: false,
			success: function(html){
				//console.log(html);
				location.reload();
				//window.location.replace(html);
			}
		});
	});

	$('body').on('click','.lang_name',function(e){
		e.preventDefault;
		e.stopPropagation();

		var lang_id = $(this).data("lang-id");
		console.log('clicked '+lang_id);
		$.ajax({
			type: 'POST',
			url: "includes/common/ajax.php",
			data: {
				change_language: true,
				lang_id: lang_id
			},
			cache: false,
			success: function(html){
				//console.log(html);
				location.reload();
				//window.location.replace(html);
			}
		});
	});

});



/* ******************************************************************************* */
/* ********************************* COOKIE BAR ********************************** */
/* ******************************************************************************* */
$(document).ready(function () {

	$('body').on('click','.cookie_banner_inner_container',function(e){
		$('.cookie_banner_inner_container').animate({
			'max-height': 0
		}, 900, function() {
			$('.cookie_banner_inner_container_second').animate({
				'max-height': 700
			}, 900, function() {
				
			});
		});
	});

	$('body').on('click','.cookie_banner_back',function(e){
		$('.cookie_banner_inner_container_second').animate({
			'max-height': 0
		}, 900, function() {
			$('.cookie_banner_inner_container').animate({
				'max-height': 700
			}, 900, function() {
				
			});
		});
	});
	

	$('body').on('click','.cookieOption1',function(e){
		Cookies.set('cookie_consent', 'cookies_accepted', { expires: 7, path: '' });
		hideCookieBanner();
	});

	$('body').on('click','.cookieOption2',function(e){
		Cookies.set('cookie_consent', 'cookies_rejected', { expires: 7, path: '' });
		hideCookieBanner();
	});
	
	/*if(window.location.href == 'https://datenblatt.online/?show'){*/

		//showCookieBanner(); //remove later
		
		if(Cookies.get('cookie_consent')){
			var cookie_consent = Cookies.get('cookie_consent');
			console.log(cookie_consent);

			if(cookie_consent == 'not_selected'){
				showCookieBanner();
			}
		}
		else{
			showCookieBanner();
			Cookies.set('cookie_consent', 'not_selected', { expires: 7, path: '' });
		}


	/*}*/


	function showCookieBanner(){
		$('#cookie_banner_overlay').show();
		$('#cookie_banner_overlay').animate({
			opacity: 1
		}, 1000, function() {
			
			$('.cookie_banner_container').animate({
				top: 0
			}, 500, function() {
				//animations ended, banner visible
			});

		});
	}


	function hideCookieBanner(){

		$('.cookie_banner_container').animate({
			top: -700
		}, 500, function() {

			$('#cookie_banner_overlay').animate({
				opacity: 0
			}, 1000, function() {
				$('#cookie_banner_overlay').hide();
			});

		});
	}

	$(document).on('click', '.top_link', function (event) {
	    event.preventDefault();

	    if($(this).attr("data-menuanchor") == 'search_section'){
	    	$('html, body').animate({
	    	  scrollTop: $($.attr(this, 'href')).offset().top - 100
	    	}, 500);
	    }
	    else if($(this).attr("data-menuanchor") == 'overview_section'){
	    	$('html, body').animate({
	    	  scrollTop: $($.attr(this, 'href')).offset().top - 50
	    	}, 500);
	    }
	    else{
	    	$('html, body').animate({
	    	  scrollTop: $($.attr(this, 'href')).offset().top
	    	}, 500);
	    }

	    $('.mobile_menu').removeClass('menu-opened');
	});

	$(document).on('click', '.search_activate', function (event) {
	    event.preventDefault();

	    $('html, body').animate({
	      scrollTop: $('#suche').offset().top - 100
	    }, 500);
	});

	$('body').on('click','.pe-7s-menu',function(e){
		if($('.mobile_menu').hasClass('menu-opened')){
			$('.mobile_menu').removeClass('menu-opened');
		}
		else{
			$('.mobile_menu').addClass('menu-opened');
		}
	});

	$('body').on('click touchend','.autocomplete-suggestion',function(e){
		$('.autocomplete-suggestion').removeClass('asopen');
		$(this).addClass('asopen');
	});

	$('body').on('click touchend','.sdb_section',function(e){
		$('.pdb_section').removeClass('mobile_section_active');
		$('.pdb_section').addClass('mobile_section_inactive');
		
		$('.sdb_section').removeClass('mobile_section_inactive');
		$('.sdb_section').addClass('mobile_section_active');

		$('.overview_table_sdb_material').show();
		$('.overview_table_sdb_brandgas').show();
		$('.overview_table_sdb_material').removeClass('hidemobile');
		$('.overview_table_sdb_brandgas').removeClass('hidemobile');
		$('.overview_table_pdb_material').addClass('hidemobile');
		$('.overview_table_pdb_brandgas').addClass('hidemobile');
	});

	$('body').on('click touchend','.pdb_section',function(e){
		$('.sdb_section').removeClass('mobile_section_active');
		$('.sdb_section').addClass('mobile_section_inactive');
		
		$('.pdb_section').removeClass('mobile_section_inactive');
		$('.pdb_section').addClass('mobile_section_active');

		$('.overview_table_sdb_material').addClass('hidemobile');
		$('.overview_table_sdb_brandgas').addClass('hidemobile');
		$('.overview_table_pdb_material').removeClass('hidemobile');
		$('.overview_table_pdb_brandgas').removeClass('hidemobile');
		$('.overview_table_pdb_material').show();
		$('.overview_table_pdb_brandgas').show();
	});

	

});