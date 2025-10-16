<?php
/**
 * Customizer dynamic CSS generation and output.
 *
 * This file collects all style-related theme mods and outputs them as
 * CSS custom properties and direct CSS rules to the <head>.
 *
 * @package luxury-jewels
 */

if ( ! class_exists( 'Luxury_Jewels_Customizer_Output' ) ) {

    class Luxury_Jewels_Customizer_Output {

        public static function init() {
            add_action( 'wp_head', array( self::class, 'generate_customizer_css' ) );
        }

        /**
         * Safely retrieves a theme modification value.
         *
         * @param string $setting_id The ID of the theme mod setting.
         * @param mixed $default The default value to return.
         * @return mixed
         */
        private static function get_theme_mod_value( $setting_id, $default ) {
            return get_theme_mod( $setting_id, $default );
        }

        /**
         * Generate and output the dynamic CSS with SEMANTIC custom variables.
         */
        public static function generate_customizer_css() {
            $css = '';
            // FIX: Use the original setting ID for backward compatibility
            $settings_map = [
                // Colors
                '--custom-color-accent'       => ['setting' => 'luxury_jewels_color_accent', 'default' => '#D4AF37'],
                '--custom-color-accent-dark'  => ['setting' => 'luxury_jewels_color_accent_dark', 'default' => '#B8941E'],
                '--custom-color-background'   => ['setting' => 'luxury_jewels_color_background', 'default' => '#FAF8F3'],
                '--custom-color-text-headings' => ['setting' => 'luxury_jewels_color_text_headings', 'default' => '#1A1A1A'],
                '--custom-color-secondary'    => ['setting' => 'luxury_jewels_color_secondary', 'default' => '#2C2C2C'], // ADDED Secondary Color
                '--custom-color-text-body'    => ['setting' => 'luxury_jewels_color_text_body', 'default' => '#333333'],
                '--custom-color-primary-cta'  => ['setting' => 'luxury_jewels_color_primary_cta', 'default' => '#D4AF37'],
                '--custom-color-border'       => ['setting' => 'luxury_jewels_color_border', 'default' => '#E5DDD0'],
                '--custom-color-header-bg'    => ['setting' => 'luxury_jewels_color_header_bg', 'default' => '#ffffff'], // FIXED: Reverted to old setting ID

                // Typography
                '--custom-font-headings'      => ['setting' => 'luxury_jewels_font_headings', 'default' => 'Cormorant Garamond', 'is_font' => true, 'suffix' => ", serif"],
                '--custom-font-body'          => ['setting' => 'luxury_jewels_font_body', 'default' => 'Lato', 'is_font' => true, 'suffix' => ", sans-serif"],
            ];

            foreach ( $settings_map as $css_var => $data ) {
                $value = self::get_theme_mod_value( $data['setting'], $data['default'] );

                // Only output CSS variables that have been changed from the theme default.
                if ( strtolower( $value ) !== strtolower( $data['default'] ) ) {
                    if ( ! empty( $data['is_font'] ) ) {
                        // For font names, wrap in quotes
                        $css .= "    {$css_var}: '{$value}'{$data['suffix']};\n";
                    } else {
                        $css .= "    {$css_var}: {$value};\n";
                    }
                }
            }

            // --- Enqueue Google Fonts (Logic from original file) ---
            $heading_font = self::get_theme_mod_value( 'luxury_jewels_font_headings', 'Cormorant Garamond' );
            $body_font    = self::get_theme_mod_value( 'luxury_jewels_font_body', 'Lato' );

            if ( $heading_font !== 'Cormorant Garamond' || $body_font !== 'Lato' ) {
                $heading_font_safe = str_replace( ' ', '+', $heading_font );
                $body_font_safe    = str_replace( ' ', '+', $body_font );
                wp_enqueue_style( 'luxury-jewels-google-fonts', "https://fonts.googleapis.com/css2?family={$heading_font_safe}:wght@400;700&family={$body_font_safe}:wght@400;700&display=swap", [], null );
            }

            // --- Direct CSS Rules (Logo Size) ---
            $logo_size = self::get_theme_mod_value( 'luxury_jewels_logo_size', 180 );
            $direct_css = '';
            if ( $logo_size !== 180 ) {
                $direct_css .= ".site-branding .custom-logo { max-width: {$logo_size}px; }\n";
            }

            // --- Final Output ---
            if ( ! empty( $css ) || ! empty( $direct_css ) ) {
                echo "<style type=\"text/css\" id=\"luxury-jewels-customizer-css\">\n";
                if ( ! empty( $css ) ) {
                    echo ":root {\n" . rtrim( $css ) . "\n}\n";
                }
                if ( ! empty( $direct_css ) ) {
                    echo $direct_css;
                }
                echo "</style>\n";
            }
        }
    }

    Luxury_Jewels_Customizer_Output::init();
}