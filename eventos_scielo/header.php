<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>

<!--Script função toggle-->
	<script type="text/javascript">
		<!--

		function toggle(obj) {

			var el = document.getElementById(obj);

			if ( el.style.display != "none" ) {

				el.style.display = 'none';

			}

			else {

				el.style.display = '';

			}

		}

		-->

	</script>
</head>
<body <?php body_class(); ?>>
<div id="wrapper" class="hfeed">

</div>
	<div id="page" class="hfeed">
		<span class="scielo_eventos_header"><img src="<?php bloginfo('template_directory'); ?>/images/headers/scielo_eventos_header.png"></span>
		<header id="branding" role="banner">
				
				<hgroup>
					<h1 id="site-title"><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span></h1>
					<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
				</hgroup>
				<?php
					// Check to see if the header image has been removed
					$header_image = get_header_image();
					if ( ! empty( $header_image ) ) :
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php
						// The header image
						// Check if this is a post or page, if it has a thumbnail, and if it's a big one
						if ( is_singular() &&
								has_post_thumbnail( $post->ID ) &&
								( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( HEADER_IMAGE_WIDTH, HEADER_IMAGE_WIDTH ) ) ) &&
								$image[1] >= HEADER_IMAGE_WIDTH ) :
							// Houston, we have a new header image!
							echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
						else : ?>
						<!--SciELO logos dos partners -->
						<div style="position: relative;"><img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
						<a href="http://www.fapesp.br/" target="_blank" style="width: 110px; height: 42px; border: 0; position: absolute; top: 100px; left: 630px;"></a>
						<a href="http://en.unesco.org/" target="_blank" style="width: 50px; height: 42px; border: 0; position: absolute; top: 100px; left: 745px;"></a>
						<a href="http://portal.fiocruz.br/" target="_blank" style="width: 110px; height: 42px; border: 0; position: absolute; top: 150px; left: 885px;"></a>
						<a href="http://www.saude.gov.br/" target="_blank" style="width: 160px; height: 42px; border: 0; position: absolute; top: 150px; left: 720px;"></a>
						<a href="http://www.ibict.br/" target="_blank" style="width: 30px; height: 42px; border: 0; position: absolute; top: 150px; left: 640px;"></a>
						<a href="http://fapunifesp.edu.br/" target="_blank" style="width: 35px; height: 42px; border: 0; position: absolute; top: 150px; left: 680px;"></a>
						<a href="http://www.abecbrasil.org.br/" target="_blank" style="width: 100px; height: 42px; border: 0; position: absolute; top: 100px; left: 890px;"></a>
						<a href="http://www.opensocietyfoundations.org/" target="_blank" style="width: 100px; height: 42px; border: 0; position: absolute; top: 100px; left: 790px;"></a>
						<!--SciELO Language bar -->
    <div id="language_bar">
        <?php if (function_exists('mlf_links_to_languages')) mlf_links_to_languages(); ?>
    </div>
						</div>
					<?php endif; // end check for featured image or standard header ?>
				</a>
				<?php endif; // end check for removed header image ?>
				
					<nav id="access" role="navigation">
						<h3 class="assistive-text"><?php _e( 'Main menu', 'twentyeleven' ); ?></h3>
						<?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff. */ ?>
						<div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to primary content', 'twentyeleven' ); ?>"><?php _e( 'Skip to primary content', 'twentyeleven' ); ?></a></div>
						<div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e( 'Skip to secondary content', 'twentyeleven' ); ?>"><?php _e( 'Skip to secondary content', 'twentyeleven' ); ?></a></div>
						<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assiged to the primary position is the one used. If none is assigned, the menu with the lowest ID is used. */ ?>
						<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
					</nav><!-- #access -->
					<div style="float:right; padding-right:10px"><a href="http://thomsonreuters.com/" target="_blank"><img src="http://www.scielo15.org/wp-content/uploads/2013/08/thomson_reuters_logo.png"/ style="margin-right:10px"></a><a href="http://www.caboverde.com.br/" target="_blank"><img src="http://www.scielo15.org/wp-content/uploads/2013/09/caboverde.jpg"/></a><a href="http://www.charlesworth-group.com/" target="_blank"><img src="http://www.scielo15.org/wp-content/uploads/2013/09/charlesworth.jpg"/></a><a href="http://www.editage.com.br/" target="_blank"><img src="http://www.scielo15.org/wp-content/uploads/2013/10/editage_logo.png"/></a></div>
		</header><!-- #branding -->
		
		<div id="main">
				