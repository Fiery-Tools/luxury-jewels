<?php
/**
 * Customizer settings for Header & Navigation.
 *
 * @package luxury-jewels
 */

if ( ! class_exists( 'Luxury_Jewels_Customizer_Header' ) ) {

    class Luxury_Jewels_Customizer_Header {

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

            // --- Header Options Section ---
            $wp_customize->add_section( 'luxury_jewels_header_section', [
                'title'    => __( 'Header & Navigation', 'luxury-jewels' ),
                'priority' => 15,
                'panel'    => 'luxury_jewels_options_panel',
            ] );

            // Setting: Logo Max Width
            $wp_customize->add_setting( 'luxury_jewels_logo_size', [
                'default'           => 180,
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            ] );
            $wp_customize->add_control( 'luxury_jewels_logo_size_control', [
                'label'       => __( 'Logo Max Width (px)', 'luxury-jewels' ),
                'section'     => 'luxury_jewels_header_section',
                'settings'    => 'luxury_jewels_logo_size',
                'type'        => 'range',
                'input_attrs' => [ 'min' => 50, 'max' => 400, 'step' => 5 ],
            ] );

            // Setting: Header Layout
            $wp_customize->add_setting( 'luxury_jewels_header_layout', [
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_key',
            ] );
            $wp_customize->add_control( 'luxury_jewels_header_layout_control', [
                'label'    => __( 'Header Layout', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_header_section',
                'settings' => 'luxury_jewels_header_layout',
                'type'     => 'radio',
                'choices'  => [
                    'default'  => __( 'Logo Left, Navigation Right', 'luxury-jewels' ),
                    'centered' => __( 'Logo Centered, Navigation Below', 'luxury-jewels' ),
                ],
            ] );

            // Setting: Sticky Header
            $wp_customize->add_setting( 'luxury_jewels_sticky_header', [
                'default'           => 0,
                'sanitize_callback' => 'absint',
            ] );
            $wp_customize->add_control( 'luxury_jewels_sticky_header_control', [
                'label'    => __( 'Enable Sticky Header on Scroll', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_header_section',
                'settings' => 'luxury_jewels_sticky_header',
                'type'     => 'checkbox',
            ] );
        }
    }
}