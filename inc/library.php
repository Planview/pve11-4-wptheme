<?php
/**
 * Functions to manipulate library-specific stuff
 */

/**
 * Filter the permalinks for resources
 *
 * @param   string  $permalink  The current permalink
 * @param   WP_Post $post       The post we're looking at
 * @return  string  The filtered permalink
 */
function pve_113_resource_post_link( $permalink, $post ) {
    if ( 'library' !== $post->post_type )
        return $permalink;

    $type = get_field( 'pv_event_resource_doc_type', $post->ID );

    switch ( $type ) {
        case 'pdf':
            $file = get_field( 'pv_event_resource_file', $post->ID );
            return $file['url'];
        case 'slideshare':
	case 'video':
	    return get_field( 'pv_event_resource_video_code', $post->ID );
	case 'link':
		return trim( get_field( 'pv_event_resource_url', $post->ID ) );
    }
    return $permalink;
}
add_filter( 'post_type_link', 'pve_113_resource_post_link', 10, 2 );


/**
 * Return a Font-awesome class based on resource type
 */
function pve_113_icon_class( $type ) {
    switch ($type) {
        case 'pdf':
            return 'fa-file-pdf-o';
        case 'video':
            return 'fa-film';
        case 'slideshare':
            return 'fa-slideshare';
        default:
            return 'fa-external-link';
    }
}


/**
 * Filter the Query so that we get all the resources back
 */
function pve_113_library_pre_get_posts( $query ) {
    if ( is_admin() || ! $query->is_main_query() || ! is_post_type_archive( 'library' ) )
        return;

    $query->set('posts_per_page', -1);
    $query->set('orderby', 'date');
    $query->set('order', 'DESC');
}
add_action('pre_get_posts', 'pve_113_library_pre_get_posts');


/**
 * A function to compare values for uasort
 */
function compare_multi($a, $b) {
    // sort by parent taxonomy sort order
    //$retval = strnatcmp($a->parent_name, $b->parent_name);
//    $retval = $a->parent_sort_order - $b->parent_sort_order;
    // if parent order is same, sort by release sort order
//    if ($retval == 0) {
        $retval = $a->sort_order - $b->sort_order;
//    }
    return $retval;
}


/**
 * A function to re-sort the library
 */
function pve_113_library_sort() {
    $sorted_list = array();

    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post();

            $release = get_field( 'pv_event_resource_release' );

            if ( ! $release ) continue;

            $release = reset($release);

            $release->sort_order = get_field( 'pve_113_library_sort_order', "{$release->taxonomy}_{$release->term_id}" );

            if ( isset( $release->parent) && $release->parent != 0 ) {
                $parent_term = get_term($release->parent, 'release');
                $release->parent_name = $parent_term->name;
                $release->parent_sort_order = get_field( 'pve_113_library_sort_order', "{$parent_term->taxonomy}_{$parent_term->term_id}" );
            } else {
                $parent_term = '';
                $release->parent_name = $release->name;
                $release->parent_sort_order = $release->sort_order;
            }

                if ( ! isset( $sorted_list[$release->name] ) ) {
                    $sorted_list[$release->name] = array();
                    $sorted_list[$release->name]['__object'] = $release;
                }

            if ( get_field( 'pv_event_resource_featured' ) &&
                    ! isset( $sorted_list[$release->name]['__featured'] ) ) {
                $sorted_list[$release->name]['__featured'] = $GLOBALS['post'];
            } else {
                $sorted_list[$release->name][] = $GLOBALS['post'];
            }

        }
    }

    uasort( $sorted_list, function ($a, $b) {
        $a_val = get_field( 'pve_113_library_sort_order', "{$a['__object']->taxonomy}_{$a['__object']->term_id}" );
        $b_val = get_field( 'pve_113_library_sort_order', "{$b['__object']->taxonomy}_{$b['__object']->term_id}" );
        return $a_val - $b_val;
    } );

//    uasort( $sorted_list, 'compare_multi' );

    echo "<pre>";
    print_r($sorted_list);
    echo "</pre>";
    die();

    return $sorted_list;
}


/**
 * A function to compare values for uasort
 */
function compare_multi_by_product($a, $b) {
    // sort by parent taxonomy sort order
    //$retval = strnatcmp($a->parent_name, $b->parent_name);
    $retval = $a['__object']->parent_sort_order - $b['__object']->parent_sort_order;
    // if parent order is same, sort by release sort order
    if ($retval == 0) {
        $retval = $a['__object']->sort_order - $b['__object']->sort_order;
    }
    return $retval;
}

function pve_113_library_sort_by_product() {
    $sorted_list = array();

    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post();

            $release = get_field( 'pv_event_resource_release' );

            if ( ! $release ) continue;

            $release = reset($release);

            $release->sort_order = get_field( 'pve_113_library_sort_order', "{$release->taxonomy}_{$release->term_id}" );

            if ( isset( $release->parent) && $release->parent != 0 ) {
                $parent_term = get_term($release->parent, 'release');
                $release->parent_name = $parent_term->name;
                $release->parent_sort_order = get_field( 'pve_113_library_sort_order', "{$parent_term->taxonomy}_{$parent_term->term_id}" );
            } else {
                $parent_term = '';
                $release->parent_name = '';
                $release->parent_sort_order = $release->sort_order;
            }

            if ( ! isset( $sorted_list[$release->name] ) ) {
                $sorted_list[$release->name] = array();
                $sorted_list[$release->name]['__object'] = $release;
            }

            if ( get_field( 'pv_event_resource_featured' ) &&
                    ! isset( $sorted_list[$release->name]['__featured'] ) ) {
                $sorted_list[$release->name]['__featured'] = $GLOBALS['post'];
            } else {
                $sorted_list[$release->name][] = $GLOBALS['post'];
            }

        }
    }
/*
    uasort( $sorted_list, function ($a, $b) {
        $a_val = get_field( 'pve_113_library_sort_order', "{$a['__object']->taxonomy}_{$a['__object']->term_id}" );
        $b_val = get_field( 'pve_113_library_sort_order', "{$b['__object']->taxonomy}_{$b['__object']->term_id}" );
        return $a_val - $b_val;
    } );
*/
    uasort( $sorted_list, 'compare_multi_by_product' );
/*
    echo "<pre>";
    print_r($sorted_list);
    echo "</pre>";
    die();
*/
    return $sorted_list;
}

/**
 * Return `target="_blank"` if it should open in a new window
 */
function pve_113_resource_target($type) {
    switch ($type) {
        case 'pdf':
        case 'slideshare':
        case 'link':
            return ' target="_blank"';
        case 'video':
            return ' class="pop-up-video"';
        default:
            return '';
    }
}

function pve_113_resource_list( $resources, $echo = true ) {
    global $post;
    if ( empty($resources) ) return false;

    $list = '<ul class="fa-ul">';

    foreach ( $resources as $post ) {
        setup_postdata( $post );

        $type = get_field('pv_event_resource_doc_type');

        $list .= sprintf(
            '<li>' .
                '<a href="%s" title="%s"%s>' .
                    '<span class="fa fa-li %s"></span> %s' .
                '</a>' .
            '</li>',
            get_permalink(),
            esc_attr( get_the_title() ),
            pve_113_resource_target( $type ),
            pve_113_icon_class( $type ),
            get_the_title()
        );
    }

    $list .= '</ul>';

    if ($echo) {
        echo $list;
        return true;
    } else {
        return $list;
    }
    wp_reset_postdata();
}
