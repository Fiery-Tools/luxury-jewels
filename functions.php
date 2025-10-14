<?php

/**
 * Luxury Jewels functions and definitions
 * @package luxury-jewels
 */

/**
 * Load theme includes.
 */
require get_template_directory() . '/includes/customizer.php';
require get_template_directory() . '/includes/attributes.php';
require get_template_directory() . '/includes/enqueue.php';
require get_template_directory() . '/includes/woocommerce-hooks.php';

/**
 * Main Theme Setup. Runs on the 'after_setup_theme' hook.
 */
function luxury_jewels_setup()
{
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
	add_theme_support('woocommerce');

	register_nav_menus([
		'primary' => esc_html__('Primary Menu', 'luxury-jewels'),
	]);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 100,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_theme_support('automatic-feed-links');
	add_theme_support('wp-block-styles');
	add_theme_support('responsive-embeds');
	add_theme_support('align-wide');
	add_editor_style('style-editor.css');

	// Theme check recommendations
	add_theme_support('custom-background');
	add_theme_support('custom-header');
}
add_action('after_setup_theme', 'luxury_jewels_setup');

/**
 * Register widget area. Runs on the 'widgets_init' hook.
 * This is the correct way to register sidebars.
 */
function luxury_jewels_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Shop Sidebar', 'luxury-jewels'),
			'id'            => 'shop-sidebar',
			'description'   => esc_html__('Add widgets here to appear in your WooCommerce shop sidebar.', 'luxury-jewels'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// todo: footer areas
	// register_sidebar(array('name' => esc_html__('Footer Column 1', 'luxury-jewels'), 'id' => 'footer-1'));
	// register_sidebar(array('name' => esc_html__('Footer Column 2', 'luxury-jewels'), 'id' => 'footer-2'));
	// register_sidebar(array('name' => esc_html__('Footer Column 3', 'luxury-jewels'), 'id' => 'footer-3'));
	// register_sidebar(array('name' => esc_html__('Footer Column 4', 'luxury-jewels'), 'id' => 'footer-4'));
}
add_action('widgets_init', 'luxury_jewels_widgets_init');

function luxury_jewels_get_header_icon($type)
{
	$icons = array(
		'home' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
		'cart' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
		'checkout' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>',
		'account' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
	);

	return isset($icons[$type]) ? $icons[$type] : '';
}

/**
 * Custom navigation that merges theme menu with user's custom links.
 */
function luxury_jewels_custom_nav_menu()
{
	// Get user's custom menu items if they exist
	$user_menu_items = array();
	if (has_nav_menu('primary')) {
		$menu_locations = get_nav_menu_locations();
		$menu_id = $menu_locations['primary'];
		$menu_items = wp_get_nav_menu_items($menu_id);

		if ($menu_items) {
			foreach ($menu_items as $item) {
				$user_menu_items[] = array(
					'title' => $item->title,
					'url'   => $item->url,
				);
			}
		}
	}

	// Define our core menu items
	$core_items = array();
	$core_items[] = array(
		'title' => '<span class="cart-icon-wrapper">' . luxury_jewels_get_header_icon('home') . ' <span class="text hide-on-mobile">Home</span></span>',
		'url'   => home_url('/'),
	);

	// let's get the top 3 categories in shop
	if (class_exists('WooCommerce')) {
		// $category_args = array(
		// 	'taxonomy'     => 'product_cat',
		// 	'number'       => 3,
		// 	'parent'       => 0, // Only top-level categories
		// 	'hide_empty'   => true,
		// 	'orderby'      => 'count',
		// 	'order'        => 'DESC'
		// );
		// $product_categories = get_terms($category_args);

		// if (!is_wp_error($product_categories) && !empty($product_categories)) {
		// 	foreach ($product_categories as $category) {
		// 		$core_items[] = array(
		// 			'title' => $category->name,
		// 			'url'   => get_term_link($category),
		// 			'class' => 'hide-on-mobile',
		// 		);
		// 	}
		// }

		$cart_count = WC()->cart->get_cart_contents_count();
		$badge_html = '';
		if ($cart_count > 0) {
			$badge_html = ' <span class="cart-count-badge">' . $cart_count . '</span>';
		}

		$core_items[] = array(
			'title' => '<span class="cart-icon-wrapper">' . luxury_jewels_get_header_icon('cart') . ' <span class="text hide-on-mobile">Cart</span></span>' . $badge_html,
			'url'   => wc_get_cart_url(),
		);

		$core_items[] = array(
			'title' => '<span class="cart-icon-wrapper">' . luxury_jewels_get_header_icon('checkout') . '<span class="text hide-on-mobile">Checkout</span></span>',
			'url'   => wc_get_checkout_url(),
		);


		$core_items[] = array(
			'title' => '<span class="cart-icon-wrapper">' . luxury_jewels_get_header_icon('account') . '<span class="text hide-on-mobile">Account</span></span>',
			'url'   => wc_get_page_permalink('myaccount'),
		);
	}

	// Get core URLs for comparison
	$core_urls = array_map(function ($item) {
		return trailingslashit($item['url']);
	}, $core_items);

	// Filter user items to only include those not in our core menu
	$extra_items = array();
	foreach ($user_menu_items as $user_item) {
		$user_url = trailingslashit($user_item['url']);
		if (!in_array($user_url, $core_urls)) {
			$extra_items[] = $user_item;
		}
	}

	// Combine: core items first, then extra user items
	$all_items = array_merge($core_items, $extra_items);

	// Output the menu
	echo '<ul id="primary-menu" class="menu">';
	foreach ($all_items as $item) {
		$li_class = isset($item['class']) ? ' class="' . esc_attr($item['class']) . '"' : '';
		echo '<li' . $li_class . '><a href="' . esc_url($item['url']) . '">' . $item['title'] . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Add custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function luxury_jewels_body_classes($classes)
{
	// Add a class if the sticky header is enabled
	if (get_theme_mod('luxury_jewels_sticky_header', 0)) {
		$classes[] = 'sticky-header-enabled';
	}

	// add sidebar sometimes
	if (is_shop() || is_product_category() || is_product_tag()) {
		$sidebar_layout = get_theme_mod('luxury_jewels_shop_sidebar_layout', 'no-sidebar');
		if (!empty($sidebar_layout)) {
			$classes[] = 'shop-sidebar-' . $sidebar_layout;
		}
	}

	return $classes;
}
add_filter('body_class', 'luxury_jewels_body_classes');

/**
 * Changes the number of product columns on the shop page.
 */
function luxury_jewels_set_shop_columns($columns)
{
	return get_theme_mod('luxury_jewels_shop_columns', 3);
}
add_filter('loop_shop_columns', 'luxury_jewels_set_shop_columns');

/**
 * Overrides the default "Sale!" text with a custom value.
 */
function luxury_jewels_custom_sale_badge($html)
{
	$custom_text = get_theme_mod('luxury_jewels_sale_badge_text', __('Sale!', 'luxury-jewels'));
	// Return the custom text wrapped in the default WooCommerce sale badge markup.
	return '<span class="onsale">' . esc_html($custom_text) . '</span>';
}
add_filter('woocommerce_sale_flash', 'luxury_jewels_custom_sale_badge', 10, 1);

/**
 * Add body classes for footer widget column layout.
 */
function luxury_jewels_footer_body_classes($classes)
{
	$columns = get_theme_mod('luxury_jewels_footer_widget_columns', 4);
	$classes[] = 'footer-columns-' . $columns;
	return $classes;
}
add_filter('body_class', 'luxury_jewels_footer_body_classes');

// WooCommerce Hooks are now in /includes/woocommerce-hooks.php

function luxury_jewels_divider()
{
	echo '<hr class="divider" />';
}






/**
 * Register Block Patterns for the theme.
 */
function luxury_jewels_register_block_patterns()
{
	// First, register a custom pattern category for our theme
	if (function_exists('register_block_pattern_category')) {
		register_block_pattern_category(
			'luxury-jewels',
			array('label' => __('Luxury Jewels', 'luxury-jewels'))
		);
	}

	// Now, register the actual pattern
	if (function_exists('register_block_pattern')) {
		register_block_pattern(
			'luxury-jewels/two-column-text', // Unique pattern name
			array(
				'title'       => __('Two Column Text', 'luxury-jewels'),
				'description' => __('A simple two-column layout with headings and paragraphs.', 'luxury-jewels'),
				'categories'  => array('luxury-jewels'),
				'content'     => '<!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading --><h2>Our Philosophy</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed-dapibus, urna ut pulvinar sollicitudin, sem-massa blandit.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading --><h2>Our Craft</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Aenean eu-leo quam. Pellentesque-ornare sem-lacinia quam venenatis vestibulum. Donec id elit non mi porta.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns -->',
			)
		);
	}
}

add_action('init', 'luxury_jewels_register_block_patterns');




// image sizes
/**
 * Provides an accurate 'sizes' attribute for WooCommerce product images
 * based on the Luxury Jewels theme's responsive grid and sidebar layout.
 *
 * @param string $sizes The original 'sizes' attribute.
 * @return string The modified and accurate 'sizes' attribute.
 */
function luxury_jewels_product_image_sizes($sizes)
{
	if (is_shop() || is_product_category() || is_product_tag()) {
		$body_classes = get_body_class();
		if (in_array('shop-sidebar-left-sidebar', $body_classes) || in_array('shop-sidebar-right-sidebar', $body_classes)) {
			return '(max-width: 580px) 92vw, (max-width: 800px) 45vw, (max-width: 1024px) 29vw, 23vw';
		} else {
			return '(max-width: 580px) 92vw, (max-width: 800px) 45vw, (max-width: 1024px) 29vw, 18vw';
		}
	}

	return $sizes;
}

add_filter('wp_calculate_image_sizes', 'luxury_jewels_product_image_sizes', 20);

/**
 * Preloads the LCP (Largest Contentful Paint) image on product category pages
 * to significantly improve the LCP metric.
 */
function luxury_jewels_preload_lcp_image()
{
	// Only run this code on product category, tag, or shop pages.
	if (is_shop() || is_product_category() || is_product_tag()) {
		global $wp_query;

		// Check if there are any products to display.
		if (isset($wp_query->posts) && ! empty($wp_query->posts)) {
			// Get the very first product in the loop.
			$first_product_id = $wp_query->posts[0]->ID;

			// Get the URL of the product's thumbnail image.
			$lcp_image_url = get_the_post_thumbnail_url($first_product_id, 'woocommerce_thumbnail');

			// If we found a valid image URL, output the preload link tag.
			if ($lcp_image_url) {
				echo '<link rel="preload" as="image" href="' . esc_url($lcp_image_url) . '">';
			}
		}
	}
}
add_action('wp_head', 'luxury_jewels_preload_lcp_image', 1);
