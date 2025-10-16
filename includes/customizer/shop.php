<?php
/**
 * Customizer settings for Shop & Product Pages.
 *
 * @package luxury-jewels
 */

if ( ! class_exists( 'Luxury_Jewels_Customizer_Shop' ) ) {

    class Luxury_Jewels_Customizer_Shop {

        /**
         * @var WP_Customize_Manager
         */
        protected $wp_customize;

        public function __construct( $wp_customize ) {
            $this->wp_customize = $wp_customize;
            $this->add_settings();
            $this->add_global_tabs();
            $this->add_swatch_info();
        }

        protected function add_settings() {
            $wp_customize = $this->wp_customize;

            // --- Shop Layout Section ---
            $wp_customize->add_section( 'luxury_jewels_shop_section', [
                'title'    => __( 'Shop & Product Pages', 'luxury-jewels' ),
                'priority' => 25,
                'panel'    => 'luxury_jewels_options_panel',
            ] );

            // Setting: Shop Columns
            $wp_customize->add_setting( 'luxury_jewels_shop_columns', [
                'default'           => 3,
                'sanitize_callback' => 'absint',
            ] );
            $wp_customize->add_control( 'luxury_jewels_shop_columns_control', [
                'label'    => __( 'Products Per Row on Shop Page', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_shop_section',
                'settings' => 'luxury_jewels_shop_columns',
                'type'     => 'select',
                'choices'  => [
                    '2' => __( '2 Columns', 'luxury-jewels' ),
                    '3' => __( '3 Columns', 'luxury-jewels' ),
                    '4' => __( '4 Columns', 'luxury-jewels' ),
                ],
            ] );

            // Setting: Shop Sidebar Layout
            $wp_customize->add_setting( 'luxury_jewels_shop_sidebar_layout', [
                'default'           => 'no-sidebar',
                'sanitize_callback' => 'sanitize_key',
            ] );
            $wp_customize->add_control( 'luxury_jewels_shop_sidebar_layout_control', [
                'label'    => __( 'Shop & Archive Sidebar', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_shop_section',
                'settings' => 'luxury_jewels_shop_sidebar_layout',
                'type'     => 'radio',
                'choices'  => [
                    'no-sidebar'    => __( 'No Sidebar', 'luxury-jewels' ),
                    'left-sidebar'  => __( 'Left Sidebar', 'luxury-jewels' ),
                    'right-sidebar' => __( 'Right Sidebar', 'luxury-jewels' ),
                ],
            ] );

            // Setting: Sale Badge Text
            $wp_customize->add_setting( 'luxury_jewels_sale_badge_text', [
                'default'           => __( 'Sale!', 'luxury-jewels' ),
                'sanitize_callback' => 'sanitize_text_field',
            ] );
            $wp_customize->add_control( 'luxury_jewels_sale_badge_text_control', [
                'label'    => __( 'Sale Badge Text', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_shop_section',
                'settings' => 'luxury_jewels_sale_badge_text',
                'type'     => 'text',
            ] );
        }

        protected function add_global_tabs() {
            $wp_customize = $this->wp_customize;

            $wp_customize->add_section( 'luxury_jewels_global_tabs_section', [
                'title'       => __( 'Global Product Tabs', 'luxury-jewels' ),
                'priority'    => 160,
                'description' => __( 'These tabs will appear on every product page. Leave a tab\'s title and content blank to hide it.', 'luxury-jewels' ),
                'panel'       => 'luxury_jewels_options_panel',
            ] );

            // Define how many global tabs we want to create
            $number_of_tabs = 3;

            for ( $i = 1; $i <= $number_of_tabs; $i++ ) {

                // --- SEPARATOR (for better UI) ---
                $wp_customize->add_setting( 'luxury_jewels_global_tab_separator_' . $i, ['sanitize_callback' => 'esc_html'] );
                $wp_customize->add_control( new WP_Customize_Control(
                    $wp_customize,
                    'luxury_jewels_global_tab_separator_control_' . $i,
                    [
                        'type'        => 'hidden',
                        'section'     => 'luxury_jewels_global_tabs_section',
                        'settings'    => 'luxury_jewels_global_tab_separator_' . $i,
                        'description' => '<hr><h3>' . sprintf( __( 'Global Tab #%d', 'luxury-jewels' ), $i ) . '</h3>',
                    ]
                ) );

                // --- TITLE ---
                $wp_customize->add_setting( 'luxury_jewels_global_tab_' . $i . '_title', [
                    'default'           => '',
                    'sanitize_callback' => 'sanitize_text_field',
                ] );
                $wp_customize->add_control( 'luxury_jewels_global_tab_' . $i . '_title_control', [
                    'label'    => sprintf( __( 'Tab #%d Title', 'luxury-jewels' ), $i ),
                    'section'  => 'luxury_jewels_global_tabs_section',
                    'settings' => 'luxury_jewels_global_tab_' . $i . '_title',
                    'type'     => 'text',
                ] );

                // --- CONTENT ---
                $wp_customize->add_setting( 'luxury_jewels_global_tab_' . $i . '_content', [
                    'default'           => '',
                    'sanitize_callback' => 'wp_kses_post',
                ] );
                $wp_customize->add_control( 'luxury_jewels_global_tab_' . $i . '_content_control', [
                    'label'    => sprintf( __( 'Tab #%d Content', 'luxury-jewels' ), $i ),
                    'section'  => 'luxury_jewels_global_tabs_section',
                    'settings' => 'luxury_jewels_global_tab_' . $i . '_content',
                    'type'     => 'textarea',
                ] );
            }
        }

        protected function add_swatch_info() {
            $wp_customize = $this->wp_customize;

            // --- Swatch Information Section ---
            $wp_customize->add_section( 'luxury_jewels_swatch_info_section', [
                'title'    => __( 'Attribute Display Types', 'luxury-jewels' ),
                'priority' => 30,
                'panel'    => 'luxury_jewels_options_panel',
            ] );

            // Create a dummy setting for the info control
            $wp_customize->add_setting( 'luxury_jewels_swatch_info_setting', ['sanitize_callback' => 'esc_html'] );

            // Add the control with the descriptive text and link
            $wp_customize->add_control( new WP_Customize_Control(
                $wp_customize,
                'luxury_jewels_swatch_info_control',
                [
                    'section'     => 'luxury_jewels_swatch_info_section',
                    'settings'    => 'luxury_jewels_swatch_info_setting',
                    'type'        => 'hidden',
                    'description' => '<p>' . sprintf(
                        /* translators: %s: URL to the product attributes admin page. */
                        __( 'To set whether an attribute (like Metal or Size) should appear as a color swatch or a button, you must configure it on the <a href="%s" target="_blank">Product Attributes page</a>. From there, you can edit each attribute and set its "Display Type".', 'luxury-jewels' ),
                        esc_url( admin_url( 'edit.php?post_type=product&page=product_attributes' ) )
                    ) . '</p>',
                ]
            ) );
        }
    }
}