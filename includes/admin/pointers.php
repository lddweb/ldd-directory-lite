<?php
add_action( 'admin_enqueue_scripts', 'custom_admin_pointers_header' );

function custom_admin_pointers_header() {
    if ( custom_admin_pointers_check() ) {
        add_action( 'admin_print_footer_scripts', 'custom_admin_pointers_footer' );

        wp_enqueue_script( 'wp-pointer' );
        wp_enqueue_style( 'wp-pointer' );
    }
}

function custom_admin_pointers_check() {
    $admin_pointers = custom_admin_pointers();
    foreach ( $admin_pointers as $pointer => $array ) {
        if ( $array['active'] )
            return true;
    }
}

function custom_admin_pointers_footer() {
    $admin_pointers = custom_admin_pointers();
    ?>
    <script type="text/javascript">
        /* <![CDATA[ */
        ( function($) {
            <?php
            foreach ( $admin_pointers as $pointer => $array ) {
               if ( $array['active'] ) {
                  ?>
            $( '<?php echo $array['anchor_id']; ?>' ).pointer( {
                content: '<?php echo $array['content']; ?>',
                position: {
                    edge: '<?php echo $array['edge']; ?>',
                    align: '<?php echo $array['align']; ?>'
                },
                close: function() {
                    $.post( ajaxurl, {
                        pointer: '<?php echo $pointer; ?>',
                        action: 'dismiss-wp-pointer'
                    } );
                }
            } ).pointer( 'open' );
            <?php
         }
      }
      ?>
        } )(jQuery);
        /* ]]> */
    </script>
<?php
}

function custom_admin_pointers() {
    $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
    $version = '1_1'; // replace all periods in 1.0 with an underscore
    $prefix = 'custom_admin_pointers' . $version . '_';

    $new_pointer_content = '<h3>' . __( 'LDD Directory version' . LDDLITE_VERSION ) . '</h3>';
    $new_pointer_content .= '<p>' . __( 'Thank you for updating to the latest LDD Directory version! You can add, edit or remove listings from this menu. Some new features have been added since last time, so be sure to review your settings!' ) . '</p>';

    return array(
        $prefix . 'new_items' => array(
            'content' => $new_pointer_content,
            'anchor_id' => '#menu-posts-directory_listings',
            'edge' => 'top',
            'align' => 'left',
            'active' => ( ! in_array( $prefix . 'new_items', $dismissed ) )
        ),
    );
}