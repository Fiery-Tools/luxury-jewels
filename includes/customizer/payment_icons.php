<?php
/**
 * Customizer settings for Payment Icons (Footer).
 *
 * @package luxury-jewels
 */

if ( ! class_exists( 'Luxury_Jewels_Customizer_Payment_Icons' ) ) {

    class Luxury_Jewels_Customizer_Payment_Icons {

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

            // --- Payment Icons Section ---
            $wp_customize->add_section( 'luxury_jewels_payment_icons_section', [
                'title'    => __( 'Payment Icons (Footer)', 'luxury-jewels' ),
                'priority' => 130,
                'panel'    => 'luxury_jewels_options_panel', // Add it to the main panel
            ] );

            // Setting: Enable/Disable payment icons
            $wp_customize->add_setting( 'luxury_jewels_show_payment_icons', [
                'default'           => true,
                'sanitize_callback' => 'absint',
            ] );

            $wp_customize->add_control( 'luxury_jewels_show_payment_icons_control', [
                'label'    => __( 'Show Payment Icons in Footer', 'luxury-jewels' ),
                'section'  => 'luxury_jewels_payment_icons_section',
                'settings' => 'luxury_jewels_show_payment_icons',
                'type'     => 'checkbox',
            ] );

            // Setting: Payment methods to display (using comma separated text field)
            $wp_customize->add_setting( 'luxury_jewels_payment_methods', [
                'default'           => 'alipay,american-express,bank-account,discover,jcb,mastercard,rupay,unionpay,wechat-pay,amazon,apple-pay,bitcoin,diners-club,eftpos,google-pay,maestro,paypal,shop-pay,visa',
                'sanitize_callback' => 'sanitize_text_field',
            ] );




            $wp_customize->add_control( 'luxury_jewels_payment_methods_control', [
                'label'       => __( 'Payment Methods (comma separated)', 'luxury-jewels' ),
                'description' => __( 'Example: visa,mastercard,paypal. Use custom images or WooCommerce asset names.', 'luxury-jewels' ),
                'section'     => 'luxury_jewels_payment_icons_section',
                'settings'    => 'luxury_jewels_payment_methods',
                'type'        => 'text',
            ] );
        }
    }
}


