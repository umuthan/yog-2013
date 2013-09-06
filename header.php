<!Doctype html>
<html <?php language_attributes(); ?>>
<head>
<title><?php wp_title('|', true, 'right'); ?></title>

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>">
<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />  
<meta property="og:description" content="<?php bloginfo('description'); ?>" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div class="header autoSize">
	<div class="topLine autoSize">
		<div class="stabilWidth overflowHid">
			<div class="right socialIco">
				<a href="#"><img src="<?php bloginfo('template_url'); ?>/themeImage/face.png" alt="" /></a>
				<a href="#"><img src="<?php bloginfo('template_url'); ?>/themeImage/twit.png" alt="" /></a>
				<a href="#"><img src="<?php bloginfo('template_url'); ?>/themeImage/gplus.png" alt="" /></a>
			</div>
		</div>
	</div>
	
	<div class="botLine autoSize">
		<div class="stabilWidth overflowHid">
			<a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('template_url'); ?>/themeImage/logo.png" alt="" class="left headLogo" /></a>
			<img src="<?php bloginfo('template_url'); ?>/themeImage/test.png" alt="" class="right headLogo" />
		</div>
	</div>
	
		<?php wp_nav_menu( 
			array(
				'menu' => 'menu'
			)
		); ?>
</div>