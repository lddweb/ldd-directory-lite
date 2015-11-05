		<div class="fep">
			<form id="your-profile" action="#fep-message" method="post"<?php do_action('user_edit_form_tag'); ?>>
			<?php wp_nonce_field('update-user_' . $user_id) ?>
			<?php if ( $wp_http_referer ) : ?>
				<input type="hidden" name="wp_http_referer" value="<?php echo esc_url($wp_http_referer); ?>" />
			<?php endif; ?>
			<p>
			<input type="hidden" name="from" value="profile" />
			<input type="hidden" name="checkuser_id" value="<?php echo $user_ID ?>" />
			</p>

			<table class="form-table">
	
<?php
			do_action('personal_options', $profileuser);
			?>
			</table>
			<?php
			do_action('profile_personal_options', $profileuser);
			?>

			<h3><?php _e('Name') ?></h3>

			<table class="form-table">
				<tr>
					<th><label for="user_login"><?php _e('Username'); ?></label></th>
					<td><input type="text" name="user_login" id="user_login" value="<?php echo esc_attr($profileuser->user_login); ?>" disabled="disabled" class="regular-text" /><br /><em><span class="description"><?php _e('Usernames cannot be changed.'); ?></span></em></td>
				</tr>
			<tr>
				<th><label for="first_name"><?php _e('First Name') ?></label></th>
				<td><input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($profileuser->first_name) ?>" class="regular-text" /></td>
			</tr>

			<tr>
				<th><label for="last_name"><?php _e('Last Name') ?></label></th>
				<td><input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($profileuser->last_name) ?>" class="regular-text" /></td>
			</tr>

			<tr>
				<th><label for="nickname"><?php _e('Nickname'); ?> <span class="description"><?php _e('(required)'); ?></span></label></th>
				<td><input type="text" name="nickname" id="nickname" value="<?php echo esc_attr($profileuser->nickname) ?>" class="regular-text" /></td>
			</tr>

			<tr>
				<th><label for="display_name"><?php _e('Display to Public as',FEP) ?></label></th>
				<td>
					<select name="display_name" id="display_name">
					<?php
						$public_display = array();
						$public_display['display_username']  = $profileuser->user_login;
						$public_display['display_nickname']  = $profileuser->nickname;
						if ( !empty($profileuser->first_name) )
							$public_display['display_firstname'] = $profileuser->first_name;
						if ( !empty($profileuser->last_name) )
							$public_display['display_lastname'] = $profileuser->last_name;
						if ( !empty($profileuser->first_name) && !empty($profileuser->last_name) ) {
							$public_display['display_firstlast'] = $profileuser->first_name . ' ' . $profileuser->last_name;
							$public_display['display_lastfirst'] = $profileuser->last_name . ' ' . $profileuser->first_name;
						}
						if ( !in_array( $profileuser->display_name, $public_display ) ) // Only add this if it isn't duplicated elsewhere
							$public_display = array( 'display_displayname' => $profileuser->display_name ) + $public_display;
						$public_display = array_map( 'trim', $public_display );
						$public_display = array_unique( $public_display );
						foreach ( $public_display as $id => $item ) {
					?>
						<option id="<?php echo $id; ?>" value="<?php echo esc_attr($item); ?>"<?php selected( $profileuser->display_name, $item ); ?>><?php echo $item; ?></option>
					<?php
						}
					?>
					</select>
				</td>
			</tr>
			</table>

			<h3><?php _e('Contact Info') ?></h3>

			<table class="form-table">
			<tr>
				<th><label for="email"><?php _e('E-mail'); ?> <span class="description"><?php _e('(required)'); ?></span></label></th>
				<td><input type="text" name="email" id="email" value="<?php echo esc_attr($profileuser->user_email) ?>" class="regular-text" />
				<?php
				$new_email = get_option( $current_user->ID . '_new_email' );
				if ( $new_email && $new_email != $current_user->user_email ) : ?>
				<div class="updated inline">
				<p><?php printf( __('There is a pending change of your e-mail to <code>%1$s</code>. <a href="%2$s">Cancel</a>',FEP), $new_email['newemail'], esc_url(get_permalink().'?dismiss=' . $current_user->ID . '_new_email'  ) ); ?></p>
				</div>
				<?php endif; ?>
				</td>
			</tr>

			<tr>
				<th><label for="url"><?php _e('Website') ?></label></th>
				<td><input type="text" name="url" id="url" value="<?php echo esc_attr($profileuser->user_url) ?>" class="regular-text code" /></td>
			</tr>

			<?php
				$contact_methods = array();
				
				$contact_methods = apply_filters("fep_contact_methods",$contact_methods);
					if(!(is_array($contact_methods))){
                                            $contact_methods = array();
                                         }
				foreach (_wp_get_user_contactmethods() as $name => $desc) {
				
						if(in_array($name,$contact_methods)) continue;
				?>
			<tr>
				<th><label for="<?php echo $name; ?>"><?php echo apply_filters('user_'.$name.'_label', $desc); ?></label></th>
				<td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_attr($profileuser->$name) ?>" class="regular-text" /></td>
			</tr>
			<?php
				}
			?>
			</table>
			<?php
			if( $show_biographical):
			?>
			<h3><?php _e('About Yourself'); ?></h3>
			<?php
			endif;
			?>

			<table class="form-table">
			<?php
			if( $show_biographical):
			?>
			<tr>
				<th><label for="description"><?php _e('Biographical Info'); ?></label></th>
				<td><textarea name="description" id="description" rows="5" cols="30"><?php echo esc_html($profileuser->description); ?></textarea><br />
				<span class="description"><?php _e('Share a little biographical information to fill out your profile. This may be shown publicly.'); ?></span></td>
			</tr>
			<?php
			endif;
			?>
			
			<?php
			$show_password_fields = apply_filters('show_password_fields', true, $profileuser);
			if ( $show_password_fields ) :
			?>
			<tr id="password">
				<th><label for="pass1"><?php _e('New Password'); ?></label><br /><span class="description"><small><?php _e("If you would like to change the password type a new one. Otherwise leave this blank."); ?></small></span></th>
				<td>
					<input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" /><br /><br />
					<input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" />&nbsp;<em><span class="description"><?php _e("Type your new password again."); ?></span></em>
					
					<?php if($show_pass_indicator):?>
					
					<div id="pass-strength"><?php _e('Strength indicator'); ?></div>
					<?php endif;?>
					
					<?php if($show_pass_hint):?>
					<p class="description indicator-hint">
					<?php 
					$passhint = get_option('fep_text_pass_hint');
					
					if(!empty($passhint)){ echo $passhint;}
					else{?>
							-&nbsp;<?php _e('The password should be at least seven characters long.'); ?><br />
							-&nbsp;<?php _e('To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).');?>
					<?php
				 		}
					?>
					</p>
					<?php endif;?>
				</td>
			</tr>
			<?php endif; ?>
			</table>

			<?php
				do_action( 'show_user_profile', $profileuser );
			?>

			<?php if ( count($profileuser->caps) > count($profileuser->roles) && apply_filters('additional_capabilities_display', true, $profileuser) ) { ?>
			<br class="clear" />
				<table width="99%" style="border: none;" cellspacing="2" cellpadding="3" class="editform">
					<tr>
						<th scope="row"><?php _e('Additional Capabilities') ?></th>
						<td><?php
						$output = '';
						foreach ( $profileuser->caps as $cap => $value ) {
							if ( !$wp_roles->is_role($cap) ) {
								if ( $output != '' )
									$output .= ', ';
								$output .= $value ? $cap : "Denied: {$cap}";
							}
						}
						echo $output;
						?></td>
					</tr>
				</table>
			<?php } ?>

			<p class="submit">
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr($user_id); ?>" />
				<input type="submit" class="button-primary" value="<?php _e('Update Profile'); ?>" name="submit" />
			</p>
			</form>
		</div>
		
		<script type="text/javascript" charset="utf-8">
			if (window.location.hash == '#password') {
				document.getElementById('pass1').focus();
			}
		</script>