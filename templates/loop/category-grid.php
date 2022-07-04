    <?php 
    /*
* File version: 2
*/


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

$cols = "col-md-4";
if(ldl()->get_option( 'directory_col_row' )=="2"){
    $cols = "col-md-6";
}
if(ldl()->get_option( 'directory_col_row' )=="3"){
    $cols = "col-md-4";
}
if(ldl()->get_option( 'directory_col_row' )=="4"){
    $cols = "col-md-3";
}

foreach($mycategory as $s)
{
	
    $cat_img = '';
$theurl = get_term_link($s, 'mycategory');
$img_url = get_term_meta($s->term_id,'avatar',true);
if($img_url){
$cat_img = "<img src='".$img_url."' width='80px'>";
}
$count = get_term_post_count( "listing_category", $s->term_id );

?><div  id="listing-<?php echo $s->term_id ?>" class="type-grid grid-item">
        <div class="thumbnail">
            <?php
            //$thumbnail_src = ldl_get_thumbnail( get_the_ID() );
            if($cat_img) {
                echo $cat_img." <hr /> ";
            }
            ?>
            <div class="caption text-left">
                <h3 class="listing-title grid-title">
                   <a href="<?php echo $theurl; ?>" rel="bookmark"><?php echo $s->name; ?></a>
                   <span class="cat-count"> <?php echo $count; ?></span>
                </h3>
                <div class="listing-meta meta-column">
                    
                    <?php echo $s->description;?>
                </div>
            </div>
        </div>
    </div>
    <?php }
                