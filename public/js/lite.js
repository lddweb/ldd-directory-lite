jQuery(document).ready(function(){

	jQuery('#lddbd_categories_multiselect').change(function(){
		var category_string = '';
		var category_array = jQuery(this).val();
		
		for(var i = 0; i < category_array.length; i++){
			category_string += ','+category_array[i];
		}
		jQuery('#lddbd_categories').val(category_string);
	});

	var add_business_top = jQuery('#lddbd_add_business_holder').css('top');
	var input_holder = jQuery('div.lddbd_input_holder'); // There are 21 of these DIVs by default

	if( input_holder.length > 21 ) {
		var incr = (-1* parseInt(add_business_top));
		for( var i = 21; i < input_holder.length; i++ ) {
			var increment_height = incr += 30;
		}
	}

	jQuery('#lddbd_listings_category_button, #lddbd_all_listings_button, #lddbd_business_login_button').click(function(){
		jQuery('#lddbd_add_business_holder').css('visibility', 'hidden');
	});

	jQuery('#lddbd_add_business_button, #lddbd_cancel_listing').click(function(){
		jQuery('#lddbd_add_business_holder').css('visibility', 'visible');
		if(jQuery('#lddbd_add_business_holder').css('top')==add_business_top){
			if( input_holder.length > 21 ) {	
				if(jQuery('#lddbd_business_directory_body').height() < -1* parseInt(add_business_top)){
					jQuery('#lddbd_business_directory_body').animate({'height': increment_height+'px'}, 400);
				}
			} else {
				if(jQuery('#lddbd_business_directory_body').height() < -1* parseInt(add_business_top)){
					jQuery('#lddbd_business_directory_body').animate({'height': -1* parseInt(add_business_top)+'px'}, 400);
				}
			}
			jQuery('#lddbd_add_business_holder').animate({'top': '0px'}, 400);
		} else {
			jQuery('#lddbd_add_business_holder').animate({'top': add_business_top}, 400);
			jQuery('#lddbd_business_directory_body').removeAttr('style');
		}
	});

	jQuery('#lddbd_add_business_form').submit(function(){
		var error = false;
		
		jQuery('#lddbd_add_business_form').find('input.required').each(function(){
			if(jQuery(this).val()==''){
				if(!jQuery(this).hasClass('add_business_error')){
					jQuery(this).addClass('add_business_error').parent().append('<div class="add_business_error">This field is required.</div>');
				}	
				error=true;
			}
		});

		if(jQuery.inArray(jQuery('#lddbd_add_business_form').find('#login').val(), lddbd_business_logins)!=-1){
			jQuery('#lddbd_add_business_form').find('#login').addClass('add_business_error').parent().append('<div class="add_business_error">This login is taken.</div>');
			error=true;
		}

	jQuery('input.add_business_error').focus(function(){
		jQuery(this).removeClass('add_business_error');
	}).blur(function(){
		if(jQuery(this).val()==''){
			jQuery(this).addClass('add_business_error');
		} else {
			jQuery(this).siblings('div.add_business_error').remove();
		}
	});

		if(!error){
			jQuery(this).contents().fadeTo(200, 0.1);
			return true;
			jQuery('#lddbd_submission_target').load(function(){
				jQuery('#lddbd_add_business_form').contents().fadeTo(200, 1);
			jQuery('#lddbd_add_business_form').html('Application Submitted');
			jQuery('#lddbd_add_business_form').parent().delay(1000).animate({'top': add_business_top});
			jQuery('#lddbd_business_directory_body').removeAttr('style');
			});
		}
		else{
			return false;
		}
	});

	jQuery('#lddbd_search_directory').focus(function(){
		if(jQuery(this).val()=='Search the Business Directory'){
			jQuery(this).val('');
		}
	}).blur(function(){
		if(jQuery(this).val()==''){
			jQuery(this).val('Search the Business Directory');
		}
	});

	jQuery('#lddbd_business_search').submit(function(){
		if(jQuery('#lddbd_add_business_holder').css('top') == '0px'){
			jQuery('#lddbd_add_business_holder').animate({'top': add_business_top}, 400);
			jQuery('#lddbd_business_directory_body').removeAttr('style');	
		}
		if(jQuery('#lddbd_business_directory_single').length){
			jQuery('#lddbd_business_directory_single').fadeOut(200);
		}

		var query = jQuery('#lddbd_search_directory').val();
		if(query=='Search the Business Directory'){
			query = '';
		}
		var promo_filter = '';
		if(jQuery('#lddbd_promo_filter').is(":checked")){
			promo_filter = 'promo';
		}
		jQuery('#lddbd_business_directory_list').fadeTo(200, 0.1);
		jQuery.ajax({
			type: 'POST',
			url: lddbd_file_pathway+'lddbd_ajax.php',
			data: {query: query, promo_filter: promo_filter, action: 'search'},
			success: function(data){
				jQuery('#lddbd_business_directory_list').html(data).fadeTo(200, 1);
			}
		});
		return false;
	});

	jQuery('#lddbd_business_login_button').click(function(){
		if(jQuery('#lddbd_add_business_holder').css('top') == '0px'){
			jQuery('#lddbd_add_business_holder').animate({'top': add_business_top}, 400);
			jQuery('#lddbd_business_directory_body').removeAttr('style');	
		}

		jQuery('#lddbd_business_directory_list').fadeTo(200, 0.1, function(){
			jQuery(this).html('<div id="lddbd_business_login_holder"><form onsubmit="javascript: return businessLogin();" id="lddbd_business_login_form" method="post" action=""><div id="lddbd_input_holder"><label for="lddbd_business_login">Login</label><input id="lddbd_business_login" name="lddbd_business_login" type="text"/><br/><label for="lddbd_business_password">Password</label><input id="lddbd_business_password" name="lddbd_business_password" type="password"/></div>	<input type="submit" value="Log In"></form><a href="javascript:void(0);" onclick="javascript: lddbdPasswordRecovery();" id="lddbd_forgot_password">Forgot Password?</a></div>');
			jQuery(this).fadeTo(200, 1);
		});
	});

	jQuery('#lddbd_directory_home_button').click(function(){
		if(jQuery('#lddbd_add_business_holder').css('top') == '0px'){
			jQuery('#lddbd_add_business_holder').animate({'top': add_business_top}, 400);
			jQuery('#lddbd_business_directory_body').removeAttr('style');	
		}

		var current_url = window.location.toString();
		var cutoff = current_url.indexOf('?');
		window.location = current_url.substring(0, cutoff);
	});

	jQuery('#lddbd_all_listings_button').click(function(){
		if(jQuery('#lddbd_add_business_holder').css('top') == '0px'){
			jQuery('#lddbd_add_business_holder').animate({'top': add_business_top}, 400);
			jQuery('#lddbd_business_directory_body').removeAttr('style');	
		}

		if(jQuery('#lddbd_business_directory_single').length){
			jQuery('#lddbd_business_directory_single').fadeOut(200);
		}

		jQuery('#lddbd_business_directory_list').fadeTo(200, 0.1);
		jQuery.ajax({
			type: 'POST',
			url: lddbd_file_pathway+'lddbd_ajax.php',
			data: {query: '', action: 'search'},
			success: function(data){
				jQuery('#lddbd_business_directory_list').html(data).fadeTo(200, 1);
			}
		});
	});

	jQuery('#lddbd_listings_category_button').click(function(){
		if(jQuery('#lddbd_add_business_holder').css('top') == '0px'){
			jQuery('#lddbd_add_business_holder').animate({'top': add_business_top}, 400);
			jQuery('#lddbd_business_directory_body').removeAttr('style');	
		}

		backToCategories();
	});

	jQuery('.lddbd_categorization_bool[name="lddbd_options[categorization]"]').change(function(){
		if(jQuery(this).val()=='Yes'){
			jQuery('#lddbd_categories_list').closest('tr').slideDown('fast');
		} else {
			jQuery('#lddbd_categories_list').closest('tr').slideUp('fast');
		}
	});

	if(jQuery('.lddbd_categorization_bool:checked').val()=='Yes'){
		jQuery('#lddbd_categories_list').closest('tr').slideDown(0);
	} else {
		jQuery('#lddbd_categories_list').closest('tr').slideUp(0);
	}
});


function lddbdPasswordRecovery(){
	var login = jQuery('#lddbd_business_login_form input#lddbd_business_login').val();
	if (login != ''){
		jQuery.ajax({
			type: 'POST',
			url: lddbd_file_pathway+'lddbd_ajax.php',
			data: {login: login, action: 'recover_password'},
			success: function(data){
				if(data=='no login'){
					alert("Sorry, but we don't have a record for that login");
				}
				else if(data=='success'){
					alert("A new password has been sent to the email address listed for the business");
				}
			}
		});
	} else {
		alert('Please provide your login and we will send you an email');
	}
}

function businessLogin(){
	if(jQuery('#lddbd_business_directory_single').length){
		jQuery('#lddbd_business_directory_single').fadeOut(200);
	}

	jQuery('#lddbd_business_directory_list').fadeTo(200, 0.1);
	var login = jQuery('#lddbd_business_login').val();
	var password = jQuery('#lddbd_business_password').val();
	jQuery.ajax({
		type: 'POST',
		url: lddbd_file_pathway+'lddbd_ajax.php',
		data: {login: login, password: password, action: 'login'},
		success: function(data){
			jQuery('#lddbd_business_directory_list').html(data).fadeTo(200, 1);
			jQuery('#lddbd_business_login, #lddbd_business_password').val('');
		}
	});
	return false;
}

function mailToBusiness(email, element, business){
	var click_position;
	
	if(jQuery(element).closest('div.lddbd_business_listing').length){
		click_position = jQuery(element).closest('div.lddbd_business_listing').position();
		click_position = click_position.top + 50;
	} else {
		click_position = 5;
	}
	jQuery('#lddbd_business_directory').append(
	'<div style="position: absolute; width: 100%; height: 100%; top:0px; left: 0px; background: #000; opacity: 0.5; z-index: 400;" id="lddbd_mail_shader"></div>' +
	'<form id="lddbd_mail_to_business_form" style="top: '+click_position+'px">' +
	'<strong>Send message to '+business+'</strong>' +
	'<input type="hidden" readonly id="email" name="email" value="'+email+'" />' +
	'<label for="name">Name:</label>' +
		'<input type="text" id="name" name="name" />' +
	'<label for="from">Email:</label>' +
		'<input type="text" id="from" name="from" />' +
	'<label for="phone">Phone:</label>' +
		'<input type="text" id="phone" name="phone" />' +
	'<label for="message">Message:</label>' +
		'<textarea id="message" name="message"></textarea>' +
		'<input type="button" value="Cancel"><input type="submit" value="Send">' +
	'</form>');
	
	jQuery('#lddbd_mail_to_business_form input:button').click(function(){
		jQuery('#lddbd_mail_to_business_form, #lddbd_mail_shader').remove();
	});
	jQuery('#lddbd_mail_to_business_form').submit(function(){
		var email_regex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		var from = jQuery('input#from', this).val();
		var message = jQuery('textarea#message', this).val();
		var error = false;
		var error_message = '';

		if(from=='' || !email_regex.test(from)){
			error = true;
			error_message+='Please enter a valid return email \r\n';
		} 
		if(message==''){
			error = true;
			error_message+='Please enter a message \r\n';
		}

		if(error){
			alert(error_message);
		} else {
			jQuery.ajax({
				type: 'POST',
				url: lddbd_file_pathway+'lddbd_ajax.php',
				data: {
					email: jQuery('#lddbd_mail_to_business_form input#email').val(),
					from: from,
					name: jQuery('#lddbd_mail_to_business_form input#name').val(),
					phone: jQuery('#lddbd_mail_to_business_form input#phone').val(),
					message: message,
					action: 'email'
					},
				success: function(data){
					//jQuery('#lddbd_mail_to_business_form').html(data);
					jQuery('#lddbd_mail_to_business_form, #lddbd_mail_shader').remove();
				}
			});
		}

		return false;
	});
}

function backToCategories(){

	if(jQuery('#lddbd_add_business_holder').css('top') == '0px'){jQuery('#lddbd_add_business_button').click();}

	if(jQuery('#lddbd_business_directory_single').length){
		jQuery('#lddbd_business_directory_single').fadeOut(200);
	}

	jQuery('#lddbd_business_directory_list').fadeTo(200, 0.1);
	jQuery.ajax({
		type: 'POST',
		url: lddbd_file_pathway+'lddbd_ajax.php',
		data: {
			action: 'categories_list'
			},
		success: function(data){
			jQuery('#lddbd_business_directory_list').html(data).fadeTo(200, 1);;
		}
	});
}

function backToResults(){
	if(jQuery('#lddbd_add_business_holder').css('top') == '0px'){jQuery('#lddbd_add_business_button').click();}

	if(typeof previous_content !== 'undefined'){
	jQuery('#lddbd_business_directory_list').fadeTo(200, 0.1, function(){
		jQuery(this).html(previous_content).fadeTo(200, 1);
	});
	} else {
	window.location.href = window.location.href.split('?')[0];
	}
}

function categoryListing(cat_id){
	if(jQuery('#lddbd_add_business_holder').css('top') == '0px'){jQuery('#lddbd_add_business_button').click();}

	jQuery('#lddbd_business_directory_list').fadeTo(200, 0.1);
	jQuery.ajax({
	type: 'POST',
	url: lddbd_file_pathway+'lddbd_ajax.php',
	data: {cat_id: cat_id, action: 'category_filter'},
	success: function(data){
		jQuery('#lddbd_business_directory_list').html(data).fadeTo(200, 1);
	}
});
}

function singleBusinessListing(bus_id){
previous_content = jQuery('#lddbd_business_directory_list').html();
var URL = document.URL.split('?')[0];
URL += '?business='+bus_id+' #lddbd_business_directory_single';
jQuery('#lddbd_business_directory_list').fadeTo(200, 0.1, function(){
	jQuery(this).load(URL, function(){
		jQuery(this).fadeTo(200, 1);
	});
});
}

function removeInfoSection(element){
	var section_count = jQuery('.lddbd_information_section').length;
	var section_id = jQuery(element).parent().attr('id');
	var section_index = parseInt(section_id.replace('lddbd_information_section_', ''));
	for(i=section_index+1; i<=section_count; i++){

		jQuery('#lddbd_information_section_'+i+' h3').html('Section '+(i-1));
		jQuery('#lddbd_information_section_'+i).find('label').eq(0).attr('for', 'lddbd_options[section'+(i-1)+'_title]');
		jQuery('#lddbd_information_section_'+i).find('input:text').attr('name', 'lddbd_options[section'+(i-1)+'_title]');
		jQuery('#lddbd_information_section_'+i).find('label').eq(1).attr('for', 'lddbd_options[section'+(i-1)+'_type]');
		jQuery('#lddbd_information_section_'+i).find('select').attr('name', 'lddbd_options[section'+(i-1)+'_type]');
		jQuery('#lddbd_information_section_'+i).attr('id', 'lddbd_information_section_'+(i-1));
	}
	jQuery(element).parent().remove();
}

/*
function removeCategory(element){
	var category_count = jQuery('.lddbd_category_holder').length;
	var category_id = jQuery(element).parent().attr('id');
	var category_index = parseInt(category_id.replace('lddbd_category_holder_', ''));
	for(i=category_index+1; i<=category_count; i++){

		jQuery('#lddbd_category_holder_'+i).find('input:text').attr('name', 'lddbd_options[category_'+(i-1)+']');
		jQuery('#lddbd_category_holder_'+i).attr('id', 'lddbd_information_section_'+(i-1));
	}
	jQuery(element).parent().remove();
}
*/