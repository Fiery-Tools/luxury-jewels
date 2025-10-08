<?php

/**
 * Luxury Jewels functions and definitions
 * @package Luxury_Jewels
 */

/**
 * Load theme customizer options.
 */
require get_template_directory() . '/includes/customizer.php';
// require get_template_directory() . '/includes/swatches.php';
require get_template_directory() . '/includes/attributes.php';

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

// Enqueue styles
function luxury_jewels_scripts()
{
    wp_enqueue_style(
        'luxury-jewels-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;700&family=Lato:wght@400;700&display=swap',
        [],
        null
    );
    wp_enqueue_style('luxury-jewels-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'luxury_jewels_scripts');

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
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    $cart_count = WC()->cart->get_cart_contents_count();

    ob_start();
    if ($cart_count > 0) :
?>
        <span class="cart-count-badge"><?php echo $cart_count; ?></span>
<?php
    endif;
    $fragments['.cart-count-badge'] = ob_get_clean();

    return $fragments;
});

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

    return $classes;
}
add_filter('body_class', 'luxury_jewels_body_classes');

// function wpb_image_editor_default_to_gd( $editors ) {
//     $gd_editor = 'WP_Image_Editor_GD';
//     $editors = array_diff( $editors, array( $gd_editor ) );
//     array_unshift( $editors, $gd_editor );
//     return $editors;
// }
// add_filter( 'wp_image_editors', 'wpb_image_editor_default_to_gd' );

// Add this to the end of functions.php

/**
 * 1. LOGIC FOR SHOP PAGE COLUMNS
 * Changes the number of product columns on the shop page.
 */
function luxury_jewels_set_shop_columns($columns)
{
    return get_theme_mod('luxury_jewels_shop_columns', 3);
}
add_filter('loop_shop_columns', 'luxury_jewels_set_shop_columns');


/**
 * 2. LOGIC FOR SHOP SIDEBAR
 * Adds a body class based on the selected sidebar layout for shop pages.
 */
function luxury_jewels_shop_body_classes($classes)
{
    // Apply sidebar class only on WooCommerce archive pages
    if (is_shop() || is_product_category() || is_product_tag()) {
        $sidebar_layout = get_theme_mod('luxury_jewels_shop_sidebar_layout', 'no-sidebar');
        if (! empty($sidebar_layout)) {
            $classes[] = 'shop-sidebar-' . $sidebar_layout;
        }
    }
    return $classes;
}
// Note: We are hooking into the existing 'body_class' filter, not adding a new one.
// If you already have a function hooked to 'body_class', add this logic into it.
add_filter('body_class', 'luxury_jewels_shop_body_classes');


/**
 * 3. LOGIC FOR SALE BADGE TEXT
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

//////////////////////////


add_action( 'init', 'custom_jewelry_product_summary_order' );
function custom_jewelry_product_summary_order() {

    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    remove_all_actions( 'woocommerce_single_product_summary' );

    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
    // add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 15 );
    add_action( 'woocommerce_single_product_summary', 'luxury_jewels_divider', 17 );
    // add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 25 );

    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 30 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 35 );

    // 8. Social Sharing Buttons (Uncomment if your theme has them and you want to show them)
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 40 );

}

function luxury_jewels_divider() {
    echo '<hr class="divider" />';
}

// You might also need a function to add a wishlist button.
// This example assumes you are using the "YITH WooCommerce Wishlist" plugin, which is very popular.
// If you are using a different plugin, you will need to find its specific shortcode or function.
add_action( 'woocommerce_single_product_summary', 'custom_display_wishlist_button', 35 );
function custom_display_wishlist_button() {
    if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
        echo '<div class="custom-wishlist-button">' . do_shortcode('[yith_wcwl_add_to_wishlist]') . '</div>';
    }
}









// begin refactoring

// end refactoring



// filters

/**
 * Display dynamic product attribute filters.
 * This function automatically finds all product attributes and displays them as a filter list.
 */
function luxury_jewels_dynamic_attribute_filters() {
    // Get all product attribute taxonomies (like pa_color, pa_size)
    $attribute_taxonomies = wc_get_attribute_taxonomies();

    if ( empty( $attribute_taxonomies ) ) {
        return;
    }

    echo '<div class="dynamic-attribute-filters">';

    foreach ( $attribute_taxonomies as $taxonomy ) {
        $taxonomy_name = wc_attribute_taxonomy_name( $taxonomy->attribute_name );

        // Get all terms for the current attribute (e.g., 'Red', 'Blue' for 'Color')
        $terms = get_terms( array(
            'taxonomy'   => $taxonomy_name,
            'hide_empty' => true, // Only show terms that are attached to products
        ) );

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            continue; // Skip to the next attribute if no terms are found
        }

        // Display the attribute name as a title (e.g., "Color")
        echo '<section class="widget widget_layered_nav">';
        echo '<h2 class="widget-title">' . esc_html( $taxonomy->attribute_label ) . '</h2>';
        echo '<ul>';

        // Check for currently active filters from the URL
        $current_filter = isset( $_GET['filter_' . $taxonomy->attribute_name] ) ?
                          explode( ',', wc_clean( wp_unslash( $_GET['filter_' . $taxonomy->attribute_name] ) ) ) :
                          [];

        foreach ( $terms as $term ) {
            $is_active = in_array( $term->slug, $current_filter );
            $link_filters = $current_filter;

            if ( $is_active ) {
                // If the term is already active, the link should remove it
                $link_filters = array_diff( $link_filters, [ $term->slug ] );
            } else {
                // If the term is not active, the link should add it
                $link_filters[] = $term->slug;
            }

            // Build the URL for the link
            $link = add_query_arg(
                'filter_' . $taxonomy->attribute_name,
                implode( ',', $link_filters ),
                get_permalink( wc_get_page_id( 'shop' ) )
            );

            // If the filter list for this attribute is now empty, remove the parameter from the URL
            if ( empty( $link_filters ) ) {
                $link = remove_query_arg( 'filter_' . $taxonomy->attribute_name, $link );
            }

            // Add an 'active' class for styling
            $class = $is_active ? 'class="active"' : '';

            echo '<li>';
            echo '<a href="' . esc_url( $link ) . '" ' . $class . '>' . esc_html( $term->name ) . '</a>';
            echo '</li>';
        }

        echo '</ul>';
        echo '</section>';
    }

    echo '</div>';
}







// Enqueue AJAX add to cart script
function luxury_jewels_ajax_add_to_cart_script()
{
    wp_enqueue_script(
        'luxury-ajax-add-to-cart',
        get_template_directory_uri() . '/js/stuff.js',
        ['jquery'],
        '1.0',
        true
    );

    wp_localize_script('luxury-ajax-add-to-cart', 'luxury_jewels_cart_params', [
        'ajax_url'   => admin_url('admin-ajax.php'),
        'wc_ajax_url' => WC_AJAX::get_endpoint("%%endpoint%%"),
    ]);
}
add_action('wp_enqueue_scripts', 'luxury_jewels_ajax_add_to_cart_script');






/**
 * Sets the default widgets for the shop sidebar on theme activation.
 *
 * This function runs only once to prevent WordPress from adding default
 * blog widgets and to provide a clean "out-of-the-box" experience.
 * It also sets a flag to ensure it doesn't override user customizations later.
 */
function luxury_jewels_setup_default_widgets() {

    // Check if we've already run this setup.
    if ( get_option( 'luxury_jewels_widgets_initialized' ) ) {
        return;
    }

    // --- 1. Create the Widget Instances ---
    // Get the widget settings from the database.
    $widget_price_filter = get_option( 'widget_woocommerce_price_filter', [] );
    $widget_product_categories = get_option( 'widget_woocommerce_product_categories', [] );

    // **FIXED LOGIC:** Get the next available ID by only looking at numeric keys.
    $price_numeric_keys = array_filter( array_keys( $widget_price_filter ), 'is_numeric' );
    $price_filter_id = empty( $price_numeric_keys ) ? 1 : max( $price_numeric_keys ) + 1;

    $cat_numeric_keys = array_filter( array_keys( $widget_product_categories ), 'is_numeric' );
    $categories_id = empty( $cat_numeric_keys ) ? 1 : max( $cat_numeric_keys ) + 1;


    // Define the settings for our new Price Filter widget.
    $widget_price_filter[ $price_filter_id ] = [
        'title' => __( 'Filter by Price', 'luxury-jewels' ),
    ];

    // Define the settings for our new Product Categories widget.
    $widget_product_categories[ $categories_id ] = [
        'title' => __( 'Product Categories', 'luxury-jewels' ),
        'orderby' => 'name',
        'dropdown' => 0,
        'count' => 0,
        'hierarchical' => 1,
        'show_children_only' => 0,
        'hide_empty' => 0,
    ];

    // Save the new widget instances back to the database.
    update_option( 'widget_woocommerce_price_filter', $widget_price_filter );
    update_option( 'widget_woocommerce_product_categories', $widget_product_categories );


    // --- 2. Assign the New Widgets to the Shop Sidebar ---
    $sidebars_widgets = get_option( 'sidebars_widgets', [] );

    // Assign our newly created widget instances to the 'shop-sidebar'.
    $sidebars_widgets['shop-sidebar'] = [
        'woocommerce_price_filter-' . $price_filter_id,
        'woocommerce_product_categories-' . $categories_id,
    ];

    update_option( 'sidebars_widgets', $sidebars_widgets );

    // --- 3. Set a flag so this function never runs again ---
    update_option( 'luxury_jewels_widgets_initialized', true );
}
add_action( 'after_switch_theme', 'luxury_jewels_setup_default_widgets' );


// all attributes

function luxury_jewels_get_all_product_attributes() {
    $all_attributes = [];

    // Get all global attribute taxonomies (e.g., pa_metal, pa_size)
    $attribute_taxonomies = wc_get_attribute_taxonomies();

    if ( empty( $attribute_taxonomies ) ) {
        return $all_attributes;
    }

    foreach ( $attribute_taxonomies as $tax ) {
        $taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name ); // Formats to 'pa_metal'
        $taxonomy_name = sanitize_title($tax->attribute_name);


        // Get all terms for this attribute, but only if they're used by products
        $terms = get_terms( [
            'taxonomy'   => $taxonomy_name,
            'hide_empty' => true, // This is the key to only showing options that exist
        ] );

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            continue; // Skip if no terms are found for this attribute
        }

        $options = [];
        foreach ( $terms as $term ) {
            $options[] = [
                'name' => $term->name,
                'slug' => $term->slug,
            ];
        }

        // Add the structured data to our main array
        $all_attributes[] = [
            'name'  => $tax->attribute_name, // e.g., 'metal'
            'label' => $tax->attribute_label, // e.g., 'Metal'
            'options' => $options,
        ];
    }

    return $all_attributes;
}

/**
 * Displays the custom product attribute filters as a list of links.
 *
 * This function constructs the correct URLs for adding/removing filters
 * and adds an 'active' class to currently selected filters.
 */
function luxury_jewels_display_custom_filters() {
    $attribute_groups = luxury_jewels_get_all_product_attributes();

    if ( empty( $attribute_groups ) ) {
        return;
    }

    $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );

    echo '<div class="custom-attribute-filters">';

    foreach ( $attribute_groups as $group ) {
        $filter_key = 'filter_' . $group['name'];
        $current_filters = isset( $_GET[ $filter_key ] ) ? explode( ',', wc_clean( $_GET[ $filter_key ] ) ) : [];

        echo '<section class="widget widget_custom_filter">';
        echo '<h2 class="widget-title">' . esc_html( $group['label'] ) . '</h2>';

        $attribute = wc_get_attribute_taxonomy_by_name( $group['name'] );
        $display_type = 'swatch';
        if ( $attribute ) {
            $display_type = get_option( 'lj_attribute_display_type_' . $attribute->attribute_id, 'swatch' );
        }

        $taxonomy_name = 'pa_' . $group['name'];
        $options_html = '';

        foreach ( $group['options'] as $option ) {
            $link_filters = $current_filters;
            $is_active = in_array( $option['slug'], $link_filters );

            if ( $is_active ) {
                $link_filters = array_diff( $link_filters, [ $option['slug'] ] );
            } else {
                $link_filters[] = $option['slug'];
            }

            $query_type_key = 'query_type_' . $group['name'];
            if ( empty( $link_filters ) ) {
                $link = remove_query_arg( array( $filter_key, $query_type_key ), $shop_page_url );
            } else {
                $link = add_query_arg( array(
                    $filter_key     => implode( ',', $link_filters ),
                    $query_type_key => 'or',
                ), $shop_page_url );
            }

            if ( 'dropdown' === $display_type ) {
                $class = $is_active ? 'class="active"' : '';
                $options_html .= '<li><a href="' . esc_url( $link ) . '" ' . $class . '>' . esc_html( $option['name'] ) . '</a></li>';
            } else {
                $term = get_term_by( 'slug', $option['slug'], $taxonomy_name );
                if ( ! $term ) continue;

                $swatch_color = get_term_meta( $term->term_id, '_swatch_color', true );
                $class = 'swatch' . ($is_active ? ' selected' : '');

                if ( 'swatch' === $display_type && $swatch_color ) {
                    $class .= ' swatch-color';
                    $options_html .= '<a href="' . esc_url( $link ) . '" class="' . esc_attr( $class ) . '" style="background-color:' . esc_attr( $swatch_color ) . ';" title="' . esc_attr( $option['name'] ) . '"></a>';
                } else {
                    $class .= ' swatch-label';
                    $options_html .= '<a href="' . esc_url( $link ) . '" class="' . esc_attr( $class ) . '">' . esc_html( $option['name'] ) . '</a>';
                }
            }
        }

        if ( 'dropdown' === $display_type ) {
            echo '<ul>' . $options_html . '</ul>';
        } else {
            $container_class = 'swatches';
            if ( 'button' === $display_type ) {
                $container_class .= ' is-buttons';
            }
            echo '<div class="' . esc_attr( $container_class ) . '" data-attribute_name="attribute_' . esc_attr( $group['name'] ) . '">' . $options_html . '</div>';
        }

        echo '</section>';
    }

    echo '</div>';
}
