<?php

/**
 *
 */


function lddlite_display_business()
{
    global $wpdb, $tables;

    $business = $wpdb->get_row( "SELECT * FROM {$tables['main']} WHERE id = '{$_GET['business']}'" );
    $documents = $wpdb->get_results( "SELECT * FROM {$tables['doc']} WHERE bus_id = '{$business->id}'" );

    $doc_list = '';
    if($documents)
        foreach($documents as $document)
            $doc_list.="<li><a target='_blank' href='".site_url('/wp-content/uploads/').$document->doc_path."'>{$document->doc_description}</a></li>";

    $options = get_option('lddbd_options');
    $description = '';
    $contact_left = '';
    $contact_right = '';
    $logo_html = '';
    $business_address = '';
    $business_info = '';



    if(!empty($business->contact)){ $contact_left.="<p><strong>Contact:</strong></p><p>{$business->contact}</p>"; }
    if(!empty($business->address_street)){ $contact_left.="<strong>Address:</strong><p>{$business->address_street}</p>"; $business_address.=$business->address_street;}
    if(!empty($business->address_city) || !empty($business->address_state) || !empty($business->address_zip)){
        $contact_left.="<p>{$business->address_city}, {$business->address_state} {$business->address_zip}</p>";
        $business_address.=' '.$business->address_city;
        $business_address.=' '.$business->address_state;
        $business_address.=' '.$business->address_zip;
    }
    if(!empty($business->phone)){ $contact_left.="<strong>Phone:</strong><p>{$business->phone}</p>"; }
    if(!empty($business->fax)){ $contact_left.="<strong>Fax:</strong><p>{$business->fax}</p>"; }
    if(!empty($business_address)){
        $address = $business_address;
        $address = str_replace(' ', '+', $address);
        $address = str_replace('.', '', $address);
        $address = str_replace(',', '', $address);
        $address = str_replace(chr(13), '+', $address);
        $address = str_replace(chr(10), '+', $address);
        $address = str_replace('++', '+', $address);

        $map_query = $options['google_map'];
        if($map_query=='Yes') {
            $map="<div id='lddbd_map'><iframe width='500' height='350' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='http://maps.google.com/maps?q={$address}&amp;ie=UTF8&amp;hq=&amp;hnear={$address}&amp;t=m&amp;z=14&amp;iwloc=A&amp;output=embed&amp;typecontrol=0'></iframe>
			<br />
			<small>
				<a target='_blank' href='http://maps.google.com/maps?q={$address}&amp;ie=UTF8&amp;hq=&amp;hnear={$address}&amp;t=m&amp;z=13&amp;source=embed' style='color:#0000FF;text-align:left'>
					View Larger Map
				</a>
			</small></div>";
        } else {
            $map = "";
        }
    }
    if(!empty($business->name)){
        $biz_name = stripslashes($business->name);
        $name = "<h4>{$biz_name}</h4>";
    }
    if($business->promo=='true'){
        $special_offer_logo="<img id='lddbd_special_offer_logo' src='". LDDLITE_URL . '/public/icons/special-offer.png' ."' />";
    }
    if(!empty($business->description)){ $description="<p>{$business->description}</p>"; }
    if(!empty($business->promoDescription)){$special_offer_description="<h4>Special Offer:</h4><p>{$business->promoDescription}</p>";}
    if(!empty($business->categories)){
        $business_categories_list = '';
        $categories_array = str_replace('x,x', ', ', $business->categories);
        $categories_array = str_replace(',x', '', $categories_array);
        $categories_array = str_replace('x', '', $categories_array);
        $categories_array = explode(', ', $categories_array);
        foreach($categories_array as $cat_id){
            $listing_category = $wpdb->get_row(
                "
							SELECT *
							FROM {$tables['cat']}
							WHERE id = '{$cat_id}'
							"
            );
            $business_categories_list .= "{$listing_category->name}, ";
        }
        $business_categories_list = substr($business_categories_list, 0, -2);
    }
    if(!empty($business->url)){
        if(strstr($business->url, 'http://')){$business_url = $business->url;}
        else{$business_url = 'http://'.$business->url;}
        $contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_url}'><img src='". LDDLITE_URL . '/public/icons/website.png' ."' /></a>";
    }
    if(!empty($business->facebook)){
        if(strstr($business->facebook, 'http://')){$business_facebook = $business->facebook;}
        else{$business_facebook = 'http://'.$business->facebook;}
        $contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_facebook}'><img src='". LDDLITE_URL . '/public/icons/facebook.png' ."' /></a>";
    }
    if(!empty($business->twitter)){
        if(strstr($business->twitter, 'http://www.twitter.com/') || strstr($business->twitter, 'http://twitter.com/')){$business_twitter = $business->twitter;}
        else if(strstr($business->twitter, '@')){$business_twitter = 'http://twitter.com/'.trim($business->twitter, '@');}
        else{$business_twitter = 'http://twitter.com/'.$business->twitter;}
        $contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_twitter}'><img src='". LDDLITE_URL . '/public/icons/twitter.png' ."' /></a>";
    }
    if(!empty($business->linkedin)){
        if(strstr($business->linkedin, 'http://')){$business_linkedin = $business->linkedin;}
        else{$business_linkedin = 'http://'.$business->linkedin;}
        $contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_linkedin}'><img src='". LDDLITE_URL . '/public/icons/linkedin.png' ."' /></a>";
    }
    if(!empty($business->email)){
        $bizname_esc = addslashes($business->name); // In the event that our business has a single or double quote in it
        $contact_right.="<a class='lddbd_contact_icon' href='javascript:void(0);' onclick=\"javascript:mailToBusiness('{$business->email}', this, '{$bizname_esc}');\"><img src='". LDDLITE_URL . '/public/icons/email.png' ."' /></a>";
    }

    if(!empty($business->logo)){$logo_html = '<img src="'.site_url('/wp-content/uploads/').$business->logo.'"/>'; }

    if( !empty( $business->other_info )) {
        $other_info = unserialize( $business->other_info );
        foreach( $other_info as $type => $data ) {
            $type = str_replace( '_', ' ', $type );
            $type = ucwords( $type );
            $business_info .= '<span class="lddbd-business-info-type">' . $type . '</span>';
            $business_info .= '<span class="lddbd-business-info-data">' . $data . '</span><br />';
        }
    }

    if( !empty( $type ) && !empty( $data )) {
        $other_info_area = "<hr /> $business_info <hr />";
    }



    $business_listing = "<div class='single_business_left'>
					{$logo_html}
				<div class='lddbd_business_contact'>
					{$contact_left}
				</div>
			</div>
			<div class='single_business_right'>
				{$special_offer_logo} {$name}
				{$description}

				{$other_info_area}

				{$special_offer_description}
				<ul class='business_docs'>
					{$doc_list}
				</ul>
				<div class='lddbd_business_contact'>
					{$contact_right}
				</div>
			</div>
			{$map}";

    $user_categorization_query = $options['user_categorization'];

    // Generates the category list for the frontend from all the categories available in the database for a user to choose from.
    if($user_categorization_query=='Yes'){
        $categories_list = $wpdb->get_results(
            "
			SELECT *
			FROM {$tables['cat']}
			"
        );

        $business_categories = "<div class='lddbd_input_holder'>\n";
        $business_categories .= "<label for='categories_multiselect'>Categories</label>\n";
        $business_categories .= "<select id='lddbd_categories_multiselect' name='categories_multiselect' multiple='multiple'>\n";

        foreach($categories_list as $category){
            $cat_name = stripslashes($category->name);
            $business_categories .= "<option value='x{$category->id}x'>{$cat_name}</option>\n";
        }

        $business_categories .= "</select>\n";
        $business_categories .= "<input id='lddbd_categories' type='hidden' name='categories'/>\n";
        $business_categories .= "</div>\n";
    }

    $submit_button_query = $options['display_button'];

    // Generates the Submit Listing form. Also returns the entire frontend display.
    if($submit_button_query=='Yes'){

        $section_array = unserialize($options['information_sections']);
        if(!empty($section_array)){
            $other_sections = '';
            foreach($section_array as $number=>$attributes){
                $type = $attributes['type'];
                if($type=='bool'){
                    $input = "<div class='lddbd_radio_holder'><input type='radio' name='{$attributes['name']}' value='Yes' />Yes&nbsp;<input type='radio' name='{$attributes['name']}' value='No' />No</div>";
                } else {
                    $input = "<input type='{$type}' name='{$attributes['name']}'/>";
                }


                $other_sections.= "<div class='lddbd_input_holder'>
						<label for='{$attributes['name']}'>{$attributes['title']}</label>
						{$input}
					</div>";

            }
        }

        $lddbd_countryTxtFile = LDDLITE_JS . '/countries.txt';

        // Text file containing list of supported countries
        $countryList = fopen( $lddbd_countryTxtFile, "r" );

        $optionLine = "";

        while( !feof ( $countryList ) ) {
            $textLine = fgets( $countryList );
            $textLine = trim( $textLine );

            $optionLine .= "<option>$textLine</option>\n";
        }
        fclose( $countryList );

        // Call the script file with the jQuery controls for displaying form elements for specific countries
        ob_start();
        include( LDDLITE_JS . 'countrySelector.php' );
        $countrySelector = ob_get_clean();

        $formActionCall = LDDLITE_AJAX;
        $submit_button = "<a href='javascript:void(0);' id='lddbd_add_business_button' class='lddbd_navigation_button'>Submit Listing</a>";

        if( !empty ( $options['directory_label'] ) ) {
            $directory_label = $options['directory_label'];
        } else {
            $directory_label = 'Business';
        }

        $add_business_holder = <<<ABH
        <div id='lddbd_add_business_holder'>
            <form id='add_business_form' action='{$formActionCall}' method='POST' enctype='multipart/form-data' target='lddbd_submission_target'>
                <div class='lddbd_input_holder'>
                    <label for='name'>{$directory_label} Name</label>
                    <input class='required' type='text' id='lddbd_name' name='name'/>
                </div>

                <div class='lddbd_input_holder'>
                    <label for='description'>{$directory_label} Description</label>
                    <textarea id='lddbd_description' name='description'></textarea>
                </div>

                <div class='lddbd_input_holder'>
                    <label for='address_street'>Street</label>
                    <input type='text' id='lddbd_address_street' name='address_street'>
                </div>

                <div class='lddbd_input_holder'>
                    <label for='address_country'>Country</label>

                    <select id='lddbd_address_country' name='address_country'>
                        {$optionLine}
                    </select>

                </div>
                <div id='selectedCountryForm'></div>

                <div class='lddbd_input_holder'>
                    <label for='phone'>Contact Phone</label>
                    <input class='' type='text' id='lddbd_phone' name='phone'/>
                </div>

                <div class='lddbd_input_holder'>
                    <label for='fax'>Contact Fax</label>
                    <input type='text' id='lddbd_fax' name='fax'>
                </div>

                <div class='lddbd_input_holder'>
                    <label for='email'>Contact Email</label>
                    <input class='' type='text' id='lddbd_email' name='email'/>
                </div>

                <div class='lddbd_input_holder'>
                    <label for='contact'>Contact Name</label>
                    <input class='' type='text' id='lddbd_contact' name='contact'/>
                </div>

                <div class='lddbd_input_holder'>
                    <label for='url'>Website</label>
                    <input type='text' id='lddbd_url' name='url'/>
                </div>

                <div class='lddbd_input_holder'>
                    <label for='facebook'>Facebook Page</label>
                    <input type='text' id='lddbd_facebook' name='facebook'/>
                </div>

                <div class='lddbd_input_holder'>
                    <label for='twitter'>Twitter Handle</label>
                    <input type='text' id='lddbd_twitter' name='twitter'/>
                </div>

                <div class='lddbd_input_holder'>
                    <label for='linkedin'>Linked In Profile</label>
                    <input type='text' id='lddbd_linkedin' name='linkedin'/>
                </div>

                <div class='lddbd_input_holder'>
                    <label for='promo'>Special Offer</label>
                    <div class='lddbd_radio_holder'>
                        <input type='radio' id='lddbd_promo' name='promo' value='true'/>Yes&nbsp;<input type='radio' id='promo' name='promo' value='false'/>No
                    </div>
                </div>

                <div class='lddbd_input_holder'>
                    <label for='promo_description'>Special Offer Description</label>
                    <input type='text' id='lddbd_promo_description' name='promo_description'/>
                </div>

                <div class='lddbd_input_holder'>
                    <label for='logo'>Logo Image</label>
                    <input class='' type='file' id='lddbd_logo' name='logo'/>
                </div>

                {$other_sections}

                {$business_categories}

                <div class='lddbd_input_holder'>
                    <label for='login'>Login</label>
                    <input class='required' type='text' id='lddbd_login' name='login'/>
                </div>

                <div class='lddbd_input_holder'>
                    <label for='password'>Password</label>
                    <input class='' type='text' id='lddbd_password' name='password'/>
                </div>

                <input type='hidden' id='action' name='action' value='add'/>
                <input type='hidden' id='lddbd_from' name='from' value='frontend'/>

                <div class='submit'>
                    <input id='lddbd_cancel_listing' type='button' class='button-primary' value='Cancel' />
                    <input type='submit' class='button-primary' value='Submit Listing' />
                </div>
            </form>
            {$countrySelector}
            <iframe id='lddbd_submission_target' name='lddbd_submission_target' src='{$formActionCall}' style='width:0px;height:0px;border:0px solid #fff;'></iframe>
        </div>
ABH;
            } else {
                $submit_button = '';
                $add_business_holder = '';
            }
	    $login_form_query = $options['display_login'];

        // Generates link/button to the AJAX login on the front end.
        if($login_form_query=='Yes'){
            $login_button = "<a href='javascript:void(0);' id='lddbd_business_login_button' class='lddbd_navigation_button'>Login</a>";
        } else {
            $login_button = '';
        }

        $biz_cat_list = stripslashes($business_categories_list);

        // Show the title of the business directory. If there isn't one entered then default to "Business Directory".
        if( !empty ( $options['directory_title'] ) ) {
            $directory_name = $options['directory_title'];
            } else {
            $directory_name = 'Business Directory';
        }

        // Show the new label for the directory. If there isn't one entered then default to "Business".
        if( !empty ( $options['directory_label'] ) ) {
            $directory_label = $options['directory_label'];
        } else {
            $directory_label = 'Business';
        }

        return "

            <div id='lddbd_business_directory'>
                <div id='lddbd_business_directory_head'>
                    <p>{$options['welcome_message']}</p>
                    <h2>{$directory_name}</h2>
                    <form id='lddbd_business_search' action='' method='GET'>
                        <input type='text' id='lddbd_search_directory' name='search_directory' value='Search the {$directory_label} Directory'/>
                        <input type='submit' value='search' />
                    </form>
                    <p>{$options['welcome_message']}</p>
                    <div id='lddbd_navigation_holder'>
                        <a href='javascript:void(0);' id='lddbd_listings_category_button' class='lddbd_navigation_button'>Categories</a>
                        <a href='javascript:void(0);' id='lddbd_all_listings_button' class='lddbd_navigation_button'>All Listings</a>
                        {$login_button}
                        {$submit_button}
                    </div>
                </div>
                <div id='lddbd_business_directory_body'>
                    <div id='lddbd_business_directory_list'></div>
                    <div id='lddbd_business_directory_single'>
                        <h3>
                            <a href='javascript:void(0);' id='lddbd_back_to_results' onclick='javascript:backToResults();'>&larr; Back</a>
                            <span>{$biz_cat_list}</span>
                        </h3>
                    {$business_listing}
                    </div>
                    {$add_business_holder}
                </div>
            </div>

}