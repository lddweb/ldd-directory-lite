<?php

/**
 *
 */

function lddlite_display_view_home( $term = false )
{
    global $post;

    $categories = wp_list_categories( array(
        'echo'          => 0,
        'hide_empty'    => 0,
        'title_li'      => '',
        'taxonomy'      => LDDLITE_TAX_CAT,
        'pad_counts'    => 1,
    ) );

    $template_vars = array(
        'url'           => get_permalink( $post->ID ),
        'categories'    => $categories,
        'allcats'       => lddlite_get_all_categories(),
    );

    return lddlite_parse_template( 'display/home', $template_vars );
}


function lddlite_get_all_categories() {

    $exclude_cat            = array();
    $show_parent_count      = 1;
    $show_child_count       = 1;
    $hide_empty             = 0;
    $desc_for_parent_title  = 'desc_for_parent_title';
    $desc_for_child_title   = 'desc_for_child_title';
    $child_hierarchical     = 1;
    $column_count           = 7;
    $sort_by                = 'name';
    $sort_direction         = 1;
    $no_child_alert         = 0;
    $show_child             = 1;
    $maximum_child          = 0;

    global $wpdb;
    $cal_tree = array();
    if (!$column_count) $column_count = 1;

    global $rssfeeds;
    $feed = '';
    if ($rssfeeds) {
        $feed = 'RSS';
        $show_parent_count = 0;
        $show_child_count = 0;
    }

    if ($sort_by == 0) {
        $order_by = $orderby = 'name';
    }
    elseif ($sort_by == 1) {
        $order_by = 'term_order'; $orderby = 'term_group';
    }


    $parent_cats = $wpdb->get_results("SELECT *
	FROM " . $wpdb->term_taxonomy . " term_taxonomy
	LEFT JOIN " . $wpdb->terms . " terms
	ON terms.term_id = term_taxonomy.term_id
	WHERE term_taxonomy.taxonomy = '" . LDDLITE_TAX_CAT . "' AND term_taxonomy.parent = 0 " .
        ( count($exclude_cat) ? ' AND terms.term_id NOT IN (' . implode(',', $exclude_cat) . ') ' : '' )
        . " ORDER BY terms." . $order_by);

    foreach ($parent_cats as $parent) {

        $summ = "SELECT SUM(count) FROM " . $wpdb->term_taxonomy . " WHERE taxonomy = '" . LDDLITE_TAX_CAT . "' AND parent = " . $parent->term_id;

        $aQueryResult_ChildSum = $wpdb->get_results( $summ, ARRAY_N );
        $child_summ = isset( $aQueryResult_ChildSum[0][0] )? $aQueryResult_ChildSum[0][0] : 0;

        $catid = $wpdb->get_var("SELECT term_ID FROM " . $wpdb->term_taxonomy . " WHERE taxonomy = '" . LDDLITE_TAX_CAT . "' AND parent = " . $parent->term_id);

        $sub_child_summ = (int)$catid ? $wpdb->get_var("SELECT SUM(count) FROM " . $wpdb->term_taxonomy . " WHERE taxonomy = '" . LDDLITE_TAX_CAT . "' AND parent = " . $catid) : 0;

        $cat_name = $parent->name;

        $descr = sprintf(__("View all posts filed under %s"), $cat_name);

        if ($desc_for_parent_title == 1) {
            if (empty($parent->description)) {
                $descr = $descr;
            } else {
                $descr = $parent->description;
            }
        }

        $child_summ += $parent->count;
        $child_summ += $sub_child_summ;

        if ($show_parent_count == 1) {
            $parent_count = ' (' . $child_summ . ')';
        } else {
            $parent_count = '';
        }

        $cal_tree[] = array(
            'cat' => array(
                'href'  => get_category_link($parent->term_id),
                'title' => $descr,
                'name'  => $parent->name,
                'count' => $parent_count
            ),
            'cats'=> wp_list_categories( 'taxonomy=' . LDDLITE_TAX_CAT . ( count($exclude_cat) ? '&exclude=' . implode(',', $exclude_cat) : '' ) . '&orderby=' . $orderby . '&show_count=' . $show_child_count . '&hide_empty=' . $hide_empty . '&use_desc_for_title=' . $desc_for_child_title . '&child_of=' . $parent->term_id . '&title_li=&hierarchical=' . $child_hierarchical . '&echo=0&feed=' . $feed)
        );

    }

    $_tree = array();
    $count = count($cal_tree);
    if ($sort_direction) {
        $line_count = ceil( $count / $column_count );
        $limit      = $count - $line_count * $column_count % $count;
        for ($i = 0; $i < $count; $i++) {
            $index = floor($i / $line_count) + ($limit && $i > $limit ? 1 : 0);
            if (!isset($_tree[$index])) { $_tree[$index] = array(); }
            $_tree[$index][] = &$cal_tree[$i];
        }
    }
    else {
        for ($i = 0; $i < $count; $i++) {
            $index = $i % $column_count;
            if (!isset($_tree[$index])) { $_tree[$index] = array(); }
            $_tree[$index][] = &$cal_tree[$i];
        }
    }


    if (count($_tree)) {

        $write = '
<div id="categories">';

        for ($j = 0, $count = count($_tree); $j < $count; $j++) {

            $write .= '
		<ul class="column">';

            for ($i = 0, $icount = count($_tree[$j]); $i < $icount; $i++) {

                $catcount = $i + 11;
                if ($j == 1) $catcount = $i + 21;
                if ($j == 2) $catcount = $i + 31;
                if ($j == 3) $catcount = $i + 41;
                if ($j == 4) $catcount = $i + 51;

                if ($rssfeeds) {

                    $write .= '

			<li id="cat-'. $catcount .'"><div><a href="' . esc_html($_tree[$j][$i]['cat']['href']) . '" title="' . esc_html($_tree[$j][$i]['cat']['title']) . '">' . esc_html($_tree[$j][$i]['cat']['name']) . '</a> (<a href="' . esc_html($_tree[$j][$i]['cat']['href']) . '/feed/" title="' . esc_html($_tree[$j][$i]['cat']['title']) . '">RSS</a>)</div>';

                } else {

                    $write .= '

			<li id="cat-'. $catcount .'"><div><a href="' . esc_html($_tree[$j][$i]['cat']['href']) . '" title="' . esc_html($_tree[$j][$i]['cat']['title']) . '">' . esc_html($_tree[$j][$i]['cat']['name']) . '</a>' . $_tree[$j][$i]['cat']['count'] . '</div>';

                }


                $nocats = '<li>' . __("No categories") . '</li>';

                if ($no_child_alert == 1) $nocats = '';

                if ($_tree[$j][$i]['cats'] != $nocats && $show_child == 1) {

                    $write .= '
			<ul class="sub-categories">';
                    if ($maximum_child) {
                        for ($s = 0, $strlen = strlen($_tree[$j][$i]['cats']), $counter = $maximum_child+1, $slevel = 0; $s < $strlen; $s++) {
                            if (!$slevel && substr($_tree[$j][$i]['cats'], $s, 3) == '<li' && !(--$counter)) break;
                            else if (substr($_tree[$j][$i]['cats'], $s, 3) == '<ul') $slevel++;
                            else if ($slevel && substr($_tree[$j][$i]['cats'], $s-4, 4) == '/ul>') $slevel--;
                            else if (!$slevel) $write .= substr($_tree[$j][$i]['cats'], $s, 1);
                        }
                        $licount = substr_count($_tree[$j][$i]['cats'], '<li');
                        if ( ($licount > $maximum_child) && ($_tree[$j][$i]['cats'] != '<li>' . __("No categories") . '</li>') ) {
                            $write .= '<li>...</li>';
                        }
                    }
                    else $write .= $_tree[$j][$i]['cats'];

                    $write .= '
			</ul>';

                }
                $write .= '
		</li>';

            }

            $write .= '
	</ul><!-- .column -->' . "\r\n";

        }

        $write .= '
</div><!-- #categories -->' . "\r\n";

            return $write;

    }
}