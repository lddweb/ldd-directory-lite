<?php
/*
 File version: 2
*/
get_header();
?>
<div class=" bootstrap-wrapper ac">
	<?php 
		/**
		 * ldd_before_main_content hook.
		 *
		 * @hooked ldd_output_content_wrapper - 10 (outputs opening divs for the content)
		 */
		do_action( 'ldd_before_main_content' );
		$version = 2;
	?>

	<?php echo ldl_get_header(); ?>
	<?php if(ldl()->get_option( 'view_controls' )=="yes"){
		if($listing_view = ldl()->get_option( 'directory_view_type') =="compact"){
			$compact = "compact";
		}
		if($listing_view = ldl()->get_option( 'directory_view_type') =="grid"){
			$grid = "grid";
		}
		if($listing_view = ldl()->get_option( 'home_page_listing') =="category"){
			$category = "category";
		}
		if($listing_view = ldl()->get_option( 'home_page_listing') =="listing"){
			$listing = "listing";
		}
		
		?>
  <div class="row view_controls">
<div class="col-md-4">&nbsp;</div>

<div class="col-md-4">&nbsp;</div>


<div class="col-md-4 text-right"><a class="<?php echo $grid;?>" href="?ldd_view=grid">Grid</a> | <a class="<?php echo $compact;?>" href="?ldd_view=compact">Compact</a></div>
</div>
<?php } ?>


	<div class="col-md-12 abcd">
		<div class="list-group">
			<?php   echo ldl_get_categories( get_queried_object()->term_id );
		

			
			 ?>
		</div>
	</div>
	<div class="col-md-12" style="margin-top:20px">
	<?php
		$paged          = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		if(ldl()->get_option( 'listings_display_number') > 0){
			
			$posts_per_page  = ldl()->get_option( 'listings_display_number', 10 );
		}
		else {
		$posts_per_page = 100;
		}

		
			global $wp_query;

			$sort_by    = ldl()->get_option( 'directory_listings_sort', 'business_name' );
			$sort_order = ldl()->get_option( 'directory_listings_sort_order', 'asc' );
			$sub_check  = ldl()->get_option( 'subcategory_listings', 0 );
			$subcategory_listings = ($sub_check == 0) ? true : false;

			if ( isset( $_GET["order_by"] ) and ! empty( $_GET["order_by"] ) ):
				$sort_by = sanitize_text_field($_GET["order_by"]);
			endif;
			if ( isset( $_GET["order"] ) and ! empty( $_GET["order"] ) ):
				$sort_order = sanitize_text_field($_GET["order"]);
			endif;


			$term_array = $wp_query->get_queried_object();
			$cat_id     = $term_array->term_id;

			$tax_query  = array(
				array(
					'taxonomy' => LDDLITE_TAX_CAT,
					'field'    => 'id',
					'terms'    => $cat_id,
					'include_children' => $subcategory_listings
				)
			);

			

			if ( $sort_by == "business_name" ):
				$cat_query =new WP_Query( array(
					'orderby'        => 'title',
					'order'          => $sort_order,
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query
				) );
				elseif ( $sort_by == "id" ):
				$cat_query =new WP_Query( array(
					'orderby'        => 'ID',
					'order'          => $sort_order,
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query
				) );
			/* Featured Listings and other listings combination with pagination */
			elseif ( $sort_by == "featured" ):
				$cat_query = new WP_Query( array(
					'tax_query'      => $tax_query,
					'orderby'        => 'menu_order',
					'order'          => $sort_order,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'post_type'      => LDDLITE_POST_TYPE,
				) );
			elseif ( $sort_by == "zip" ):
				$cat_query = new WP_Query( array(
					'meta_key'       => '_lddlite_postal_code',
					'order'          => $sort_order,
					'orderby'        => 'meta_value',
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query
				) );
			
				elseif ( $sort_by == "country" ):
				$cat_query = new WP_Query( array(
					'meta_key'       => '_lddlite_country',
					'order'          => $sort_order,
					'orderby'        => 'meta_value',
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query

				) );
				elseif ( $sort_by == "city" ):
			$cat_query = 	new WP_Query( array(
					'meta_key'       => '_lddlite_city',
					'order'          => $sort_order,
					'orderby'        => 'meta_value',
					'post_type'      => LDDLITE_POST_TYPE,
					'paged'          =>  get_query_var( 'paged' ),
					'posts_per_page' => $posts_per_page,
					
					'tax_query'      => $tax_query

				) );
				elseif ( $sort_by == "state" ):
			$cat_query = 	new WP_Query( array(
					'meta_key'       => '_lddlite_state',
					'order'          => $sort_order,
					'orderby'        => 'meta_value',
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query

				) );
			elseif ( $sort_by == "category" ):
				$cat_query = new WP_Query( array(
					'order'          => $sort_order,
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query
				) );
			elseif ( $sort_by == "random" ):
				$cat_query = new WP_Query( array(
					'orderby'        => 'rand',
					'order'          => $sort_order,
					'post_type'      => LDDLITE_POST_TYPE,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'tax_query'      => $tax_query
				) );
			endif;
			

			if ( $cat_query->have_posts() ) :
			
			
			
			
			
			
			$listing_view = ldl()->get_option( 'directory_view_type', 'compact' );
			if ( $listing_view == "grid" ) {
				echo "<div class='grid js-isotope2 masonry-cols3' >";
				
				//echo '<div class="grid" data-isotope=\'{ "grid-item": ".grid-item", "getSortData": { "name": "lddmas", "category": "[data-category]" }, "masonry": { "columnWidth": 200 } }\'>';
			}
			while ( $cat_query->have_posts() ) {
				$cat_query->the_post();
				ldl_get_template_part( 'loop/listing', $listing_view );
			}
			if ( $listing_view == "grid" ) {
				echo "</div>";
				//wp_enqueue_script( 'isotope-pkgd', LDDLITE_URL . '/public/js/isotope.pkgd.min.js' );
			}
			wp_reset_postdata(); 
			 
			?>
			<div class="clearfix"></div>
		<?php else : ?>
			<?php ldl_get_template_part( 'loop/no-listings-found.php' ); ?>
		<?php endif; ?>

	<?php 
		/**
		 * ldd_after_directory_loop hook.
		 *
		 * @hooked ldd_default_pagination - 10
		 */
		 if(ldl()->get_option( 'listings_display_number') >0){
		  the_posts_pagination( array(
    'mid_size' => 2,
    'prev_text' => __( 'Previous', 'textdomain' ),
    'next_text' => __( 'Next', 'textdomain' ),
) ); 
		 }
		//do_action( 'ldd_after_directory_loop' );
	?>

	<?php
		/**
		 * ldd_after_main_content hook.
		 *
		 * @hooked ldd_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'ldd_after_main_content' );
	?>
	</div>
</div>
<?php get_footer(); ?>