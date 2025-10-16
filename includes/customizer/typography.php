<?php
/**
 * Customizer settings for Typography.
 *
 * @package luxury-jewels
 */

if ( ! class_exists( 'Luxury_Jewels_Customizer_Typography' ) ) {

    class Luxury_Jewels_Customizer_Typography {

        /**
         * @var WP_Customize_Manager
         */
        protected $wp_customize;

        public function __construct( $wp_customize ) {
            $this->wp_customize = $wp_customize;
            $this->add_settings();
        }

        protected function add_settings() {
            $wp_customize = $this->wp_customize;

            // --- Typography Section ---
            $wp_customize->add_section( 'luxury_jewels_typography_section', [
                'title'    => __( 'Typography', 'luxury-jewels' ),
                'priority' => 20,
                'panel'    => 'luxury_jewels_options_panel',
            ] );

            // Setting: Heading Font
            $wp_customize->add_setting( 'luxury_jewels_font_headings', ['default' => 'Cormorant Garamond', 'sanitize_callback' => 'sanitize_text_field'] );
            $wp_customize->add_control( 'luxury_jewels_font_headings_control', [
                'label'    => __( 'Heading Font (Google Fonts Name)', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_typography_section',
                'settings' => 'luxury_jewels_font_headings',
                'type'     => 'text',
            ] );

            // Setting: Body Font
            $wp_customize->add_setting( 'luxury_jewels_font_body', ['default' => 'Lato', 'sanitize_callback' => 'sanitize_text_field'] );
            $wp_customize->add_control( 'luxury_jewels_font_body_control', [
                'label'    => __( 'Body Font (Google Fonts Name)', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_typography_section',
                'settings' => 'luxury_jewels_font_body',
                'type'     => 'text',
            ] );
        }
    }
}