<?php
/**
 * Template Functions
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * This defines where the plugin should look for custom templates under a parent or child theme directory.
 *
 * @since 0.6.0
 * @return string
 */
function ldl_get_template_dir_name() {
    return trailingslashit(apply_filters('lddlite_template_dir_name', 'lddlite_templates'));
}


/**
 * This is a modified version of the core locate_template() function, providing for multiple paths to search
 * in before returning a template for use in presentation.
 *
 * @todo  How are we going to let developers know when there's major updates to a core template?
 * @since 0.6.0
 *
 * @param array $templates    The array of templates to look for
 * @param bool  $load         Whether to return the path, or to load the template
 * @param bool  $require_once Whether to require_once or require. Default true. Has no effect if $load is false.
 *
 * @return string The template filename if one is located.
 */
function ldl_locate_template($templates, $load = false, $require_once = true) {
	
    // No template found yet
    $located = false;

    $custom_path = ldl_get_template_dir_name();

    // Build an array of locations to search
    $template_paths = array(
        trailingslashit(get_stylesheet_directory()) . $custom_path,
        trailingslashit(get_template_directory()) . $custom_path,
        trailingslashit(LDDLITE_PATH . '/templates'), // Default
    );

    foreach ((array) $templates as $template) {

        // Continue if template is empty
        if (empty($template))
            continue;

        // Trim off any slashes from the template name
        $template = ltrim($template, '/');

        // try locating this template file by looping through the template paths
        foreach ($template_paths as $path) {

            if (file_exists(trailingslashit($path) . $template)) {
                $located = trailingslashit($path) . $template;
                break;
            }else{ //backward compatibility for old plugin version
	            $old_template = explode( '/', $template );
				
				
	            if (file_exists(trailingslashit($path) . $old_template[1]) && isset($old_template[1]) ) {
					
		            $located = trailingslashit($path) . $old_template[1];
		            break;
	            }
            }
        }

        if ($located) {
            break;
        }
    }
	
    if (true == $load && false != $located)
       load_template($located, $require_once);
	
    return $located;
}


/**
 * This duplicates get_template_part() verbatim, with the single exception of using our ldl_locate_template()
 * instead of the core locate_template()
 *
 * @since 0.6.0
 * @uses  ldl_locate_template()
 *
 * @param string $slug The parent template we're looking for
 * @param string $name The specific type of a particular parent template, if any
 */
function ldl_get_template_part($slug, $name = null, $load = true) {

    do_action('get_template_part_' . $slug, $slug, $name);

    $templates = array();
    $name = (string) $name;
    if ('' !== $name)
        $templates[] = "{$slug}-{$name}.php";

    $templates[] = "{$slug}.php";

    // Using the array, locate a template and return it
    return ldl_locate_template($templates, $load, false);
}


/** URL HELPERS */

/**
 * Get the link to the submit form
 *
 * @since 0.6.0
 */
function ldl_get_submit_link() {
    $post_id = ldl()->get_option('directory_submit_page');

    return ($post_id) ? get_permalink($post_id) : '';
}

/**
 * Get the link to the directory page
 *
 */
function ldl_get_directory_link() {
    $post_id = ldl()->get_option('directory_front_page');

    return ($post_id) ? get_permalink($post_id) : '';
}

/*
* Get directory page title
*/

function ldl_get_directory_title() {
    $post_id = ldl()->get_option('directory_front_page');

    return ($post_id) ? get_the_title($post_id) : '';
}

/**
 * Get the link to the management page
 *
 * @since 0.7.2
 * @TODO Code repetitititition, single function in the future? url helper class?
 */
function ldl_get_manage_link() {
    $post_id = ldl()->get_option('directory_manage_page');

    return ($post_id) ? get_permalink($post_id) : '';
}


/** CONDITIONALS */

/**
 * Are google maps turned on?
 *
 * @return bool True or false
 */
function ldl_use_google_maps() {

    if (is_single()) {
        global $geo;

        if (!isset($geo) || !is_array($geo) || in_array('', $geo) || !ldl()->get_option('google_maps'))
            return false;

        return true;
    }

    return ldl()->get_option('google_maps');
}


/** TEMPLATE UTILITIES */

/**
 * An alias for returning the header template (the header template has our navbar)
 */
function ldl_get_header() {
    $show_header = apply_filters('lddlite_filter_presentation_header', true);
    if ($show_header)
        ldl_get_template_part('global/header');
}


/**
 * This will check if we need a contact form, and if so enqueues the scripts and retrieves the appropriate template.
 */
function ldl_get_contact_form() {
	
    $post_id = get_the_ID();

    if (!$post_id)
        return;

    if (!get_post_meta($post_id, ldl_pfx('contact_email'), 1))
	return;

    echo '<script>var ajaxurl = "' . admin_url('admin-ajax.php') . '"</script>';

    wp_enqueue_script('lddlite-contact');
	
    ldl_get_template_part('global/contact', 'sidebar');
}


/**
 * An alias for ldl_get_categories that defaults to the top level categories.
 */
function ldl_get_parent_categories($attr = array()) {
    return ldl_get_categories(0,$attr);
}

/**
 * Deprecated. will be removed in future releases
 * Obtain a list of categories based on the provided parent ID, and return a formatted list for display
 * via one of the plugin templates.
 *
 * @param int $parent The term ID to retrieve categories from
 *
 * @return string Return a formatted string containing all category elements
 */
 
function ldl_get_categories($parent = 0,$attr = array()) {

	$custom_url = "";
	$custom_url_para = array();

	$sort_by 	= ldl()->get_option( 'directory_category_sort', 'business_name' );
	$sort_order = ldl()->get_option( 'directory_category_sort_order','asc' );
	$sub_check  = ldl()->get_option( 'subcategory_listings', 0 );
	$subcategory_listings = ($sub_check == 0) ? true : false;



	$args_arr = array(
		'order'		=> $sort_order,
		'orderby' 	=> $sort_by,
		'offset'       => $offset,
		'pad_counts'=> $subcategory_listings,
		
	);

	if(isset($parent) and !empty($parent)):
		$args_arr['parent'] 	= $parent;
	endif;

	if(isset($attr["cat_order_by"]) and !empty($attr["cat_order_by"])):
		$args_arr['orderby'] 	= $attr["cat_order_by"];
	endif;

	if(isset($attr["cat_order"]) and !empty($attr["cat_order"])):
		$args_arr['order'] 		= $attr["cat_order"];
	endif;

	if(isset($attr["list_order_by"]) and !empty($attr["list_order_by"])):
		$ls_sort_by 				 = $attr["list_order_by"];
		$custom_url_para["order_by"] = $ls_sort_by;
	endif;

	if(isset($attr["list_order"]) and !empty($attr["list_order"])):
		$ls_sort_order  	 	  = $attr["list_order"];
		$custom_url_para["order"] = $ls_sort_order;
	endif;

	if(isset($custom_url_para) and !empty($custom_url_para)):
		$custom_url = "?".http_build_query($custom_url_para);
	endif;

	$terms = get_terms(LDDLITE_TAX_CAT, $args_arr);
	$listing_view = ldl()->get_option( 'directory_view_type', 'compact' );
if($listing_view=='grid'){
	$gridclass= "col-xs-12 col-sm-6 col-md-4 type-grid grid-item ldd_list_grid";
}

	$mask = '<a href="%1$s'.$custom_url.'" class="list-group-item '.$gridclass.'"><span class="label label-primary pull-right">%3$d</span>%2$s</a>';

	$categories = array();
	if(!empty($terms) and !is_wp_error($terms)) {

		foreach ($terms as $category) {

			$term_link = get_term_link($category);
			$count = get_term_post_count( "listing_category", $category->term_id );
			//$categories[] = sprintf($mask, $term_link, $category->name, $category->count);
			$categories[] = sprintf($mask, $term_link, $category->name, $count);
		}

		$categories = apply_filters('lddlite_filter_presentation_categories', $categories, $terms, $mask);
		
	}
	
	return implode(' ', $categories);
}

/**
 * Obtain a list of categories based on the provided parent ID, and return a formatted list for display
 * via one of the plugin templates.
 *
 * @param int $parent The term ID to retrieve categories from
 *
 * @return string Return a formatted string containing all category elements
 */
function ldl_get_categories_li($parent = 0) {
	
	$args_arr = array('parent' => $parent);

	$sort_by 	= ldl()->get_option('directory_category_sort', 'business_name');
	$sort_order = ldl()->get_option('directory_category_sort_order','asc');
		
	if(isset($attr["cat_order_by"]) and !empty($attr["cat_order_by"])):
		$sort_by 	= $attr["cat_order_by"];
	endif;

	if(isset($attr["cat_order"]) and !empty($attr["cat_order"])):
		$sort_order = $attr["cat_order"];
	endif;	

	
	if($sort_by == "title"):
		$args_arr = array(
					  'orderby'	=> 'name', 
					  'order'	=> $sort_order,
					  'parent'	=> $parent
				  	); 
	elseif ($sort_by == "id"):		
		$args_arr = array(
					  'orderby'	=> 'id', 
					  'order'	=> $sort_order,
					  'parent'	=> $parent
				  	); 						
	elseif ($sort_by == "slug"):
		$args_arr = array(
					  'orderby'	=> 'slug', 
					  'order'	=> $sort_order,
					  'parent'	=> $parent
				  	); 					
	elseif ($sort_by == "count"):	
		$args_arr = array(
					  'orderby'	=> 'count', 
					  'order'	=> $sort_order,
					  'parent'	=> $parent
				  	); 
	endif;				

	$custom_url = "";
	$custom_url_para = array();
	
	if(isset($attr["list_order_by"]) and !empty($attr["list_order_by"])):
		$ls_sort_by 				 = $attr["list_order_by"];
		$custom_url_para["order_by"] = $ls_sort_by;
	endif;

	if(isset($attr["list_order"]) and !empty($attr["list_order"])):
		$ls_sort_order  	 	  = $attr["list_order"];
		$custom_url_para["order"] = $ls_sort_order;
	endif;	

	if(isset($custom_url_para) and !empty($custom_url_para)):
		$custom_url = "?".http_build_query($custom_url_para);
	endif;
		
    $terms = get_terms(LDDLITE_TAX_CAT, $args_arr);

    $mask = '<li><a href="%1$s'.$custom_url.'">%2$s</a></li>';

    $categories = array();
	if(!empty($terms) and !is_wp_error( $terms )) {
	  foreach ($terms as $category) {
		  $term_link = get_term_link($category);
		  $categories[] = sprintf($mask, $term_link, $category->name);
	  }
	}
    return implode(' ', $categories);
}

/**
 * Returns a post thumbnail/logo for the provided ID. If none is found, a default image is returned.
 *
 * @param int $post_id The post ID
 *
 * @return string
 */
function ldl_get_thumbnail($post_id, $size = 'directory-listing', $class = 'img-rounded img-responsive') {

	$allow_image_placeholder = ldl()->get_option('general_display_img_placeholder','yes');

    if (has_post_thumbnail($post_id)) {
        $thumbnail = get_the_post_thumbnail($post_id, $size, array('class' => $class));
    } else if(ldl()->get_option('ldd_placeholder_image') and $allow_image_placeholder === 'yes') {
        $thumbnail = '<img src="' . ldl()->get_option('ldd_placeholder_image') . '" class="' . $class . '">';
    } else if(isset($allow_image_placeholder) and $allow_image_placeholder === 'yes') {
        $thumbnail = '<img src="' . LDDLITE_NOLOGO . '" class="' . $class . '">';
    }

    return apply_filters('lddlite_filter_presentation_thumbnail', $thumbnail, $post_id, $size, $class);
}


/**
 * Single helper function for determining if a terms of service section should be displayed on the submit form. If
 * set to true and content has been provided, get the appropriate template part.
 */
function ldl_the_tos() {
    if (!ldl()->get_option('submit_use_tos') || '' == ldl()->get_option('submit_tos')) {
        return;
    }

    ldl_get_template_part('frontend/submit', 'tos');
}

/** LISTING META UTILITES */

/**
 * Return a piece of the geo post meta.
 *
 * @param string $key Should be one of 'formatted', 'lat', or 'lng'
 *
 * @return string|false Returns the value for the requested key if found, false otherwise
 */
function ldl_get_address() {

    $post_id = get_the_ID();

    if (!is_int($post_id))
        return false;

    $address_one = get_post_meta($post_id, ldl_pfx('address_one'), true);
    $address_two = get_post_meta($post_id, ldl_pfx('address_two'), true);
	$city 		 = get_post_meta($post_id, ldl_pfx('city'), true);
	$state 		 = get_post_meta($post_id, ldl_pfx('state'), true);
    $postal_code = get_post_meta($post_id, ldl_pfx('postal_code'), true);
    $country 	 = get_post_meta($post_id, ldl_pfx('country'), true);

    $output = '';
    $output .= empty($address_one) ? '' : $address_one;
    $output .= empty($address_two) ? '' : ', ' . $address_two;
	$output .= empty($city) ? '' : ', ' . $city;
	$output .= empty($state) ? '' : ', ' . $state;
	$output .= empty($postal_code) ? '' : ', ' . $postal_code;
    $output .= empty($country) ? '' : ', ' . $country;

    $output = apply_filters('lddlite_presentation_get_address', $output, $post_id, compact('address_one', 'address_two', 'city', 'state', 'postal_code', 'country'));

    return $output ? $output : false;
}


/**
 * Get an array of social media links for the designated post, and return it as a string to be used
 * in various templates.
 *
 * @param int $post_id The post ID
 *
 * @return string
 */
function ldl_get_social($post_id) {

    if (!is_int($post_id))
        return false;

    // Get the links for this listing
    $social = array(
        'facebook' => ldl_force_scheme(get_post_meta($post_id, ldl_pfx('url_facebook'), 1)),
        'linkedin' => ldl_force_scheme(get_post_meta($post_id, ldl_pfx('url_linkedin'), 1)),
        'twitter'  => ldl_sanitize_twitter(get_post_meta($post_id, ldl_pfx('url_twitter'), 1)),
		'instagram' => ldl_force_scheme(get_post_meta($post_id, ldl_pfx('url_instagram'), 1)),
        'google-plus' => ldl_force_scheme(get_post_meta($post_id, ldl_pfx('url_googleplus'), 1)),
        'youtube'  => ldl_force_scheme(get_post_meta($post_id, ldl_pfx('url_youtube'), 1)),
    );

    $titles = array(
        'facebook' => 'Visit %1$s on Facebook',
        'linkedin' => 'Connect with %1$s on LinkedIn',
        'twitter'  => 'Follow %1$s on Twitter',
		 'instagram' => 'Visit %1$s on Instagram',
        'google-plus' => 'Connect with %1$s on Google +',
        'youtube'  => 'Follow %1$s on Youtube',
        'default'  => 'Visit %1$s on %2$s',
    );

    $name = get_the_title($post_id);


    // Start building an array of links
    $output = array();

    foreach ($social as $key => $url) {
        if (!empty($url)) {
            $title_key = array_key_exists($key, $titles) ? $titles[ $key ] : $titles['default'];
            $title = sprintf(__($title_key, 'ldd-directory-lite'), $name, $key);
			if($key=="instagram"){
			 $output[] = '<a target="_blank" href="' . $url . '" title="' . $title . '"><i class="fa fa-' . $key . '"></i></a>';
				} else {
            $output[] = '<a target="_blank" href="' . $url . '" title="' . $title . '"><i class="fa fa-' . $key . '-square"></i></a>';
				}
        }
    }

    /**
     * Allow developers to filter these links before returning them to the template
     *
     * @param array $output  An array of social links
     * @param int   $post_id The post ID
     */
    $output = apply_filters('lddlite_filter_presentation_social', $output, $post_id);

    return implode(' ', $output);
}

/**
 * Get Featured Posts
 */
function ldl_get_featured_posts($args = null, $attr = array()) {

	$featured_listings_limit = (int) ldl()->get_option('featured_listings_limit', '3');
	$sort_by 	             = ldl()->get_option('directory_featured_sort', 'business_name');
	$sort_order              = ldl()->get_option('directory_featured_sort_order','asc');
	$f_terms               = ldl()->get_option('featured_listings_tags');
	$ldd_terms          = explode(",",$f_terms);

	if(isset($attr["fl_order_by"]) and !empty($attr["fl_order_by"])):
		$sort_by 	= $attr["fl_order_by"];
	endif;

	if(isset($attr["fl_order"]) and !empty($attr["fl_order"])):
		$sort_order = $attr["fl_order"];
	endif;	



				if($sort_by == "business_name"):
					$defaults = array(
						'post_type'      => LDDLITE_POST_TYPE,
						'tax_query'      => array(
							array(
								'taxonomy' => LDDLITE_TAX_TAG,
								'field'    => 'slug',
								'terms'    => $ldd_terms,
								'paged'          => $paged,
							),
						),
						'orderby'        => 'title',
						'order' 		 => $sort_order,
						'posts_per_page' => $featured_listings_limit
					);				
				elseif ($sort_by == "zip"):		
					$defaults = array(
						'post_type'      => LDDLITE_POST_TYPE,
						'tax_query'      => array(
							array(
								'taxonomy' => LDDLITE_TAX_TAG,
								'field'    => 'slug',
								'terms'    => $ldd_terms,
							),
						),
						'meta_key'       => '_lddlite_postal_code',
						'orderby'        => 'meta_value',
						'order' 		 => $sort_order,
						'posts_per_page' => $featured_listings_limit
					);
				elseif ($sort_by == "area"):	
					$defaults = array(
						'post_type'      => LDDLITE_POST_TYPE,
						'tax_query'      => array(
							array(
								'taxonomy' => LDDLITE_TAX_TAG,
								'field'    => 'slug',
								'terms'    => $ldd_terms,
							),
						),
						'meta_key'       => '_lddlite_country',
						'orderby'        => 'meta_value',
						'order' 		 => $sort_order,
						'posts_per_page' => $featured_listings_limit
					);				
				elseif ($sort_by == "category"):	
					$defaults = array(
						'post_type'      => LDDLITE_POST_TYPE,
						'tax_query'      => array(
							array(
								'taxonomy' => LDDLITE_TAX_TAG,
								'field'    => 'slug',
								'terms'    => $ldd_terms,
							),
						),
						'order' 		 => $sort_order,
						'posts_per_page' => $featured_listings_limit
					);						
				elseif ($sort_by == "random"):	
					$defaults = array(
						'post_type'      => LDDLITE_POST_TYPE,
						'tax_query'      => array(
							array(
								'taxonomy' => LDDLITE_TAX_TAG,
								'field'    => 'slug',
								'terms'    => $ldd_terms,
							),
						),
						'orderby'        => 'rand',
						'order' 		 => $sort_order,
						'posts_per_page' => $featured_listings_limit
					);				
				endif;

    $args = wp_parse_args($args, $defaults);
    return new WP_Query($args);
}


/*
* Get Directory listing for home page
*/

function ldl_get_directory_listing() {

		$sort_by    = ldl()->get_option( 'directory_listings_sort', 'business_name' );
		$sort_order = ldl()->get_option( 'directory_listings_sort_order', 'asc' );
		$posts_per_page = ldl()->get_option( 'listings_display_number' );//get_option( 'posts_per_page' );
		
		if($posts_per_page == ''){
			$posts_per_page = -1;
			}
		$paged          = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		if ( $sort_by == "business_name" ):
				$args= array(
					'orderby'        => 'title',
					'order'          => $sort_order,
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					
				);
				elseif ( $sort_by == "id" ):
				$args= array(
					'orderby'        => 'ID',
					'order'          => $sort_order,
					'post_type'      => LDDLITE_POST_TYPE,
					'paged'          => $paged,
					'posts_per_page' => $posts_per_page,
					
				 );
			
			
			elseif ( $sort_by == "zip" ):
				$args= array(
					'meta_key'       => '_lddlite_postal_code',
					'order'          => $sort_order,
					'orderby'        => 'meta_value',
					'post_type'      => LDDLITE_POST_TYPE,
					'paged'          => $paged,
					'posts_per_page' => $posts_per_page,
					
				 );
			
				elseif ( $sort_by == "country" ):
				$args== array(
					'meta_key'       => '_lddlite_country',
					'order'          => $sort_order,
					'orderby'        => 'meta_value',
					'post_type'      => LDDLITE_POST_TYPE,
					'paged'          => $paged,
					'posts_per_page' => $posts_per_page,
					

				 );
				elseif ( $sort_by == "city" ):
				$args= array(
					'meta_key'       => '_lddlite_city',
					'order'          => $sort_order,
					'orderby'        => 'meta_value',
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					

				 );
				elseif ( $sort_by == "state" ):
				$args= array(
					'meta_key'       => '_lddlite_state',
					'order'          => $sort_order,
					'orderby'        => 'meta_value',
					'post_type'      => LDDLITE_POST_TYPE,
					'paged'          => $paged,
					'posts_per_page' => $posts_per_page,
					

				 );
			
			elseif ( $sort_by == "random" ):
				$args= array(
					'orderby'        => 'rand',
					'order'          => $sort_order,
					'post_type'      => LDDLITE_POST_TYPE,
					'paged'          => $paged,
					'posts_per_page' => $posts_per_page,
					
				 );
			endif;

	$defaults = array(
						'post_type'      => LDDLITE_POST_TYPE,
						
						'orderby'        => 'date',
						
						'posts_per_page' => $posts_per_page
					);		
					
	//$args = wp_parse_args($args, $defaults);
	
    return new WP_Query($args);

}

/**
 * Get listings by user
 */
function ldl_get_listings_by_current_author() {

    $user_ID = get_current_user_id();

    $args = array(
        'post_type' => LDDLITE_POST_TYPE,
        'author'    => $user_ID,
    );

    return new WP_Query($args);
}


/**
 * Return a link for use on the manage listings page which opens up the listing editor
 */
function ldl_edit_link($post_id, $action) {
    echo add_query_arg(
        array(
            'id'  => $post_id,
            'edit' => $action,
        ),
        remove_query_arg('msg')
    );
}

function ldd_get_tag_ID($tag_name) {
	$tag = get_term_by('name', $tag_name, 'listing_tag');
	if ($tag) {
		return $tag->term_id;
	} else {
		return 0;
	}
}

/**
 * Global functions for hooks
 */

if ( ! function_exists( 'ldd_output_content_wrapper' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function ldd_output_content_wrapper() {
		ldl_get_template_part( 'global/wrapper-start' );
	}
}
if ( ! function_exists( 'ldd_output_content_wrapper_end' ) ) {

	/**
	 * Output the end of the page wrapper.
	 *
	 */
	function ldd_output_content_wrapper_end() {
		ldl_get_template_part( 'global/wrapper-end' );
	}
}

if ( ! function_exists( 'ldd_default_pagination' ) ) {

	/**
	 * Output the pagination.
	 *
	 * @subpackage	Loop
	 */
	function ldd_default_pagination() {
		ldl_get_template_part( 'loop/pagination' );
	}
}

/**
	 * Show tags on listing detail page
	 *
	 * @subpackage	
	 */

function ldd_custom_taxonomies_terms_links() {
    // Get post by post ID.
    $post = get_post( $post->ID );
 
    // Get post type by post.
    $post_type = $post->post_type;
 
    // Get post type taxonomies.
    $taxonomies = get_object_taxonomies( $post_type, 'objects' );
 
    $out = array();
 
    foreach ( $taxonomies as $taxonomy_slug => $taxonomy ){
        if($taxonomy_slug == 'listing_category'){
            continue;
        }
        // Get the terms related to post.
        $terms = get_the_terms( $post->ID, $taxonomy_slug );
 
        if ( ! empty( $terms ) ) {
            $out[] = '<img src="'.LDDLITE_URL.'/public/images/tags.png">  ';
            foreach ( $terms as $term ) {
                $out[] = sprintf( '<a href="%1$s">%2$s</a> ',
                    esc_url( get_term_link( $term->slug, $taxonomy_slug ) ),
                    //esc_url( get_bloginfo('url')."?post_type=directory_listings&s=".$term->name),
                    esc_html( $term->name )
                );
            }
            $out[] = "";
        }
    }
    return implode( '', $out );
}



function ldd_show_all_cat(){



	$sort_by 	= ldl()->get_option( 'directory_category_sort', 'business_name' );
	$sort_order = ldl()->get_option( 'directory_category_sort_order','asc' );
	$sub_check  = ldl()->get_option( 'subcategory_listings', 0 );
	$subcategory_listings = ($sub_check == 0) ? true : false;
	$per_page   = ldl()->get_option( 'listings_category_number', 10 );
	

if ( get_query_var( 'paged' ) )
$paged = get_query_var('paged');
else if ( get_query_var( 'page' ) )
$paged = get_query_var( 'page' );
else
$paged = 1;

//$per_page    = 2;
$number_of_series = count( get_terms( LDDLITE_TAX_CAT,array('hide_empty'=>'0') ) );
$offset      = $per_page * ( $paged -1) ;

	$args_arr = array(
		'order'		=> $sort_order,
		'orderby' 	=> $sort_by,
		'offset'       => $offset,
		'pad_counts'=> $subcategory_listings,
		'number'       => $per_page,
		'hide_empty'=>'0'
		
	);

	if(isset($parent) and !empty($parent)):
		$args_arr['parent'] 	= $parent;
	endif;

	if(isset($attr["cat_order_by"]) and !empty($attr["cat_order_by"])):
		$args_arr['orderby'] 	= $attr["cat_order_by"];
	endif;

	if(isset($attr["cat_order"]) and !empty($attr["cat_order"])):
		$args_arr['order'] 		= $attr["cat_order"];
	endif;

	if(isset($attr["list_order_by"]) and !empty($attr["list_order_by"])):
		$ls_sort_by 				 = $attr["list_order_by"];
		$custom_url_para["order_by"] = $ls_sort_by;
	endif;

	if(isset($attr["list_order"]) and !empty($attr["list_order"])):
		$ls_sort_order  	 	  = $attr["list_order"];
		$custom_url_para["order"] = $ls_sort_order;
	endif;

	if(isset($custom_url_para) and !empty($custom_url_para)):
		$custom_url = "?".http_build_query($custom_url_para);
	endif;



	

// Setup the arguments to pass in
/*$args_arr = array(
'offset'       => $offset,
'number'       => $per_page,
'hide_empty'=>'0'
);*/

// Gather the series
$mycategory = get_terms( LDDLITE_TAX_CAT, $args_arr );


// Loop through and display the series
foreach($mycategory as $s)
{
$theurl = get_term_link($s, 'mycategory');
if ( is_wp_error( $theurl ) ) {
    // something went wrong
   // echo $theurl->get_error_message();
   continue;
}
$listing_view = ldl()->get_option( 'directory_view_type', 'compact' );
if($listing_view=='grid'){
	$gridclass= "col-xs-12 col-sm-6 col-md-4 type-grid grid-item ldd_list_grid";
	$cls2 = "grid_image";
	
} else{
	$cls2="col-xs-2";
	$cls10 = "col-xs-10";
}
$cat_img ="";
$img_url = get_term_meta($s->term_id,'avatar',true);
if($img_url){
$cat_img = "<img src='".$img_url."' width='80px'>";
}
$count = get_term_post_count( "listing_category", $s->term_id );
echo "<div class=\"ser-img img\" >
<a class='list-group-item ".$gridclass."' href=\"" . $theurl  . "\">
<div class='".$cls2."'>".$cat_img."</div>
<div class='".$cls10."'>". $s->name."<span class=\"label label-primary pull-right\">".$count."</span><br>".$s->description ."
</div>
</a></div>";

}
echo "<nav class='ldd_listing_pagination clearfix'>";
$big = 999999;
echo paginate_links(apply_filters( 'ldd_pagination_args', array(
'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
'format'  => '?paged=%#%',
'current' => $paged,
'prev_text'    => '&larr;',
		'next_text'    => '&rarr;',
		'type'         => 'list',
'total'   => ceil( $number_of_series / $per_page ) // 3 items per page
) ));
echo "</nav>";
}

// this function adds css from directoy setting->appearance
function ldd_plugin_css(){
	
	?>
<style>
<?php $hbcolor = ldl()->get_option( 'appearance_header_background' ); 
 $bac_txt_color = ldl()->get_option( 'appearance_header_text' ); 
 $button_color = ldl()->get_option( 'appearance_primary_normal',"#3bafda" ); 
 $button_color_hover = ldl()->get_option( 'appearance_primary_hover',"#3bafda" ); 
 $button_color_txt = ldl()->get_option( 'appearance_primary_foreground',"#fff" ); 
 $txt_color_link = ldl()->get_option( 'link_text_color',"#337ab7" ); 
 $txt_color_hover = ldl()->get_option( 'link_text_hover',"#337ab7" ); 
 if($listing_view = ldl()->get_option( 'directory_view_type') =="compact"){
	 $underline = "underline";
 }
 if($listing_view = ldl()->get_option( 'directory_view_type') =="grid"){
	 $underline = "underline";
 }
 if($listing_view = ldl()->get_option( 'home_page_listing') =="category"){
	 $underline = "underline";
 }
 if($listing_view = ldl()->get_option( 'home_page_listing') =="lisitng"){
	 $underline = "underline";
 }
 ?>
.bootstrap-wrapper .navbar-inverse{background-color:<?php echo $hbcolor;?> !important}
.bootstrap-wrapper .navbar-inverse{border-color:<?php echo $hbcolor;?> !important}
.bootstrap-wrapper .navbar-inverse .navbar-nav > li > a, .show_search{color:<?php echo $bac_txt_color;?> !important}
.bootstrap-wrapper .btn-primary, .label-primary,.ldd_listing_pagination a,.social-meta .fa {
    color: <?php echo $button_color_txt;?> !important;
    background-color: <?php echo $button_color;?> !important;
    border-color: <?php echo $button_color;?> !important;
}
.social-meta .fa {
	color: <?php echo $button_color;?> !important;
    background-color: <?php echo $button_color_txt;?> !important;
    border-color: <?php echo $button_color_txt;?> !important;
}
.social-meta .fa:hover, .grid_socials .fa:hover{color: <?php echo $button_color_hover;?> !important;}
.listing-meta .fa{color:<?php echo $button_color;?> !important}
.bootstrap-wrapper .btn-primary:hover,.ldd_listing_pagination .current,.ldd_listing_pagination a:hover {
    
    background-color: <?php echo $button_color_hover;?> !important;
    border-color: <?php echo $button_color_hover;?> !important;
}
#navbar-directory{border:none !important}
.bootstrap-wrapper  a{color:<?php echo $txt_color_link;?> !important}
.bootstrap-wrapper  a:hover{color:<?php echo $txt_color_hover;?> !important}
.view_controls .category,.view_controls .listing,.view_controls .grid,.view_controls .compact{text-decoration:underline;}
</style>

	<?php

	
}

add_action( 'wp_enqueue_scripts', 'ldd_plugin_css' );
function ldd_inc_temp(){
	ldl_get_template_part('single2');
}


function ldd_get_custom_post_type_template($content) {
     if (is_singular('directory_listings') && in_the_loop()) {
		 $content='';
		$content.= ldd_inc_temp();
	 }
	
	 return $content;
}

//add_filter( 'the_content', 'ldd_get_custom_post_type_template' );

function ldd_check_template_version(){

	 $filedata = get_file_data(plugin_dir_url('ldd-directory-lite') . 'ldd-directory-lite/templates/category.php', array(
    'version' => 'File version'
    
        ));
    print_r($filedata);
	//return "here";

}

function ldd_show_prof(){
	$current_user = wp_get_current_user();
	?>
	<div class="logout_link">Hi <?php echo $current_user->display_name;?><br> <a href="<?php echo wp_logout_url( home_url() ); ?> ">  Logout</a></div>
	<?php
}

/*
* Directory Listing View
*/
function ldd_directory_layout()
{
	if(isset($_GET['ldd_view'])){
		$page_id= ldl()->get_option('directory_front_page'); 
		if($_GET['ldd_view'] == "listing"){
			 ldl()->get_option('directory_front_page'); 
			$opt_array = get_option('lddlite_settings'); 
			
			$arr2 = $opt_array['home_page_listing']='listing';
			
			
			if(update_option('lddlite_settings',$opt_array)){
				wp_redirect(get_bloginfo('url').'?page_id='.$page_id);
				exit;
			}
			
		}
		if($_GET['ldd_view'] == "category"){
			$opt_array = get_option('lddlite_settings'); 
			
			$arr2 = $opt_array['home_page_listing']='category';
			
			
			if(update_option('lddlite_settings',$opt_array)){
				wp_redirect(get_bloginfo('url').'?page_id='.$page_id);
				exit;
			}
		}
			
			if($_GET['ldd_view'] == "map"){
			$opt_array = get_option('lddlite_settings'); 
			
			$arr2 = $opt_array['home_page_listing']='map';
			
			
			if(update_option('lddlite_settings',$opt_array)){
				wp_redirect(get_bloginfo('url').'?page_id='.$page_id);
				exit;
			}
			
		}
		if($_GET['ldd_view'] == "compact"){
			$opt_array = get_option('lddlite_settings'); 
			global $post;
			$arr2 = $opt_array['directory_view_type']='compact';
			
			
			if(update_option('lddlite_settings',$opt_array)){
				wp_redirect(wp_sanitize_redirect("http://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
				exit;
			}
			
		}
		if($_GET['ldd_view'] == "grid"){
			$opt_array = get_option('lddlite_settings'); 
			
			$arr2 = $opt_array['directory_view_type']='grid';
			
			
			if(update_option('lddlite_settings',$opt_array)){
				wp_redirect(wp_sanitize_redirect("http://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
				exit;
			}
			
		}
	}

}
add_action('init','ldd_directory_layout');