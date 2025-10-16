<?php
/**
 * Custom template tags for this theme.
 *
 * @package luxury-jewels
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'luxury_jewels_display_payment_icons' ) ) {
	/**
	 * Renders the payment icons list based on Customizer settings.
	 */
	function luxury_jewels_display_payment_icons() {
		if ( ! get_theme_mod( 'luxury_jewels_show_payment_icons', true ) ) {
			return;
		}

		$methods_string = get_theme_mod( 'luxury_jewels_payment_methods', 'applepay,visa,mastercard,paypal,klarna,shop' );

		if ( empty( $methods_string ) ) {
			return;
		}

		// Clean up the string and convert it to an array of payment methods
		$methods = array_filter( array_map( 'trim', explode( ',', $methods_string ) ) );

		if ( ! empty( $methods ) ) : ?>
			<div class="payment-icons-list">
				<?php
				foreach ( $methods as $method ) {
					// Check for a local theme icon first, then fall back to WooCommerce's icons.
					// This allows for custom icons like Klarna or Shop Pay.
					$icon_url = '';
					$method_slug = strtolower( esc_attr( $method ) );

					// Path for local theme icon. e.g., /assets/images/payment/klarna.svg
					$local_icon_path = get_stylesheet_directory() . '/assets/images/icons/' . $method_slug . '.svg';

					if ( file_exists( $local_icon_path ) ) {
						$icon_url = get_stylesheet_directory_uri() . '/assets/images/icons/' . $method_slug . '.svg';
					} else {
						// Fallback to WooCommerce icons.
						$wc_icon_slug = str_replace( 'applepay', 'apple-pay', $method );
						$icon_url     = WC()->plugin_url() . '/assets/images/icons/credit-cards/' . strtolower( esc_attr( $wc_icon_slug ) ) . '.svg';
					}

					$label = ucwords( str_replace( '-', ' ', $method ) );
					?>
					<img
						src="<?php echo esc_url( $icon_url ); ?>"
						alt="<?php echo esc_attr( $label ); ?>"
						loading="lazy"
					/>
					<?php
				}
				?>
			</div>
		<?php
		endif;
	}
}
