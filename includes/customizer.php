<?php
/**
 * Luxury Jewels Theme Customizer Orchestrator
 *
 * This file sets up the main Customizer panel and loads all sub-module classes.
 *
 * @package luxury-jewels
 */

// Define the customizer sub-directory path for easy access.
define( 'LUXURY_JEWELS_CUSTOMIZER_DIR', trailingslashit( get_template_directory() ) . 'includes/customizer/' );

/**
 * Load all customizer sub-modules.
 */
function luxury_jewels_load_customizer_modules() {
    // Load sub-classes
    require_once LUXURY_JEWELS_CUSTOMIZER_DIR . 'header.php';
    require_once LUXURY_JEWELS_CUSTOMIZER_DIR . 'colors.php';
    require_once LUXURY_JEWELS_CUSTOMIZER_DIR . 'typography.php';
    require_once LUXURY_JEWELS_CUSTOMIZER_DIR . 'shop.php';
    require_once LUXURY_JEWELS_CUSTOMIZER_DIR . 'payment_icons.php';
    require_once LUXURY_JEWELS_CUSTOMIZER_DIR . 'css_output.php';
    require_once LUXURY_JEWELS_CUSTOMIZER_DIR . 'footer.php';
}
add_action( 'after_setup_theme', 'luxury_jewels_load_customizer_modules' );


/**
 * Main class to manage the WordPress Customizer registration process.
 */
class Luxury_Jewels_Customizer {

    /**
     * The single instance of the class.
     * @var Luxury_Jewels_Customizer
     */
    protected static $instance = null;

    /**
     * Get the single instance of this class.
     * @return Luxury_Jewels_Customizer
     */
    public static function init() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'customize_register', array( $this, 'register_panels_and_sections' ) );
    }

    /**
     * Register all theme options in the WordPress Customizer.
     *
     * @param WP_Customize_Manager $wp_customize The Customizer Manager object.
     */
    public function register_panels_and_sections( $wp_customize ) {

        // 1. Add the main panel.
        $wp_customize->add_panel( 'luxury_jewels_options_panel', [
            'title'    => __( 'Luxury Jewels Theme Options', 'luxury-jewels' ),
            'priority' => 10,
        ] );

        // 2. Instantiate all the Customizer modules.
        // Each sub-class handles adding its own sections, settings, and controls.
        new Luxury_Jewels_Customizer_Header( $wp_customize );
        new Luxury_Jewels_Customizer_Colors( $wp_customize );
        new Luxury_Jewels_Customizer_Typography( $wp_customize );
        new Luxury_Jewels_Customizer_Shop( $wp_customize );
        new Luxury_Jewels_Customizer_Payment_Icons( $wp_customize );
        new Luxury_Jewels_Customizer_Footer( $wp_customize );

        // The CSS generation is hooked via the CSS_Output class, not instantiated here.
    }
}

// Initialize the main Customizer class.
Luxury_Jewels_Customizer::init();