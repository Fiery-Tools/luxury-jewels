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

    // --- This is the new, crucial code ---
    // On single product pages, we disable the default WooCommerce tab script
    // to prevent conflicts with our custom accordion.
    if ( is_product() ) {
        wp_dequeue_script( 'wc-tabs' );
    }
    // --- End of new code ---


    // Enqueue our theme's custom JavaScript file
    wp_enqueue_script(
        'luxury-jewels-main',
        get_template_directory_uri() . '/js/stuff.js',
        array('jquery'),
        '1.0.1', // Bumped version number
        true
    );
}
add_action('wp_enqueue_scripts', 'luxury_jewels_enqueue_scripts', 100); // Use a high priority

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

add_action('wp_enqueue_scripts', function() {
    // Remove WooCommerce's single product JS (it hides the tabs)
    wp_dequeue_script('wc-single-product');
    wp_deregister_script('wc-single-product');
}, 99);

add_action('wp_head', function(){
  ?>
  <script>
  (function(){
    // minimal, same idea as above
    const origSetAttribute = Element.prototype.setAttribute;
    Element.prototype.setAttribute = function(name, value) {
      if (name === 'style' && this.classList && this.classList.contains('woocommerce-Tabs-panel')) {
        console.warn('setAttribute(style) on .woocommerce-Tabs-panel', this, value);
        console.trace();
      }
      return origSetAttribute.apply(this, arguments);
    };
    const origSetProperty = CSSStyleDeclaration.prototype.setProperty;
    CSSStyleDeclaration.prototype.setProperty = function(name, val, prio) {
      if (name === 'display') {
        console.warn('style.setProperty display', val, this);
        console.trace();
      }
      return origSetProperty.apply(this, arguments);
    };
  })();
  </script>
  <?php
}, 1); // very early
