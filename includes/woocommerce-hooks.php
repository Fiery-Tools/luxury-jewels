<?php
/**
 * All WooCommerce custom hooks and functions for the Luxury Jewels theme.
 * FINAL CLEANED VERSION.
 *
 * @package luxury-jewels
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// =============================================================================
// == 1. PER-PRODUCT TABS (META BOX LOGIC)
// =============================================================================

function luxury_jewels_add_extra_tabs_meta_box() {
    add_meta_box('luxury_jewels_extra_tabs', __('Extra Product Tabs Content', 'luxury-jewels'), 'luxury_jewels_extra_tabs_meta_box_html', 'product', 'normal', 'default');
}
add_action('add_meta_boxes', 'luxury_jewels_add_extra_tabs_meta_box');

function luxury_jewels_extra_tabs_meta_box_html($post) {
    $content = get_post_meta($post->ID, '_extra_tabs_content', true);
    wp_nonce_field('luxury_jewels_save_extra_tabs_data', 'luxury_jewels_extra_tabs_nonce');
    echo '<p>' . __('Use a <strong>Heading 2 (H2)</strong> for each tab title. All content below that heading will become the content for that tab.', 'luxury-jewels') . '</p>';
    wp_editor($content, '_extra_tabs_content', ['textarea_name' => 'extra_tabs_content', 'media_buttons' => true, 'textarea_rows' => 15]);
}

function luxury_jewels_save_extra_tabs_data($post_id) {
    if (!isset($_POST['luxury_jewels_extra_tabs_nonce']) || !wp_verify_nonce($_POST['luxury_jewels_extra_tabs_nonce'], 'luxury_jewels_save_extra_tabs_data')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (isset($_POST['extra_tabs_content'])) {
        update_post_meta($post_id, '_extra_tabs_content', wp_kses_post($_POST['extra_tabs_content']));
    }
}
add_action('save_post_product', 'luxury_jewels_save_extra_tabs_data');


// =============================================================================
// == 2. GATHER ALL TAB DATA
// =============================================================================

add_filter('woocommerce_product_tabs', 'luxury_jewels_manage_product_tabs');
function luxury_jewels_manage_product_tabs($tabs) {
    global $post;

    unset($tabs['reviews'], $tabs['additional_information']);

    if (isset($tabs['description'])) {
        $tabs['description']['callback'] = 'luxury_jewels_description_tab_content';
    }

    for ($i = 1; $i <= 3; $i++) {
        $title = get_theme_mod('global_tab_' . $i . '_title');
        $content = get_theme_mod('global_tab_' . $i . '_content');
        if (!empty($title) && !empty($content)) {
            $tabs['global_tab_' . $i] = ['title' => $title, 'priority' => 20 + $i, 'callback' => 'luxury_jewels_custom_tab_callback', 'content' => $content];
        }
    }

    $per_product_content = get_post_meta($post->ID, '_extra_tabs_content', true);
    if (!empty($per_product_content)) {
        $sections = preg_split('/(<h2.*?>.*?<\/h2>)/i', $per_product_content, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        if (count($sections) >= 2) {
            $priority = 40;
            $counter = 1;
            for ($i = 0; $i < count($sections); $i += 2) {
                if (isset($sections[$i + 1])) {
                    $tabs['per_product_tab_' . $counter] = ['title' => wp_strip_all_tags($sections[$i]), 'priority' => $priority, 'callback' => 'luxury_jewels_custom_tab_callback', 'content' => $sections[$i + 1]];
                    $priority += 5;
                    $counter++;
                }
            }
        }
    }

    return $tabs;
}

// =============================================================================
// == 3. RENDER THE CUSTOM ACCORDION
// =============================================================================

function luxury_jewels_render_custom_accordion() {
    $tabs = apply_filters('woocommerce_product_tabs', []);
    if (empty($tabs)) return;

    uasort($tabs, function($a, $b) {
        $p1 = isset($a['priority']) ? (int) $a['priority'] : 20;
        $p2 = isset($b['priority']) ? (int) $b['priority'] : 20;
        return $p1 <=> $p2;
    });
    ?>
    <div class="luxury-jewels-tabs">
        <?php foreach ($tabs as $key => $tab) : ?>
            <div class="lj-accordion-item" id="lj-tab-<?php echo esc_attr($key); ?>">
                <h2 class="lj-accordion-title"><?php echo esc_html($tab['title']); ?></h2>
                <div class="lj-accordion-content">
                    <?php if (isset($tab['callback'])) call_user_func($tab['callback'], $key, $tab); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}

// =============================================================================
// == 4. HELPER CALLBACK FUNCTIONS
// =============================================================================

function luxury_jewels_description_tab_content() {
    the_content();
}

function luxury_jewels_custom_tab_callback($key, $tab) {
    echo apply_filters('the_content', $tab['content']);
}


// == 5. PRODUCT VARIATION CUSTOMIZATIONS
// =============================================================================

/**
 * Removes the variation description from the data sent to the frontend.
 * This prevents the description box from appearing below the swatches
 * when a user selects a product variation.
 *
 * @param array $variation_data The data for a single product variation.
 * @return array The modified variation data without the description.
 */
function luxury_jewels_remove_variation_description( $variation_data ) {
    // The description is stored in this array key. We simply unset it.
    if ( isset( $variation_data['variation_description'] ) ) {
        unset( $variation_data['variation_description'] );
    }
    return $variation_data;
}
add_filter( 'woocommerce_available_variation', 'luxury_jewels_remove_variation_description' );

// =============================================================================
// == 6. CUSTOM SIDEBAR FILTERS (SWATCHES & BUTTONS)
// =============================================================================

/**
 * Customize the HTML for terms in the Layered Nav widget to display swatches or buttons.
 *
 * @param string   $term_html The original HTML for the term (an `<a>` tag).
 * @param WP_Term  $term      The term object.
 * @param string   $link      The link for the term.
 * @param int      $count     The product count for the term.
 * @return string  The modified HTML.
 */
function luxury_jewels_custom_layered_nav_term_html( $term_html, $term, $link, $count ) {
    // Get our custom display type for this attribute's taxonomy
    $attr_data = luxury_jewels_get_taxonomy( $term->taxonomy );

    // If it's not a swatch or button type, or if data is missing, return the default HTML
    if ( ! $attr_data || ! in_array( $attr_data['display_type'], [ 'swatch', 'button' ] ) ) {
        return $term_html;
    }

    // Check if the current term is an active filter.
    $is_active = false;
    $chosen_attributes = WC()->query->get_layered_nav_chosen_attributes();
    if ( isset( $chosen_attributes[ $term->taxonomy ] ) && in_array( $term->slug, $chosen_attributes[ $term->taxonomy ]['terms'] ) ) {
        $is_active = true;
    }

    $class = 'lj-filter-item' . ( $is_active ? ' is-active' : '' );
    $label = esc_html( $term->name );

    // Generate custom HTML based on display type
    if ( 'swatch' === $attr_data['display_type'] ) {
        $color = get_term_meta( $term->term_id, '_swatch_color', true );
        return sprintf(
            '<a rel="nofollow" href="%s" class="%s swatch-filter" title="%s" style="background-color:%s;"><span class="screen-reader-text">%s</span></a>',
            esc_url( $link ),
            esc_attr( $class ),
            esc_attr( $label ),
            esc_attr( $color ?: '#fff' ),
            $label
        );
    }

    if ( 'button' === $attr_data['display_type'] ) {
        return sprintf(
            '<a rel="nofollow" href="%s" class="%s button-filter">%s</a>',
            esc_url( $link ),
            esc_attr( $class ),
            $label
        );
    }

    return $term_html;
}
add_filter( 'woocommerce_layered_nav_term_html', 'luxury_jewels_custom_layered_nav_term_html', 10, 4 );

/**
 * Add custom classes to the layered nav filter list (<ul>) for styling.
 *
 * @param array $list_args Arguments for the list.
 * @return array Modified arguments.
 */
function luxury_jewels_layered_nav_list_args($list_args) {
    if ( ! isset($list_args['taxonomy']) ) {
        return $list_args;
    }

    $attr_data = luxury_jewels_get_taxonomy($list_args['taxonomy']);

    if ($attr_data && in_array($attr_data['display_type'], ['swatch', 'button'])) {
        // Prepend our custom classes to any existing classes.
        $existing_class = $list_args['list_class'] ?? '';
        $list_args['list_class'] = 'lj-filter-list type-' . $attr_data['display_type'] . ' ' . $existing_class;
    }

    return $list_args;
}
add_filter('woocommerce_layered_nav_list_args', 'luxury_jewels_layered_nav_list_args');


/**
 * Displays previous/next product navigation on single product pages.
 *
 * This function creates links to the next and previous products within the
 * same product category, enhancing user navigation.
 */
function luxury_jewels_product_nav() {
    // Get links to the previous and next products.
    // The 'true' argument for in_same_term ensures navigation is within the same 'product_cat'.
    $previous_link = get_previous_post_link( '%link', '<span class="icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon feather feather-chevron-left" aria-hidden="true" focusable="false" role="presentation"><path d="m15 18-6-6 6-6"></path></svg></span> Previous', true, '', 'product_cat' );
    $next_link     = get_next_post_link( '%link', '<span>Next</span> <span class="icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon feather feather-chevron-right" aria-hidden="true" focusable="false" role="presentation"><path d="m9 18 6-6-6-6"></path></svg></span>', true, '', 'product_cat' );


    // Only display the navigation container if there's a link to show.
    if ( $previous_link || $next_link ) {
        echo '<nav class="product-navigation">';
        echo $previous_link;
        echo $next_link;
        echo '</nav>';
    }
}
