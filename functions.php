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
            'before_widget' => '<div id="%1$s" class="page-widget overflowHid %2$s">',
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

//comments

if (!function_exists('yog_comment')) :
    function yog_comment($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' : ?>

                <li class="comment media" id="comment-<?php comment_ID(); ?>">
                    <div class="media-body">
                        <p>
                            <?php _e('Pingback:', 'yog'); ?> <?php comment_author_link(); ?>
                        </p>
                    </div><!--/.media-body -->
                <?php
                break;
            default :
                // Proceed with normal comments.
                global $post; ?>

                <li class="comment media" id="li-comment-<?php comment_ID(); ?>">
                        <a href="<?php echo $comment->comment_author_url;?>" class="pull-left">
                            <?php echo get_avatar($comment, 64); ?>
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading comment-author vcard">
                                <?php
                                printf('<cite class="fn">%1$s %2$s</cite>',
                                    get_comment_author_link(),
                                    // If current post author is also comment author, make it known visually.
                                    ($comment->user_id === $post->post_author) ? '<span class="label"> ' . __(
                                        'Post author',
                                        'yog'
                                    ) . '</span> ' : ''); ?>
                            </h4>

                            <?php if ('0' == $comment->comment_approved) : ?>
                                <p class="comment-awaiting-moderation"><?php _e(
                                    'Your comment is awaiting moderation.',
                                    'yog'
                                ); ?></p>
                            <?php endif; ?>

                            <?php comment_text(); ?>
                            <p class="meta">
                                <?php printf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
                                            esc_url(get_comment_link($comment->comment_ID)),
                                            get_comment_time('c'),
                                            sprintf(
                                                __('%1$s at %2$s', 'yog'),
                                                get_comment_date(),
                                                get_comment_time()
                                            )
                                        ); ?>
                            </p>
                            <p class="reply">
                                <?php comment_reply_link( array_merge($args, array(
                                            'reply_text' => __('Reply <span>&darr;</span>', 'yog'),
                                            'depth'      => $depth,
                                            'max_depth'  => $args['max_depth']
                                        )
                                    )); ?>
                            </p>
                        </div>
                        <!--/.media-body -->
                <?php
                break;
        endswitch;
    }
endif;
?>