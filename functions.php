<?php

//start top menu

add_action('init', 'theme_menus');

function theme_menus(){
	register_nav_menus(
		array(
			
			'menu' => __( 'Top menu', 'yog'),
			
	));
}

//description,charset,title

function yog_wp_title($title, $sep)
{
    global $paged, $page;
    if (is_feed()) {
        return $title;
    }
    // Add the site name.
    $title .= get_bloginfo('name');
    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page())) {
        $title = "$title $sep $site_description";
    }
    // Add a page number if necessary.
    if ($paged >= 2 || $page >= 2) {
        $title = "$title $sep " . sprintf(__('Page %s', 'yog'), max($paged, $page));
    }
    return $title;
}

add_filter('wp_title', 'yog_wp_title', 10, 2);

//body class

function yog_body_classes($classes)
{
    if (!is_multi_author()) {
        $classes[] = 'single-author';
    }
    return $classes;
}

add_filter('body_class', 'yog_body_classes');

//post image

add_theme_support ('post-thumbnails');
set_post_thumbnail_size (150, 150, true );
add_image_size ('single-post-thumbnail', 150, 150);

	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}
	}
	
//read more

function yog_get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content( $content );

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

//post meta

if (!function_exists('yog_posted_on')) :
    function yog_posted_on(){
        printf(__('Tarih : <span class="lightData"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time> |</span> Yazar : <a class="post-meta-link" href="%5$s" title="%6$s" rel="author">%7$s</a>','yog'),
            esc_url(get_permalink()),
            esc_attr(get_the_time()),
            esc_attr(get_the_date('c')),
            esc_html(get_the_date()),
            esc_url(get_author_posts_url(get_the_author_meta('ID'))),
            esc_attr(sprintf(__('View all posts by %s', 'yog'), get_the_author())),
            esc_html(get_the_author())
        );
    }
endif;

function yog_widgets_init() {

    register_sidebar(
        array(
            'name'          => __('Page Sidebar', 'yog'),
            'id'            => 'sidebar-page',
			'description'   => __('Sayfa ici bilesen ekleyiniz', 'yog'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => "</div>",
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        )
    );

    register_sidebar(
        array(
            'name'          => __('Posts Sidebar', 'yog'),
            'id'            => 'sidebar-posts',
			'description'   => __('Post ici bilesen ekleyiniz', 'yog'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => "</div>",
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        )
    );

    register_sidebar(
        array(
            'name'          => __('Footer Content', 'yog'),
            'id'            => 'footer-content',
            'description'   => __('Alt alan icin sadece " 3 " bilesen ekleyiniz', 'yog'),
            'before_widget' => '<div id="%1$s" class="widget left %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4>',
            'after_title'   => '</h4>'
        )
    );

}
add_action('init', 'yog_widgets_init');

?>