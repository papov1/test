$(document).ready(function () {

	$('body').on('click','#google_login_form_button',function(e){
		e.preventDefault();

		$('#user_name').parent().find('.form_warning_msg').removeClass('form_warning_msg_show');
		$('#user_lastname').parent().find('.form_warning_msg').removeClass('form_warning_msg_show');
		$('#user_email').parent().find('.form_warning_msg').removeClass('form_warning_msg_show');

		var user_name = $('#user_name').val();
		var user_lastname = $('#user_lastname').val();
		var user_email = $('#user_email').val();

		var continue_flag = 0;

		if(user_name.length == 0){
			$('#user_name').parent().find('.form_warning_msg').addClass('form_warning_msg_show');
			var continue_flag = 0;
		}
		else{
			$('#user_name').parent().find('.form_warning_msg').removeClass('form_warning_msg_show');
			var continue_flag = continue_flag + 1;
		}

		if(user_lastname.length == 0){
			$('#user_lastname').parent().find('.form_warning_msg').addClass('form_warning_msg_show');
			var continue_flag = 0;
		}
		else{
			$('#user_lastname').parent().find('.form_warning_msg').removeClass('form_warning_msg_show');
			var continue_flag = continue_flag + 1;
		}

		if(user_email.length == 0){
			$('#user_email').parent().find('.form_warning_msg').addClass('form_warning_msg_show');
			var continue_flag = 0;
		}
		else{
			$('#user_email').parent().find('.form_warning_msg').removeClass('form_warning_msg_show');
			var continue_flag = continue_flag + 1;
		}

		if(continue_flag > 1){
			$.ajax({
				url: "ajax.php",
				type: 'POST',
				data: {
					user_name: user_name,
					user_lastname: user_lastname,
					user_email: user_email
				},
				cache: false,
				success: function(response){
					if(response == 'user_added'){
						
						$('.login_container').animate({
							opacity: 0
						}, 500, function() {
							$('.login_container').hide();
							$('.added_confirmation').show();
							$('.added_confirmation').animate({
								opacity: 1
							}, 500, function() {
								
							});
						});

					}

					if(response == 'user_already_in_the_queue'){
						$('.login_failed_1').show();
					}

					if(response == 'user_already_registered'){
						$('.login_failed_2').show();
					}
				}
			});	
		}
		else{
			//console.log('errors found, can\'t continue');
		}

	});


});
