<?php

/**
 *
 */

function lddlite_display_main()
{
    global $wpdb, $tables;

    $business_list = $wpdb->get_results(
        "
		SELECT *
		FROM {$tables['main']} WHERE approved = true
		ORDER BY name ASC
		"
    );
    $category_list = $wpdb->get_results(
        "
		SELECT * FROM {$tables['cat']}
		ORDER BY name ASC
		"
    );

    $category_number = $wpdb->get_var( "SELECT * FROM {$tables['cat']}" );




    // @TODO: CATEGORY_LIST looks the same, function?
    if($category_list){
        $i = 0;
        $categories = '';
        foreach($category_list as $category){
            $cat_count = 0;
            foreach($business_list as $business){
                $cat_array = explode(',', str_replace('x', '', substr($business->categories, 1)));
                if(in_array($category->id, $cat_array)){
                    $cat_count++;
                }
            }

            $cat_name = stripslashes($category->name);

            $wpdb->update(
                $tables['cat'],
                array('count'=>$cat_count),
                array('id'=>$category->id),
                array('%d'),
                array('%d')
            );
            $categories.="<a class='category_link' href='javascript:void(0);' onclick='javascript:categoryListing({$category->id});'>{$cat_name} ({$cat_count}) </a>";
            if($i >= $category_number/2){
                $categories.="</div><div id='lddbd_categories_right'>";
            }
            $i++;
        }
    }



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


    $business_div = '';

    foreach ( $business_list as $business )
    {
        $logo_html = '';
        $contact = '';
        $contact_right = '';

        // Check if there is a logo for this particular business listing.
        if(!empty($business->logo)){ $logo_html = "<div class='lddlite-logo' onclick='javascript:singleBusinessListing({$business->id});'><img src='".site_url('/wp-content/uploads/')."{$business->logo}'/></div>"; }
        $logo_html = '<div class="lddlite-logo" onclick="javascript:singleBusinessListing(' . $business->id . ');"><img src="' . LDDLITE_URL . '/public/icons/avatar_default.png" /></div>';

        // Check if there is a name, phone, and/or fax number for this business listing.
        if(!empty($business->contact)){ $contact.="<li><strong>Contact:</strong> {$business->contact}</li>";}
        if(!empty($business->phone)){ $contact.="<li><strong>Phone:</strong> {$business->phone}</li>"; }
        if(!empty($business->fax)){ $contact.="<li><strong>Fax:</strong> {$business->fax}</li>"; }

        // Check if there is a website URL assigned to this business listing.
        if(!empty($business->url)){
            if(strstr($business->url, 'http://')) {$business_url = $business->url; }
            else{$business_url = 'http://'.$business->url;}
            $contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_url}'><img src='". LDDLITE_URL . '/public/icons/website.png' ."' /></a>";
        }
        // Check if there is a Facebook account assigned to this business listing.
        if(!empty($business->facebook)){
            if(strstr($business->facebook, 'http://')) {$business_facebook = $business->facebook;}
            else{$business_facebook = 'http://'.$business->facebook;}
            $contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_facebook}'><img src='". LDDLITE_URL . '/public/icons/facebook.png' ."' /></a>";
        }
        // Check if there is a Twitter account assigned to this business listing.
        if(!empty($business->twitter)){
            if(strstr($business->twitter, 'http://www.twitter.com/') || strstr($business->twitter, 'http://twitter.com/')){$business_twitter = $business->twitter;}
            else if(strstr($business->twitter, '@')){$business_twitter = 'http://twitter.com/'.trim($business->twitter, '@');}
            else{$business_twitter = 'http://twitter.com/'.$business->twitter;}
            $contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_twitter}'><img src='". LDDLITE_URL . '/public/icons/twitter.png' ."' /></a>";
        }
        // Check if there is a LinkedIn account assigned to this business listing.
        if(!empty($business->linkedin)){
            if(strstr($business->linkedin, 'http://')){$business_linkedin = $business->linkedin;}
            else{$business_linkedin = 'http://'.$business->linkedin;}
            $contact_right.="<a class='lddbd_contact_icon' target='_blank' href='{$business_linkedin}'><img src='". LDDLITE_URL . '/public/icons/linkedin.png' ."' /></a>";
        }
        // Check if there is an email address assigned to this business listing.
        if(!empty($business->email)){
            $bizname_esc = addslashes($business->name); // In the event that our business has a single or double quote in it
            $contact_right.="<a class='lddbd_contact_icon' href='javascript:void(0);' onclick=\"javascript:mailToBusiness('{$business->email}', this, '{$bizname_esc}');\"><img src='". LDDLITE_URL . '/public/icons/email.png' ."' /></a>"; }

        // Check if there is a promotion available for this business listing.
        if($business->promo=='true'){ $contact_right.="<a class='lddbd_contact_icon' href='javascript:void(0);' onclick=\"javascript:singleBusinessListing({$business->id});\"><img src='". LDDLITE_URL . '/public/icons/special-offer.png' ."' /></a>"; }




            $business_div = "<div id='lddbd_categories_left'>
					{$categories}
					</div>";

    }



    global $post;



    $template_vars = array(
        'body'  => $business_div,
        'url'   => get_permalink( $post->ID ),
    );

    return ld_parse_template( 'display/default', $template_vars );

}
