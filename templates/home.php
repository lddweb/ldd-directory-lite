<div class="directory-lite">

    <?php 
		echo ldl_get_header(); 
		$pass_attr = array();
		if(isset($atts) and !empty($atts)):
			$pass_attr = array_filter( $atts );
		endif;
	?>

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="list-group">
                    <?php echo ldl_get_parent_categories($pass_attr); ?>
                </div>
            </div>
            <?php if (ldl()->get_option('appearance_display_featured')):
                $featured = ldl_get_featured_posts(array(),$pass_attr); if ($featured->have_posts()):
                ?>
            <div class="col-md-12">

                <h2><?php _e('Featured Listings', 'ldd-directory-lite'); ?></h2>

                <?php while ($featured->have_posts()): $featured->the_post(); ?>
                    <?php ldl_get_template_part('listing', 'compact'); ?>
                <?php endwhile; ?>

            </div>
            <?php endif; endif; ?>
        </div>

    </div>

</div>
