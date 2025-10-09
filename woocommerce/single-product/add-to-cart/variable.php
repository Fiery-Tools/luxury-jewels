<?php
// your-theme/woocommerce/single-product/add-to-cart/variable.php
defined('ABSPATH') || exit;

global $product;

$attribute_keys  = array_keys($attributes);
$variations_json = wp_json_encode($available_variations);
$variations_attr = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);
$taxonomies = wc_get_attribute_taxonomies();
/*
$taxonomies looks like:
array(1)
id:3 =
stdClass
attribute_id =
"3"
attribute_name =
"metal"
attribute_label =
"Metal"
attribute_type =
"select"
attribute_orderby =
"menu_order"
attribute_public =
0
*/

do_action('woocommerce_before_add_to_cart_form'); ?>

<form class="variations_form cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint($product->get_id()); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok.
																																																																																																																																											?>">
	<?php do_action('woocommerce_before_variations_form'); ?>

	<?php if (empty($available_variations) && false !== $available_variations) : ?>
		<p class="stock out-of-stock"><?php echo esc_html(apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock and unavailable.', 'woocommerce'))); ?></p>
	<?php else : ?>
		<div class="variations">
			<?php
			uksort($attributes, function ($a, $b) use ($taxonomies) {
				$a_slug = str_replace('pa_', '', $a);
				$b_slug = str_replace('pa_', '', $b);

				$a_obj = null;
				foreach ($taxonomies as $tax) {
					if ($tax->attribute_name === $a_slug) {
						$a_obj = $tax;
						break;
					}
				}

				$b_obj = null;
				foreach ($taxonomies as $tax) {
					if ($tax->attribute_name === $b_slug) {
						$b_obj = $tax;
						break;
					}
				}

				$a_id = $a_obj ? $a_obj->attribute_id : 0;
				$b_id = $b_obj ? $b_obj->attribute_id : 0;
				$a_pos = $a_id ? (int) get_option('luxury_jewels_attribute_position_' . $a_id, 0) : 0;
				$b_pos = $b_id ? (int) get_option('luxury_jewels_attribute_position_' . $b_id, 0) : 0;
				return $a_pos <=> $b_pos;
			});

			?>
			<?php foreach ($attributes as $attribute_name => $options) : ?>
				<div class="variation-row">
					<legend class="label"><?php echo wc_attribute_label($attribute_name); ?>: <span class="selected-variation-name"></span></legend>


					<div class="value">
						<?php
						// $taxonomy = $taxonom
						$attr = luxury_jewels_get_taxonomy($attribute_name);

						// $attribute_object = null;
						// foreach ($taxonomies as $tax) {
						// 	if ($tax->attribute_name === $attribute_slug) {
						// 		$attribute_object = $tax;
						// 		break;
						// 	}
						// }

						// // Default to swatch to keep existing behavior for custom attributes that get auto-converted.
						// $display_type = 'swatch';
						// if ($attribute_object) {
						// 	$display_type = get_option('luxury_jewels_attribute_display_type_' . $attribute_object->attribute_id, 'swatch');
						// }

						switch ($attr["display_type"]) {
							case "swatch":
								wc_dropdown_variation_attribute_options([
									'options'   => $options,
									'attribute' => $attribute_name,
									'product'   => $product,
									'class'     => 'hidden-select'
								]);

								// Get terms to display as swatches
								$terms_to_display = [];
								if (taxonomy_exists($attribute_name)) {
									$terms_to_display = get_terms([
										'taxonomy'   => $attribute_name,
										'slug'       => $options,
										'hide_empty' => false,
										'orderby'    => 'include',
									]);
								}

								if (!empty($terms_to_display)) {
									echo '<div class="swatches" data-attribute_name="attribute_' . esc_attr(sanitize_title($attribute_name)) . '">';
									foreach ($terms_to_display as $term) {
										$swatch_color = get_term_meta($term->term_id, '_swatch_color', true);

										if ($swatch_color) {
											echo '<div class="swatch-attribute swatch-color" data-value="' . esc_attr($term->slug) . '" style="background-color:' . esc_attr($swatch_color) . ';" title="' . esc_attr($term->name) . '"></div>';
										} else {
											echo '<div class="swatch-attribute swatch-color" data-value="' . esc_attr($term->slug) . '" style="background-color:' . esc_attr($term->name) . ';" title="' . esc_attr($term->name) . '"></div>';

										}
									}
									echo '</div>';
								}
								break;
							case "button":
								wc_dropdown_variation_attribute_options([
									'options'   => $options,
									'attribute' => $attribute_name,
									'product'   => $product,
									'class'     => 'hidden-select'
								]);

								// Get terms to display as buttons
								$terms_to_display = [];
								if (taxonomy_exists($attribute_name)) {
									$terms_to_display = get_terms([
										'taxonomy'   => $attribute_name,
										'slug'       => $options,
										'hide_empty' => false,
										'orderby'    => 'include',
									]);
								}

								if (!empty($terms_to_display)) {
									echo '<div class="buttons is-buttons" data-attribute_name="attribute_' . esc_attr(sanitize_title($attribute_name)) . '">';
									foreach ($terms_to_display as $term) {
										echo '<div class="button-attribute" data-value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</div>';
									}
									echo '</div>';
								}
								break;
							default:
								wc_dropdown_variation_attribute_options([
									'options'   => $options,
									'attribute' => $attribute_name,
									'product'   => $product,
								]);
								break;
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
