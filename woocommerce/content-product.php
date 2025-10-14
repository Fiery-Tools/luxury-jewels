<?php

/**
 * @package luxury-jewels
 */

defined('ABSPATH') || exit;

global $product;

if (! is_a($product, WC_Product::class) || ! $product->is_visible()) {
	return;
}
?>
<li <?php wc_product_class('', $product); ?>>
	<?php
	do_action('woocommerce_before_shop_loop_item');
	do_action('woocommerce_before_shop_loop_item_title');
	do_action('woocommerce_shop_loop_item_title');
	do_action('woocommerce_after_shop_loop_item_title');
	// i want to add attribute info here
	$attributes = $product->get_attributes();

	// Check if the product has any attributes
	if (! empty($attributes)) {
		echo '<div class="product-attributes">';
		global $luxury_jewels_taxonomies;



		foreach ($attributes as $attribute) {
			// Skip if this attribute is not meant to be visible on the frontend
			if (! $attribute->get_visible() || $product->get_type() !== 'variable') {
				continue;
			}

			$current_attribute_name = $attribute->get_name();

			$taxonomy_settings_array = array_filter(
				$luxury_jewels_taxonomies,
				function ($tax) use ($current_attribute_name) {
					// The matching condition: does 'pa_' + our custom name match the attribute's name?
					return 'pa_' . $tax['name'] === $current_attribute_name;
				}
			);

			if (! empty($taxonomy_settings_array)) {

				// Get the first (and only) item from the filtered array
				$taxonomy = array_shift($taxonomy_settings_array);

				// NOW, check your custom rule: 'display_in_card'
				if (isset($taxonomy['display_in_card']) && $taxonomy['display_in_card'] === 'yes') {
					$attribute_name = $attribute->get_name();

					switch ($taxonomy['display_type']) {
						case "swatch":

							echo '<div class="swatches" data-attribute_name="attribute_' . esc_attr(sanitize_title($attribute_name)) . '">';

							$terms = wc_get_product_terms($product->get_id(), $attribute->get_name());

							foreach ($terms as $term) {
								$swatch_color = get_term_meta($term->term_id, '_swatch_color', true);

								$link = "";



								if ($swatch_color) {
									echo '<div class="swatch-attribute swatch-color" data-value="' . esc_attr($term->slug) . '" style="background-color:' . esc_attr($swatch_color) . ';" title="' . esc_attr($term->name) . '"></div>';
								} else {
									echo '<div class="swatch-attribute swatch-color" data-value="' . esc_attr($term->slug) . '" style="background-color:' . esc_attr($term->name) . ';" title="' . esc_attr($term->name) . '"></div>';
								}
							}

							echo '</div>';





							break;
						case "button":
							echo '<div class="buttons is-buttons" data-attribute_name="attribute_' . esc_attr(sanitize_title($attribute_name)) . '">';
							// foreach ($terms as $term) {
							// 	$is_active = in_array($term->slug, $current_filters);
							// 	$swatch_color = get_term_meta($term->term_id, '_swatch_color', true);

							// 	if ($is_active) {
							// 		$temp_filters = array_diff($temp_filters, array($term->slug));
							// 	} else {
							// 		$temp_filters[] = $term->slug;
							// 	}

							// 	if (! empty($temp_filters)) {
							// 		$link = add_query_arg($filter_name, implode(',', $temp_filters));
							// 	} else {
							// 		$link = remove_query_arg($filter_name);
							// 	}

							// 	$label = esc_html($term->name);
							// 	$selected = in_array($term->slug, $current_filters) ? " selected" : "";


							// }

							$terms = wc_get_product_terms($product->get_id(), $attribute->get_name());

							foreach ($terms as $term) {
								$link = "";
								echo '<div class="button-attribute sm" data-value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</div>';
							}



							echo '</div>';

							break;
						default:
					}
				}
			}
		}

		echo '</div>';
	}
	do_action('woocommerce_after_shop_loop_item');
	?>
</li>