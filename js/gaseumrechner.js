$(document).ready(function(){
	var current_gas = null;
	var current_unit = null;
	var entered_value = null;

	if($('.gaseumrechner').length > 0)
	{
		$('.gaseumrechner').each(function(i, e){
			toggle_legend(e);
		
			$(e).find('input:text').keydown(function(event){
				return is_calculate_keycode(event.keyCode) || is_do_not_calculate_keycode(event.keyCode);
			});

			$(e).find('input:text').keyup(function(event){
				
				if(is_calculate_keycode(event.keyCode) || is_do_not_calculate_keycode(event.keyCode))
				{
					$(e).find('input:text').removeClass('source');
					$(this).addClass('source');
					
					if(is_calculate_keycode(event.keyCode))
					{
						entered_value = commatron($(this).val());
						
						for(i = 0; i < gase_data.gas.length; i++)
						{
							if(gase_data.gas[i]['@attributes'].id == $(this).parents('.gaseumrechner').find('[name="gas"]').val())
							{
								current_gas = gase_data.gas[i];
							}
						}
						
						for(i = 0; i < current_gas.einheit.length; i++)
						{
							if(current_gas.einheit[i]['@attributes'].id == $(this).attr('id'))
							{
								current_unit = current_gas.einheit[i];
							}
						}
						
						$(e).find('input:text').each(function(j, f){
							if(!$(f).hasClass('source'))
								$(f).val(commatron(toFixed((current_unit['@attributes']['faktor-' + $(f).attr('id')] * entered_value), 3)));
						});
					}
				} else {
					return false;
				}
			});

			$(e).find('a.reset').click(function(){
				reset(e);
			});
			
			$(e).find('select').change(function(){
				reset(e);
				toggle_legend(e);
			});

		});
		
	}
});


function toggle_legend(rechner)
{
	if($(rechner).find('span.legend[data-limit-to="' + $(rechner).find('select').val() + '"]').length > 0)
	{
		$(rechner).find('ul li span.legend').each(function(i, e){
			if($(e).attr('data-limit-to') == $(rechner).find('select').val())
			{
				$(e).show();
			} else {
				$(e).hide();
			}
		});
	} else {
		$(rechner).find('ul li span.legend').each(function(i, e){
			if(typeof $(e).attr('data-limit-to') == 'undefined')
			{
				$(e).show();
			} else {
				$(e).hide();
			}
		});
	}
}


// http://stackoverflow.com/questions/2221167/javascript-formatting-a-rounded-number-to-n-decimals
function toFixed(value, precision)
{
    var precision = precision || 0,
    neg = value < 0,
    power = Math.pow(10, precision),
    value = Math.round(value * power),
    integral = String((neg ? Math.ceil : Math.floor)(value / power)),
    fraction = String((neg ? -value : value) % power),
    padding = new Array(Math.max(precision - fraction.length, 0) + 1).join('0');

    return precision ? integral + '.' +  padding + fraction : integral;
}


function commatron(input)
{
	var output = '';
	var period_to_comma = true;
	if(input.indexOf(",") != -1)
	{
		period_to_comma = false;
	}
	if(period_to_comma)
	{
		output = input.replace(/\./, ",");
	} else {
		output = input.replace(/,/, ".");
	}
	return output;
}


function reset(rechner)
{
	$(rechner).find('input:text').val('');
}


function is_calculate_keycode(key_code)
{
	/*
		backspace	 8
		enter	 13
		insert	 45
		delete	 46
		0	 48
		1	 49
		2	 50
		3	 51
		4	 52
		5	 53
		6	 54
		7	 55
		8	 56
		9	 57
		numpad 0	 96
		numpad 1	 97
		numpad 2	 98
		numpad 3	 99
		numpad 4	 100
		numpad 5	 101
		numpad 6	 102
		numpad 7	 103
		numpad 8	 104
		numpad 9	 105
		decimal point	 110
		comma 188
	*/

	return key_code == 8 || key_code == 13 || key_code == 45 || key_code == 46 || (key_code >= 48 && key_code <= 57) || (key_code >= 96 && key_code <= 105) || key_code == 110 || key_code == 188;
}


function is_do_not_calculate_keycode(key_code)
{
	/*
		tab	 9
		end	 35
		home	 36	
		left arrow	 37
		up arrow	 38
		right arrow	 39
		down arrow	 40
	*/
	
	return key_code == 9 || (key_code >= 35 && key_code <= 40);
}
