<?php

/**
 *
 */

function lddlite_display_submit_form()
{
    global $wpdb, $post, $tables;

    $lddlite = lddlite();

    $page = isset( $_GET['page'] ) ? intval( $_GET['page'] ) : 1;
    $next_page = $page + 1;


    // Initialize our template variables.
    $template_vars = array(
        'form_action'   => get_permalink( $post->ID ) . '?submit=true&page=' . $next_page,
        'page'          => $page,
        'back'          => __( 'Back', $lddlite->slug() ),
        'next'          => __( 'Next', $lddlite->slug() ),
        'submit'        => __( 'Submit Listing', $lddlite->slug() )
    );

    if ( isset( $_POST['current_page'] ) )
    {
        $current_page = $_POST['current_page'];
        $valid = lddlite_process_page( $current_page );
    }

    echo lddlite_parse_template( 'submit/' . $page, $template_vars );

}



function lddlite_process_page( $current_page )
{
    return true;
}




/*
         { // Builds the category list for the submission form.
            $categories_list = $wpdb->get_results(
                "
			SELECT *
			FROM {$tables['cat']}
			"
            );

            $business_categories = "<div class='lddbd_input_holder'>";
            $business_categories .= "<label for='categories_multiselect'>Categories</label>";
            $business_categories .= "<select id='lddbd_categories_multiselect' name='categories_multiselect' multiple='multiple'>";

            foreach($categories_list as $category){
                $cat_name = stripslashes($category->name);
                $business_categories .= "<option value='x{$category->id}x'>{$cat_name}</option>";
            }

            $business_categories .= "</select>";
            $business_categories .= "<input id='lddbd_categories' type='hidden' name='categories'/>";
            $business_categories .= "</div>";

        }

        $template_vars = array(
            'form_action'           => LDDLITE_AJAX,
            'country_select'        => '<option value="USA">United States of America</option>',
            'display_categories'    => $business_categories,
        );

        echo lddlite_parse_template( 'display/submit', $template_vars );

 */