<?php
/**
 * Customizer settings for Brand Colors.
 *
 * @package luxury-jewels
 */

if ( ! class_exists( 'Luxury_Jewels_Customizer_Colors' ) ) {

    class Luxury_Jewels_Customizer_Colors {

        /**
         * @var WP_Customize_Manager
         */
        protected $wp_customize;

        /**
         * Defines the color palette for use in Customizer controls.
         * @var array
         */
        protected $palette;

        public function __construct( $wp_customize ) {
            $this->wp_customize = $wp_customize;
            $this->palette      = ['#D4AF37', '#B8941E', '#E8D4A0', '#1A1A1A', '#2C2C2C', '#FAF8F3', '#333333', '#666666', '#E5DDD0'];
            $this->add_settings();
        }

        protected function add_settings() {
            $wp_customize = $this->wp_customize;
            $palette      = $this->palette;

            // --- Brand Colors Section ---
            $wp_customize->add_section( 'luxury_jewels_colors_section', [
                'title'    => __( 'Brand Colors', 'luxury-jewels' ),
                'priority' => 10,
                'panel'    => 'luxury_jewels_options_panel',
            ] );

            // Setting: Accent Color
            $wp_customize->add_setting( 'luxury_jewels_color_accent', ['default' => '#D4AF37', 'sanitize_callback' => 'sanitize_hex_color'] );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luxury_jewels_color_accent_control', [
                'label'    => __( 'Accent Color (Buttons, Highlights)', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_colors_section',
                'settings' => 'luxury_jewels_color_accent',
                'palettes' => $palette,
            ] ) );

            // Setting: Accent Dark Color
            $wp_customize->add_setting( 'luxury_jewels_color_accent_dark', ['default' => '#B8941E', 'sanitize_callback' => 'sanitize_hex_color'] );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luxury_jewels_color_accent_dark_control', [
                'label'    => __( 'Accent Dark (Prices, Hover States)', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_colors_section',
                'settings' => 'luxury_jewels_color_accent_dark',
                'palettes' => $palette,
            ] ) );

            // Setting: Primary CTA Color
            $wp_customize->add_setting( 'luxury_jewels_color_primary_cta', [
                'default'           => '#D4AF37',
                'sanitize_callback' => 'sanitize_hex_color',
            ] );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luxury_jewels_color_primary_cta_control', [
                'label'       => __( 'Primary Action Color (Add to Cart)', 'luxury-jewels' ),
                'description' => __( 'The most important button on the site.', 'luxury-jewels' ),
                'section'     => 'luxury_jewels_colors_section',
                'settings'    => 'luxury_jewels_color_primary_cta',
                'palettes'    => $palette,
            ] ) );

            // Setting: Site Background Color
            $wp_customize->add_setting( 'luxury_jewels_color_background', ['default' => '#FAF8F3', 'sanitize_callback' => 'sanitize_hex_color'] );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luxury_jewels_color_background_control', [
                'label'    => __( 'Site Background Color', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_colors_section',
                'settings' => 'luxury_jewels_color_background',
                'palettes' => $palette,
            ] ) );

            // Setting: Headings & Titles Color
            $wp_customize->add_setting( 'luxury_jewels_color_text_headings', ['default' => '#1A1A1A', 'sanitize_callback' => 'sanitize_hex_color'] );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luxury_jewels_color_text_headings_control', [
                'label'    => __( 'Headings & Titles Color', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_colors_section',
                'settings' => 'luxury_jewels_color_text_headings',
                'palettes' => $palette,
            ] ) );

            // --- NEW SETTING: Secondary Color (to match CSS) ---
            $wp_customize->add_setting( 'luxury_jewels_color_secondary', ['default' => '#2C2C2C', 'sanitize_callback' => 'sanitize_hex_color'] );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luxury_jewels_color_secondary_control', [
                'label'    => __( 'Secondary Color (Used for Borders/Accents)', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_colors_section',
                'settings' => 'luxury_jewels_color_secondary',
                'palettes' => $palette,
            ] ) );

            // Setting: Body Text Color
            $wp_customize->add_setting( 'luxury_jewels_color_text_body', ['default' => '#333333', 'sanitize_callback' => 'sanitize_hex_color'] );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luxury_jewels_color_text_body_control', [
                'label'    => __( 'Body Text Color', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_colors_section',
                'settings' => 'luxury_jewels_color_text_body',
                'palettes' => $palette,
            ] ) );

            // Setting: Border Color
            $wp_customize->add_setting( 'luxury_jewels_color_border', ['default' => '#E5DDD0', 'sanitize_callback' => 'sanitize_hex_color'] );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luxury_jewels_color_border_control', [
                'label'    => __( 'Border Color', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_colors_section',
                'settings' => 'luxury_jewels_color_border',
                'palettes' => $palette,
            ] ) );

            // FIX: Reverted to the original ID 'luxury_jewels_color_header_bg' for backward compatibility.
            $wp_customize->add_setting( 'luxury_jewels_color_header_bg', ['default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color'] );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'luxury_jewels_color_header_bg_control', [
                'label'    => __( 'Header & Footer Background', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_colors_section',
                'settings' => 'luxury_jewels_color_header_bg',
                'palettes' => $palette,
            ] ) );
        }
    }
}