<?php

/**
 * Luxury Jewels functions and definitions
 * @package Luxury_Jewels
 */

/**
 * Load theme customizer options.
 */
require get_template_directory() . '/includes/customizer.php';
require get_template_directory() . '/includes/attributes.php';
require get_template_directory() . '/includes/enqueue.php';
require get_template_directory() . '/includes/woocommerce-hooks.php';

// Main Theme Setup
function luxury_jewels_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('woocommerce');

    register_nav_menus([
        'primary' => esc_html__('Primary Menu', 'luxuryjewels'),
    ]);

    add_theme_support(
        'custom-logo',
        array(
            'height'      => 100, // Optional.
            'width'       => 400, // Optional.
            'flex-height' => true,
            'flex-width'  => true,
        )
    );

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

    register_sidebar(array('name' => esc_html__('Footer Column 1', 'luxury-jewels'), 'id' => 'footer-1'));
    register_sidebar(array('name' => esc_html__('Footer Column 2', 'luxury-jewels'), 'id' => 'footer-2'));
    register_sidebar(array('name' => esc_html__('Footer Column 3', 'luxury-jewels'), 'id' => 'footer-3'));
    register_sidebar(array('name' => esc_html__('Footer Column 4', 'luxury-jewels'), 'id' => 'footer-4'));
}
add_action('after_setup_theme', 'luxury_jewels_setup');



// Custom navigation that merges theme menu with user's custom links
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
        'title' => 'Home',
        'url'   => home_url('/'),
    );

    if (class_exists('WooCommerce')) {
        $core_items[] = array(
            'title' => 'Shop',
            'url'   => wc_get_page_permalink('shop'),
        );

        $cart_count = WC()->cart->get_cart_contents_count();
        $badge_html = '';
        if ($cart_count > 0) {
            $badge_html = ' <span class="cart-count-badge">' . $cart_count . '</span>';
        }

        $core_items[] = array(
            'title' => 'Cart' . $badge_html,
            'url'   => wc_get_cart_url(),
        );

        $core_items[] = array(
            'title' => 'Checkout',
            'url'   => wc_get_checkout_url(),
        );

        $core_items[] = array(
            'title' => 'My Account',
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
        if (! in_array($user_url, $core_urls)) {
            $extra_items[] = $user_item;
        }
    }

    // Combine: core items first, then extra user items
    $all_items = array_merge($core_items, $extra_items);

    // Output the menu
    echo '<ul id="primary-menu" class="menu">';
    foreach ($all_items as $item) {
        echo '<li><a href="' . esc_url($item['url']) . '">' . $item['title'] . '</a></li>';
    }
    echo '</ul>';
}

// Update cart badge via AJAX when items are added to cart
// add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
//     $cart_count = WC()->cart->get_cart_contents_count();

//     ob_start();
//     if ($cart_count > 0) :
// >
//         <span class="cart-count-badge"><?php echo $cart_count; ></span>
// <php
//     endif;
//     $fragments['.cart-count-badge'] = ob_get_clean();

//     return $fragments;
// });

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
        if (! empty($sidebar_layout)) {
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
function luxury_jewels_footer_body_classes( $classes ) {
    $columns = get_theme_mod( 'luxury_jewels_footer_widget_columns', 4 );
    $classes[] = 'footer-columns-' . $columns;
    return $classes;
}
add_filter( 'body_class', 'luxury_jewels_footer_body_classes' );








add_action( 'init', 'custom_jewelry_product_summary_order' );
function custom_jewelry_product_summary_order() {
    // This function removes all default actions from the product summary
    remove_all_actions( 'woocommerce_single_product_summary' );

    // Re-add actions in your desired order

    // Add SKU and Category at the very top (Priority < 5)
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 4 );

    // Add the rest of the elements in their new order
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 15 );
    add_action( 'woocommerce_single_product_summary', 'luxury_jewels_divider', 17 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 25 );

    add_action( 'woocommerce_single_product_summary', 'luxury_jewels_render_custom_accordion', 35 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 40 );
}

function luxury_jewels_divider() {
    echo '<hr class="divider" />';
}



