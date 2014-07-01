<?php
/**
 * This is more of a temporary storage while I clean up the mess I made with RainTPL. In the future this could
 * become the home of deprecated functionality that we want to keep for backwards compatibility but as of now
 * it could disappear within a commit or two.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * BEGIN actions/category.php
 */
function ldl_action__category($cat_id) {
    global $post;

    $tpl = ldl_get_template_object();


    $listings = get_posts(array(
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'post_type'      => LDDLITE_POST_TYPE,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => LDDLITE_TAX_CAT,
                'field'    => 'id',
                'terms'    => $cat_id,
            ),
        ),
    ));

    $output = '';
    $nth = 0;

    if (!empty($listings)) {

        $gridview = (isset($_GET['f']) && 'grid' == $_GET['f']) ? true : false;

        if ($gridview)
            $output .= '<div class="row">';

        foreach ($listings as $listing) {

            $nth_class = ($nth % 2) ? 'odd' : 'even';
            $nth++;

            $id = $listing->ID;
            $title = $listing->post_title;
            $summary = $listing->post_excerpt;

            $meta = ldl_get_listing_meta($id);
            $address = $meta['address'];
            $website = $meta['website'];
            $email = $meta['email'];
            $phone = $meta['phone'];
            $social = ldl_get_social($id, 'default', false);

            $link = add_query_arg(array(
                'show' => 'listing',
                't'    => $listing->post_name,
            ));

            //	        $link = get_permalink( $id );


            // the following is used to build our title, and the logo
            $link_mask = '<a href="' . $link . '" title="' . esc_attr($title) . '">%1$s</a>';

            // the logo
            if (has_post_thumbnail($id))
                $thumbnail = sprintf($link_mask, get_the_post_thumbnail($id, 'directory-listing', array('class' => 'img-rounded'))); else
                $thumbnail = sprintf($link_mask, '<img src="' . LDDLITE_URL . 'public/images/noimage.png" class="img-rounded">');

            if (empty($summary)) {
                $summary = $listing->post_content;

                $summary = strip_shortcodes($summary);

                $summary = apply_filters('lddlite_the_content', $summary);
                $summary = str_replace(']]>', ']]&gt;', $summary);

                $excerpt_length = apply_filters('lddlite_excerpt_length', 35);
                $excerpt_more = apply_filters('lddlite_excerpt_more', '&hellip;');

                $summary = wp_trim_words($summary, $excerpt_length, $excerpt_more);
            }

            $tpl->assign('id', $id);
            $tpl->assign('nth', $nth_class);
            $tpl->assign('thumbnail', $thumbnail);
            $tpl->assign('title', sprintf($link_mask, $title));

            $tpl->assign('social', $social);
            $tpl->assign('address', $address);
            $tpl->assign('website', $website);
            $tpl->assign('email', $email);
            $tpl->assign('phone', $phone);

            $tpl->assign('summary', $summary);

            $draw = ($gridview) ? 'listing-grid' : 'listing-compact';
            $output .= $tpl->draw($draw, 1);
            if ($gridview && (0 === $nth % 4))
                $output .= '</div><div class="row">';


        } // foreach

        if ($gridview)
            $output .= '</row>';
    } // if

    $tpl = ldl_get_template_object();

    $tpl->assign('header', ldl_get_header('category'));

    $tpl->assign('home', remove_query_arg(array(
        'show',
        't',
    )));

    $tpl->assign('list_link', remove_query_arg(array('f')));
    $tpl->assign('grid_link', add_query_arg(array(
        'f' => 'grid',
    )));
    $tpl->assign('url', get_permalink($post->ID));

    $tpl->assign('listings', $output);

    return $tpl->draw('category', 1);

}

/**
 * END actions/category.php
 */

/**
 * BEGIN actions/listing.php
 */
function ldl_action__listing($term) {

    $listing = get_posts(array(
        'name'           => $term,
        'post_type'      => LDDLITE_POST_TYPE,
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'no_found_rows'  => true,
    ));

    if (!empty($listing)) {
        $listing = $listing[0];
    }


    $terms = wp_get_post_terms($listing->ID, LDDLITE_TAX_CAT);
    if (isset($terms[0])) {
        $term_link = add_query_arg(array(
            'show' => 'category',
            't'    => $terms[0]->slug,
        ));
        $term_name = $terms[0]->name;
    }

    $tpl = ldl_get_template_object();

    $post_id = $listing->ID;
    $title = $listing->post_title;
    $meta = ldl_get_listing_meta($post_id);
    $address = $meta['address'];
    $website = $meta['website'];
    $email = $meta['email'];
    $phone = $meta['phone'];
    $social = ldl_get_social($post_id, '');

    if (has_post_thumbnail($post_id))
        $thumbnail = get_the_post_thumbnail($post_id, 'directory-listing', array('class' => 'img-rounded')); else
        $thumbnail = '<img src="' . LDDLITE_URL . 'public/images/noimage.png" class="img-rounded">';

    $geocode = false;

    if (!empty($meta['geocode'])) {

        $get_address = 'http://maps.google.com/maps/api/geocode/json?address=' . $meta['geocode'] . '&sensor=false';
        $data = wp_remote_get($get_address);

        if (isset($data['response']) && '200' == $data['response']['code']) {

            $output = json_decode($data['body']);

            $geocode = array(
                'lat' => $output->results[0]->geometry->location->lat,
                'lng' => $output->results[0]->geometry->location->lng,
            );

        }

    }

    $tpl->assign('header', ldl_get_header('category'));


    $tpl->assign('home', remove_query_arg(array('show', 't')));


    $tpl->assign('id', $post_id);
    $tpl->assign('title', $title);

    $tpl->assign('term_link', $term_link);
    $tpl->assign('term_name', $term_name);

    $tpl->assign('thumbnail', $thumbnail);

    $tpl->assign('address', $address);
    $tpl->assign('website', $website);
    $tpl->assign('phone', $phone);

    $tpl->assign('social', $social);

    $google_maps = (ldl_use_google_maps() && $geocode) ? true : false;
    $tpl->assign('google_maps', $google_maps);
    $tpl->assign('geo', $geocode);
    $tpl->assign('description', wpautop($listing->post_content));


    return $tpl->draw('listing', 1);
}

/**
 * END actions/listing.php
 */


/**
 * BEGIN actions/search.php
 */
function ldl_action__search($terms) {
    global $post;

    $tpl = ldl_get_template_object();

    $terms = sanitize_text_field($terms);

    $listings = get_posts(array(
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'DESC',
        'post_type'      => LDDLITE_POST_TYPE,
        'post_status'    => 'publish',
        's'              => $terms,
    ));

    $output = '';
    $nth = 0;

    if (!empty($listings)) {

        $gridview = (isset($_GET['f']) && 'grid' == $_GET['f']) ? true : false;

        if ($gridview)
            $output .= '<div class="row">';

        foreach ($listings as $listing) {

            $nth_class = ($nth % 2) ? 'odd' : 'even';
            $nth++;

            $id = $listing->ID;
            $title = $listing->post_title;
            $summary = $listing->post_excerpt;

            $meta = ldl_get_listing_meta($id);
            $address = $meta['address'];
            $website = $meta['website'];
            $email = $meta['email'];
            $phone = $meta['phone'];
            $social = ldl_get_social($id, 'default', false);

            $link = add_query_arg(array(
                'show' => 'listing',
                't'    => $listing->post_name,
            ));


            // the following is used to build our title, and the logo
            $link_mask = '<a href="' . $link . '" title="' . esc_attr($title) . '">%1$s</a>';

            // the logo
            if (has_post_thumbnail($id))
                $thumbnail = sprintf($link_mask, get_the_post_thumbnail($id, 'directory-listing', array('class' => 'img-rounded'))); else
                $thumbnail = sprintf($link_mask, '<img src="' . LDDLITE_URL . 'public/images/noimage.png" class="img-rounded">');

            if (empty($summary)) {
                $summary = $listing->post_content;

                $summary = strip_shortcodes($summary);

                $summary = apply_filters('lddlite_the_content', $summary);
                $summary = str_replace(']]>', ']]&gt;', $summary);

                $excerpt_length = apply_filters('lddlite_excerpt_length', 35);
                $excerpt_more = apply_filters('lddlite_excerpt_more', '&hellip;');

                $summary = wp_trim_words($summary, $excerpt_length, $excerpt_more);
            }

            $tpl->assign('id', $id);
            $tpl->assign('nth', $nth_class);
            $tpl->assign('thumbnail', $thumbnail);
            $tpl->assign('title', sprintf($link_mask, $title));

            $tpl->assign('social', $social);
            $tpl->assign('address', $address);
            $tpl->assign('website', $website);
            $tpl->assign('email', $email);
            $tpl->assign('phone', $phone);

            $tpl->assign('summary', $summary);

            $draw = ($gridview) ? 'listing-grid' : 'listing-compact';
            $output .= $tpl->draw($draw, 1);
            if ($gridview && (0 === $nth % 4))
                $output .= '</div><div class="row">';


        } // foreach

        if ($gridview)
            $output .= '</row>';
    } // if

    $tpl = ldl_get_template_object();

    $tpl->assign('header', ldl_get_header('category'));
    $tpl->assign('home', remove_query_arg(array(
        'show',
        't',
    )));
    $tpl->assign('terms', $terms);
    $tpl->assign('list_link', remove_query_arg(array('f')));
    $tpl->assign('grid_link', add_query_arg(array(
        'f' => 'grid',
    )));
    $tpl->assign('url', get_permalink($post->ID));

    $tpl->assign('listings', $output);

    return $tpl->draw('search', 1);
}
/**
 * END actions/search.php
 */