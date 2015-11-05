<?php
class fep_widget extends WP_Widget {

	function fep_widget () {
		 $widget_ops = array( 'classname' => 'fep_login', 'description' => __('Display login widget on sidebar ', FEP) );
                $this->WP_Widget('fep_widget', 'Frontend Login', $widget_ops );        
	}


	public function form( $instance ) {

		if ( isset( $instance[ 'title' ]) && isset ($instance[ 'register' ]) && ($instance[ 'hide_widget']) ) {
			$title = $instance[ 'title' ];
			$register = $instance[ 'register' ];
			$hide_widget = $instance[ 'hide_widget' ];
		} else {
			$title = __( '', 'fep_widget_title' );
			$register = __( '', 'fep_widget_register' );
			$hide_widget = __( '', 'fep_widget_hide_widget' );
			} 
?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', FEP); ?></label>
    <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
</p>

<p>
    <input class="checkbox" type="checkbox" <?php checked( $instance['register'], "on" ); ?> id="<?php echo $this->get_field_id( 'register' ); ?>" name="<?php echo $this->get_field_name( 'register' ); ?>" />
    <label for="<?php echo $this->get_field_id( 'register' ); ?>"><?php _e('Show Register Link', FEP); ?></label>
</p>

<p>
    <input class="checkbox" type="checkbox" <?php checked( $instance['hide_widget'], "on" ); ?> id="<?php echo $this->get_field_id( 'hide_widget' ); ?>" name="<?php echo $this->get_field_name( 'hide_widget' ); ?>" />
    <label for="<?php echo $this->get_field_id( 'hide_widget' ); ?>"><?php _e('Hide widget when user logged in', FEP); ?></label>
</p>

<?php

}

// update the new values in database

	function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		$instance['register'] = ( ! empty( $new_instance['register'] ) ) ? strip_tags( $new_instance['register'] ) : '';
	
		$instance['hide_widget'] = ( ! empty( $new_instance['hide_widget'] ) ) ? strip_tags( $new_instance['hide_widget'] ) : '';

		return $instance;

	}

//Display the stored widget information in webpage.

	function widget($args, $instance) {
		
		extract($args);
		

	
		if (!is_user_logged_in() ) { 
			echo $before_widget; //Widget starts to print information

			$title = apply_filters( 'widget_title', $instance['title'] );
			$register = $instance['register'];
		
		
			if ( !empty( $title ) ) { 
				echo $before_title . $title . $after_title; 
			};
	
			include_once( realpath(dirname(__FILE__)).'/login_form.php');
		
			echo $after_widget; //Widget ends printing information
		}else{

			$hide_widget = $instance['hide_widget'];

			if($hide_widget != "on"){
				echo $before_widget; //Widget starts to print information

				$title = apply_filters( 'widget_title', $instance['title'] );

				if ( !empty( $title ) ) { 
					echo $before_title . $title . $after_title; 
				};
	
				include_once( realpath(dirname(__FILE__)).'/loggedin_form.php');
		
				echo $after_widget; //Widget ends printing information
			}
		}
	} 

}
?>