<?php
/**
 * Customizer settings for Footer
 *
 * @package luxury-jewels
 */

if ( ! class_exists( 'Luxury_Jewels_Customizer_Footer' ) ) {

    class Luxury_Jewels_Customizer_Footer {

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

            // --- Footer Options Section ---
            $wp_customize->add_section( 'luxury_jewels_Footer_section', [
                'title'    => __( 'Footer', 'luxury-jewels' ),
                'priority' => 55,
                'panel'    => 'luxury_jewels_options_panel',
            ] );

            // Setting: Footer Widget Columns
            $wp_customize->add_setting( 'luxury_jewels_footer_widget_columns', [
                'default'           => 4,
                'sanitize_callback' => 'absint',
            ] );
            $wp_customize->add_control( 'luxury_jewels_footer_widget_columns_control', [
                'label'       => __( 'Footer Widget Columns', 'luxury-jewels' ),
                'description' => __( 'Select the number of widget columns to display in the footer.', 'luxury-jewels' ),
                'section'     => 'luxury_jewels_Footer_section',
                'settings'    => 'luxury_jewels_footer_widget_columns',
                'type'        => 'select',
                'choices'     => [
                    '0' => __( '0 - No Widgets', 'luxury-jewels' ),
                    '1' => __( '1 Column', 'luxury-jewels' ),
                    '2' => __( '2 Columns', 'luxury-jewels' ),
                    '3' => __( '3 Columns', 'luxury-jewels' ),
                    '4' => __( '4 Columns', 'luxury-jewels' ),
                ],
            ] );

            // Setting: Copyright Text
            $wp_customize->add_setting( 'luxury_jewels_copyright_text', [
                'default'           => 'Copyright &copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '. All Rights Reserved.',
                'sanitize_callback' => 'wp_kses_post',
            ] );
            $wp_customize->add_control( 'luxury_jewels_copyright_text_control', [
                'label'    => __( 'Copyright Text', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_Footer_section',
                'settings' => 'luxury_jewels_copyright_text',
                'type'     => 'textarea',
            ] );

            // --- SEPARATOR ---
            $wp_customize->add_setting( 'luxury_jewels_footer_widget_info_separator', ['sanitize_callback' => 'esc_html'] );
            $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'luxury_jewels_footer_widget_info_separator_control',
                [
                    'type'        => 'hidden',
                    'section'     => 'luxury_jewels_Footer_section',
                    'settings'    => 'luxury_jewels_footer_widget_info_separator',
                    'description' => '<hr>',
                ]
            ) );

            // --- WIDGET INFO ---
            $wp_customize->add_setting( 'luxury_jewels_footer_widget_info_setting', ['sanitize_callback' => 'esc_html'] );
            $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'luxury_jewels_footer_widget_info_control',
                [
                    'section'     => 'luxury_jewels_Footer_section',
                    'settings'    => 'luxury_jewels_footer_widget_info_setting',
                    'type'        => 'hidden',
                    'description' => '<h3>' . __( 'Footer Widget Content', 'luxury-jewels' ) . '</h3><p>' . sprintf(
                        /* translators: %s: URL to the Widgets admin page. */
                        __( 'You can add content (like menus, text, or images) to your footer columns on the <a href="%s" target="_blank">Widgets screen</a>. The number of columns you selected above will determine how many "Footer Column" areas are available.', 'luxury-jewels' ),
                        esc_url( admin_url( 'widgets.php' ) )
                    ) . '</p>',
                ]
            ) );
        }
    }
}
