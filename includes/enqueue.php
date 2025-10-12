<?php
/**
 * Enqueue scripts and styles for the Luxury Jewels theme.
 *
 * @package Luxury_Jewels
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Enqueue custom theme scripts.
 */
function luxury_jewels_enqueue_scripts() {
    $theme_version = wp_get_theme()->get('Version');

    // On single product pages, we disable the default WooCommerce tab script
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
}
add_action('wp_enqueue_scripts', 'luxury_jewels_enqueue_scripts', 100); // Use a high priority

// Enqueue styles
function luxury_jewels_scripts()
{
    $theme_version = wp_get_theme()->get('Version');
    wp_enqueue_style(
        'luxury-jewels-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;700&family=Lato:wght@400;700&display=swap',
        [],
        $theme_version
    );
    wp_enqueue_style('luxury-jewels-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'luxury_jewels_scripts');


