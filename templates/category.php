<?php define( 'WP_USE_THEMES', false ); get_header(); ?>

    <section id="primary" class="page-content directory-lite">
        <div id="content" role="main">

            <?php echo ldl_get_header(); ?>

            <div class="col-md-12">
                <div class="list-group">
                    <?php echo ldl_get_categories( get_queried_object()->term_id ); ?>
                </div>
            </div>

            <?php
			$paged 			= ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$posts_per_page = 10;

			if (have_posts()) :
				global $wp_query;
				
				$sort_by 	= ldl()->get_option('directory_listings_sort', 'business_name');
				$sort_order = ldl()->get_option('directory_listings_sort_order','asc');
				
				if(isset($_GET["order_by"]) and !empty($_GET["order_by"])):
					$sort_by 	= $_GET["order_by"];
				endif;
				if(isset($_GET["order"]) and !empty($_GET["order"])):
					$sort_order = $_GET["order"];
				endif;				
				
				 $term_array = $wp_query->get_queried_object();
				 $cat_id = $term_array->term_id;

				if($sort_by == "business_name"):
					query_posts( array (
									'orderby' 		=> 'title',
									'order' 		=> $sort_order,
									'post_type'		=> LDDLITE_POST_TYPE,
									'posts_per_page'=> $posts_per_page,
									'paged' 		=> $paged,
									'tax_query' 	=> array(
														  array(
															  'taxonomy' => LDDLITE_TAX_CAT,
															  'field' 	 => 'id',
															  'terms' 	 => $cat_id
														  )
													   )
										) );
				/* Featured Listings and other listings combination with pagination */
				elseif ($sort_by == "featured"):

					/*$cat_args = array();
					if($cat_id){
						$cat_args = array('tax_query' => array(
								array(
									'taxonomy' => LDDLITE_TAX_CAT,
									'field' 	 => 'id',
									'terms' 	 => $cat_id
								)
						));
					}*/

					 /*$featured = ldl_get_featured_posts($cat_args);
					  while ($featured->have_posts()): $featured->the_post();
						  ldl_get_template_part('listing', 'compact');
					 endwhile;
			   		 wp_reset_postdata();*/

					query_posts( array (
									'tax_query' => array(
										array(
											'taxonomy' => LDDLITE_TAX_CAT,
											'field' 	 => 'id',
											'terms' 	 => $cat_id
											 )
									),
									'orderby' 		=> 'menu_order',
									'order' 		=> $sort_order,
									'posts_per_page'=> $posts_per_page,
									'paged' 		=> $paged,
									'post_type'	=> LDDLITE_POST_TYPE,
								) );

				elseif ($sort_by == "zip"):	
					query_posts( array (
									'meta_key'  	=> '_lddlite_postal_code',
									'order' 		=> $sort_order,
									'post_type'		=> LDDLITE_POST_TYPE,
									'posts_per_page'=> $posts_per_page,
									'paged' 		=> $paged,
									'tax_query' 	=> array(
														  array(
															  'taxonomy' => LDDLITE_TAX_CAT,
															  'field' 	 => 'id',
															  'terms' 	 => $cat_id
														  )
													   )
								) );	
				elseif ($sort_by == "area"):	
					query_posts( array (
									'meta_key'  	=> '_lddlite_country',
									'order' 		=> $sort_order,
									'post_type'		=> LDDLITE_POST_TYPE,
									'posts_per_page'=> $posts_per_page,
									'paged' 		=> $paged,
									'tax_query' 	=> array(
														  array(
															  'taxonomy' => LDDLITE_TAX_CAT,
															  'field' 	 => 'id',
															  'terms' 	 => $cat_id
														  )
													   )
									
								) );
				elseif ($sort_by == "category"):	
					query_posts( array (
									'order' 		=> $sort_order,
									'post_type'		=> LDDLITE_POST_TYPE,
									'posts_per_page'=> $posts_per_page,
									'paged' 		=> $paged,
									'tax_query' 	=> array(
														  array(
															  'taxonomy' => LDDLITE_TAX_CAT,
															  'field' 	 => 'id',
															  'terms' 	 => $cat_id
														  )
													   )
								) );	
				elseif ($sort_by == "random"):	
					query_posts( array (
									'orderby' 		=> 'rand',
									'order' 		=> $sort_order,
									'post_type'		=> LDDLITE_POST_TYPE,
									'posts_per_page'=> $posts_per_page,
									'paged' 		=> $paged,
									'tax_query' 	=> array(
														  array(
															  'taxonomy' => LDDLITE_TAX_CAT,
															  'field' 	 => 'id',
															  'terms' 	 => $cat_id
														  )
													   )
								) );
				endif;

				while (have_posts()) { the_post();

					ldl_get_template_part('listing', 'compact');
                }
				wp_reset_postdata();
                ?>

            <?php else :?>
            <?php endif; ?>
			<?php if (function_exists("ldd_pagination")) { ldd_pagination($wp_query->max_num_pages); } ?>
        </div>
    </section>
<?php  get_footer();  ?>