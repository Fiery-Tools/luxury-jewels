<?php

/**
 * The sidebar containing custom attribute filters for the shop.
 *
 * This template replaces the standard widget area with a programmatically
 * generated set of filters based on product attributes, ensuring a
 * consistent and powerful filtering experience.
 *
 * @package Luxury_Jewels
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

// Only display these filters on relevant WooCommerce pages.
if (! is_shop() && ! is_product_category() && ! is_product_tag()) {
	return;
}

// Access the global variable with our pre-sorted attribute data.
global $luxury_jewels_taxonomies;

// Display Active Filters widget if there are any active filters.
if (! empty(WC()->query->get_layered_nav_chosen_attributes())) {
	the_widget(
		'WC_Widget_Layered_Nav_Filters',
		array(
			'title' => __('Active Filters', 'luxury-jewels'),
		)
	);
}

// Display the price filter.
// the_widget(
// 	'WC_Widget_Price_Filter',
// 	array(
// 		'title' => __('Filter by Price', 'luxury-jewels'),
// 	)
// );

// Loop through our taxonomies and display custom filter controls directly.
if (! empty($luxury_jewels_taxonomies)) {


	echo '<div class="luxury-jewels-tabs keep-open">';





	foreach ($luxury_jewels_taxonomies as $taxonomy_data) {
		$taxonomy_slug = 'pa_' . $taxonomy_data['name'];

		// Get only terms that are associated with products.
		$terms = get_terms(array(
			'taxonomy'   => $taxonomy_slug,
			'hide_empty' => true,
		));

		// If there are no terms for this attribute, skip it.
		if (empty($terms) || is_wp_error($terms)) {
			continue;
		}

		// Start widget wrapper for consistent styling with other widgets.
		// echo '<section class="widget woocommerce widget_layered_nav">';
		// echo '<h2 class="widget-title">' . esc_html($taxonomy_data['label']) . '</h2>';
		$display_type = $taxonomy_data['display_type'];
		$filter_name  = 'filter_' . $taxonomy_data['name'];
		$current_filters = isset($_GET[$filter_name]) ? explode(',', wc_clean(wp_unslash($_GET[$filter_name]))) : [];

		$is_active = 0;
		foreach($terms as $term){
			if(in_array($term->slug, $current_filters)) $is_active = 1;
		}


?>
		<div class="lj-accordion-item<?php echo $is_active ? ' is-active' : ''; ?>" id="lj-tab-<?php echo $taxonomy_data['id']; ?>">
			<h2 class="lj-accordion-title"><?php echo $taxonomy_data['label']; ?></h2>
			<div class="lj-accordion-content">
				<?php

				// For swatches and buttons, build the custom markup directly.
				$list_class = 'lj-filter-list type-' . esc_attr($display_type);
				echo '<div class="' . esc_attr($list_class) . '">';
				$attribute_name = $taxonomy_data['name'];
				$temp_filters = $current_filters;

				switch ($display_type) {
					case "swatch":

						echo '<div class="swatches" data-attribute_name="attribute_' . esc_attr(sanitize_title($attribute_name)) . '">';

						foreach ($terms as $term) {
							$is_active = in_array($term->slug, $current_filters);
							$swatch_color = get_term_meta($term->term_id, '_swatch_color', true);

							if ($is_active) {
								$temp_filters = array_diff($temp_filters, array($term->slug));
							} else {
								$temp_filters[] = $term->slug;
							}

							if (! empty($temp_filters)) {
								$link = add_query_arg($filter_name, implode(',', $temp_filters));
							} else {
								$link = remove_query_arg($filter_name);
							}

							$label = esc_html($term->name);
							$selected = in_array($term->slug, $current_filters) ? " selected" : "";

							if ($swatch_color) {
								echo '<a href="' . $link . '"><div class="swatch-attribute swatch-color' . $selected . '" data-value="' . esc_attr($term->slug) . '" style="background-color:' . esc_attr($swatch_color) . ';" title="' . esc_attr($term->name) . '"></div></a>';
							} else {
								echo '<a href="' . $link . '"><div class="swatch-attribute swatch-color' . $selected . '" data-value="' . esc_attr($term->slug) . '" style="background-color:' . esc_attr($term->name) . ';" title="' . esc_attr($term->name) . '"></div></a>';
							}
						}

						echo '</div>';





						break;
					case "button":
						echo '<div class="buttons is-buttons" data-attribute_name="attribute_' . esc_attr(sanitize_title($attribute_name)) . '">';
						foreach ($terms as $term) {
							$is_active = in_array($term->slug, $current_filters);
							$swatch_color = get_term_meta($term->term_id, '_swatch_color', true);

							if ($is_active) {
								$temp_filters = array_diff($temp_filters, array($term->slug));
							} else {
								$temp_filters[] = $term->slug;
							}

							if (! empty($temp_filters)) {
								$link = add_query_arg($filter_name, implode(',', $temp_filters));
							} else {
								$link = remove_query_arg($filter_name);
							}

							$label = esc_html($term->name);
							$selected = in_array($term->slug, $current_filters) ? " selected" : "";

							echo '<a href="' . $link . '"><div class="button-attribute ' . $selected . '" data-value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</div></a>';
						}
						echo '</div>';

						break;
					default:
						the_widget('WC_Widget_Layered_Nav', array(
							'title'        => '', // Title is already printed above.
							'attribute'    => $taxonomy_data['name'],
							'query_type'   => 'AND',
							'display_type' => 'dropdown',
						));
						// select
				}


				?>
			</div>
		</div>
		</div>
<?php

	}
	echo '</section>'; // Close widget wrapper.

}
