<?php
/**
 * Enqueue scripts and styles for the Luxury Jewels theme.
 *
 * @package luxury-jewels
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Enqueue all theme scripts and styles.
 */
function luxury_jewels_enqueue_assets() {
    // Get the theme version dynamically so you don't have to update it here.
    $theme_version = wp_get_theme()->get('Version');

    // === STYLES ===

    // Enqueue Google Fonts
    wp_enqueue_style(
        'luxury-jewels-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;700&family=Lato:wght@400;700&display=swap',
        [],
        null // Google Fonts do not need a version number.
    );

    // Enqueue the main stylesheet.
    wp_enqueue_style(
        'luxury-jewels-style',
        get_stylesheet_uri(),
        [], // No dependencies
        $theme_version
    );


    // === SCRIPTS ===

    // On single product pages, disable the default WooCommerce tab script
    if ( is_product() ) {
        wp_dequeue_script( 'wc-tabs' );
    }

    // Enqueue our theme's custom JavaScript file
    wp_enqueue_script(
        'luxury-jewels-main',
        get_template_directory_uri() . '/js/frontend-script.js',
        array('jquery'),
        $theme_version,
        true
    );

    // FIX: Enqueue the comment-reply script to resolve the theme check recommendation.
    // This is the only new logic being added.
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
// Use a high priority (100) to ensure this runs after WooCommerce adds its scripts,
// which is necessary for your wp_dequeue_script() call to work correctly.
add_action('wp_enqueue_scripts', 'luxury_jewels_enqueue_assets', 100);