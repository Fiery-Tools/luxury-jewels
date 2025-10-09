<?php
/**
 * All WooCommerce custom hooks and functions for the Luxury Jewels theme.
 * FINAL CLEANED VERSION.
 *
 * @package Luxury_Jewels
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