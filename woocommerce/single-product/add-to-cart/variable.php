<?php
// your-theme/woocommerce/single-product/add-to-cart/variable.php
defined('ABSPATH') || exit;

global $product;

$attribute_keys  = array_keys($attributes);
$variations_json = wp_json_encode($available_variations);
$variations_attr = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);

do_action('woocommerce_before_add_to_cart_form'); ?>

<form class="variations_form cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint($product->get_id()); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok.
																																																																																																																																											?>">
	<?php do_action('woocommerce_before_variations_form'); ?>

	<?php if (empty($available_variations) && false !== $available_variations) : ?>
		<p class="stock out-of-stock"><?php echo esc_html(apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock and unavailable.', 'woocommerce'))); ?></p>
	<?php else : ?>
		<div class="variations">
			<?php foreach ($attributes as $attribute_name => $options) : ?>
				<div class="variation-row">
					<div class="label">
						<label for="<?php echo esc_attr(sanitize_title($attribute_name)); ?>"><?php echo wc_attribute_label($attribute_name);																																									?>:</label> <span class="selected-variation-name"></span>
					</div>
					<div class="value">
						<?php
						// Hide the original dropdown
						wc_dropdown_variation_attribute_options([
							'options'   => $options,
							'attribute' => $attribute_name,
							'product'   => $product,
							'class'     => 'hidden-select'
						]);

						// Get terms to display as swatches
						$terms_to_display = [];

						$attribute_slug = sanitize_title($attribute_name);
						$taxonomy_name  = 'pa_' . $attribute_slug;
						if(strstr($taxonomy_name, 'pa_pa')){
							continue;
						}

						$existing_taxonomy = get_taxonomy($taxonomy_name);

						if (taxonomy_exists($taxonomy_name)) {
							$terms_to_display = get_terms([
								'taxonomy'   => $taxonomy_name,
								'slug'       => $options,       // Pass the entire array of slugs here
								'hide_empty' => false,          // Important: ensures terms are found even if only used by this variation
								'orderby'    => 'include',      // Optional: keeps the order of the $options array
							]);
						}

						if (count($terms_to_display) < count($options)) {
							// It's a custom attribute. Call our sophisticated function to handle it.
							$terms_to_display = mytheme_handle_custom_attribute_swatches($product, $attribute_name, $options);
						}

						if (! empty($terms_to_display)) {
							echo '<div class="swatches" data-attribute_name="attribute_' . esc_attr(sanitize_title($attribute_name)) . '">';
							foreach ($terms_to_display as $term) {

								// Use our custom color field
								$swatch_color = get_term_meta($term->term_id, '_swatch_color', true);

								// NOTE: You can extend this to check for an image meta field first if you have one.
								// $swatch_image_id = get_term_meta( $term->term_id, 'image_id', true );

								if ($swatch_color) {
									echo '<div class="swatch swatch-color" data-value="' . esc_attr($term->slug) . '" style="background-color:' . esc_attr($swatch_color) . ';" title="' . esc_attr($term->name) . '"></div>';
								} else {
									echo '<div class="swatch swatch-label" data-value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</div>';
								}
							}
							echo '</div>';
						}
						?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="single_variation_wrap">
			<?php
			do_action('woocommerce_before_single_variation');
			do_action('woocommerce_single_variation');
			do_action('woocommerce_after_single_variation');
			?>
		</div>
	<?php endif; ?>

	<?php do_action('woocommerce_after_variations_form'); ?>
</form>

<?php do_action('woocommerce_after_add_to_cart_form'); ?>