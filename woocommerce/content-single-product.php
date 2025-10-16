<?php
/**
 * The template for displaying product content in the single-product.php template
 * (Corrected Version)
 */

defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
<div class="breadcrumbs product-top">
    <?php woocommerce_breadcrumb(); ?>
</div>

<div class="product-nav-container product-top">
    <?php luxury_jewels_product_nav(); ?>
</div>

    <div class="product-gallery-column">
        <?php
        /**
         * Hook: woocommerce_before_single_product_summary.
         */
        do_action( 'woocommerce_before_single_product_summary' );
        ?>
    </div>

    <div class="product-info-column">
        <?php
        /**
         * Hook: woocommerce_single_product_summary.
         */
        woocommerce_template_single_meta(); // Display meta (SKU, categories) first.
        do_action( 'woocommerce_single_product_summary' );
        luxury_jewels_render_custom_accordion();
        ?>


    </div>


	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 *
	 * =========================================================================
	 * == SOLUTION: The hook is now placed here, inside the main product div ==
	 * =========================================================================
	 */

	?>



</div>
<?php do_action( 'woocommerce_after_single_product_summary' ); ?>


<?php do_action( 'woocommerce_after_single_product' ); ?>
