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



class LDL_Pointers {

	/**
	 * @var $_instance An instance of ones own instance
	 */
	private static $_instance = null;

	/**
	 * Class constructor.
	 */
	private function __construct() {
		if ( current_user_can( 'manage_options' ) && !ldl_get_setting( 'allow_tracking_pointer_done' ) ) {
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'jquery-ui' );
			wp_enqueue_script( 'wp-pointer' );
			wp_enqueue_script( 'utils' );
			add_action( 'admin_print_footer_scripts', array( $this, 'print_scripts' ) );
		}
	}

	/**
	 * Get the singleton instance of this class
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( null === self::$_instance )
			self::$_instance = new self;
		return self::$_instance;
	}


	function print_scripts() {

		$nonce = wp_create_nonce( 'lite_allow_tracking_nonce' );

		$content = '<h3>' . __( 'Help improve LDD Directory Lite', 'lddlite' ) . '</h3>';
		$content .= '<p>' . __( 'Usage tracking is completely anonymous and allows us to know what configurations, plugins and themes we should be testing future versions of our plugin with.', 'lddlite' ) . '</p>';

		$opt_arr = array(
			'content'  => $content,
			'position' => array( 'edge' => 'top', 'align' => 'center' )
		);

		?>
		<script type="text/javascript">
			//<![CDATA[
			(function ($) {
				var lite_pointer_options = <?php echo json_encode( $opt_arr ); ?>, setup;

				function ldl_store_answer(input, nonce) {
					var ldl_tracking_data = {
						action        : 'lite_allow_tracking',
						allow_tracking: input,
						nonce         : nonce
					};
					jQuery.post(ajaxurl, ldl_tracking_data, function () {
						jQuery('#wp-pointer-0').remove();
					});
				}

				lite_pointer_options = $.extend(lite_pointer_options, {
					buttons: function (event, t) {
						var button = jQuery('<a id="pointer-close" style="margin-left:5px;" class="button-secondary">' + '<?php _e( 'Do not allow tracking', 'lddlite' ) ?>' + '</a>');
						button.bind('click.pointer', function () {
							t.element.pointer('close');
						});
						return button;
					},
					close  : function () {
					}
				});

				setup = function () {
					$('#wpadminbar').pointer(lite_pointer_options).pointer('open');
					jQuery('#pointer-close').after('<a id="pointer-primary" class="button-primary">' + '<?php _e( 'Allow tracking', 'lddlite' ) ?>' + '</a>');
					jQuery('#pointer-primary').click(function () {
						ldl_store_answer( "yes", "<?php echo $nonce ?>" )
					});
					jQuery('#pointer-close').click(function () {
						ldl_store_answer( "no", "<?php echo $nonce ?>" )
					});
				};

				if (lite_pointer_options.position && lite_pointer_options.position.defer_loading)
					$(window).bind('load.wp-pointers', setup);
				else
					$(document).ready(setup);
			})(jQuery);
			//]]>
		</script>
	<?php
	}


} /* End of class */
