<?php

function lddlite_display_directory()
{
    global $wpdb, $tables;

    // Initialize $output and set our JavaScript location
    $output = '<script>var lddlite_ajax_url = "' . LDDLITE_AJAX . '"; var $lddurl = "' . LDDLITE_URL . '";</script>';

    if ( isset( $_GET['submit'] ) )
    {
        require_once( LDDLITE_PATH . '/includes/display_submit.php' );
        $output .= lddlite_display_submit_form();
    }
    else if ( array_key_exists( 'business', $_GET ) )
    {
        require_once( LDDLITE_PATH . '/includes/display_business.php' );
        $output .= lddlite_display_business();
    }
    else
    {
        require_once( LDDLITE_PATH . '/includes/display_directory.php' );
        $output .= lddlite_display_main();
	}

    return $output;

}
