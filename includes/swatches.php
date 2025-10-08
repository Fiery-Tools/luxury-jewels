<?php
// includes/swatches.php

/**
 * Guesses a hex code from a string (color name).
 *
 * @param string $name The name of the color.
 * @return string The guessed hex code.
 */
function mytheme_guess_hex_from_name($name)
{
  $name  = strtolower($name);
  $colors = [
    'rose gold'    => '#B76E79',
    'silver'       => '#C0C0C0',
    'platinum'     => '#E5E4E2',
    'white gold'   => '#F8F8F8',

    // 16 More common jewelry colors/metals
    'yellow gold'  => '#FFD700',
    'gold'         => '#FFD700', // Alias for yellow gold
    'green gold'   => '#A5C68B',
    'rhodium'      => '#A9ACB0',
    'palladium'    => '#D3D3D3',
    'titanium'     => '#878C90',
    'tungsten'     => '#4D4D4F',
    'steel'        => '#A7A7AD', // For Stainless Steel
    'bronze'       => '#CD7F32',
    'copper'       => '#B87333',
    'brass'        => '#E1C16E',
    'pewter'       => '#6E7271',
    'gunmetal'     => '#53565A',
    'black'        => '#1A1A1A', // For oxidized, carbon fiber, etc.
    'chocolate'    => '#7B3F00', // For brown gold/diamonds
    'champagne'    => '#F7E7CE', // For champagne gold/diamonds

    'black'      => '#000000',
    'white'      => '#FFFFFF',
    'red'        => '#FF0000',
    'green'      => '#008000',
    'blue'       => '#0000FF',
    'yellow'     => '#FFFF00',
    'orange'     => '#FFA500',
    'purple'     => '#800080',
    'pink'       => '#FFC0CB',
    'brown'      => '#A52A2A',
    'silver'     => '#C0C0C0',
    'gray'       => '#808080',
    'grey'       => '#808080',
    'charcoal'   => '#36454F',
    'navy'       => '#000080',
    'maroon'     => '#800000',
    'olive'      => '#808000',
    'teal'       => '#008080',

  ];

  foreach ($colors as $color_name => $hex) {
    //
    if (strpos($name, $color_name) !== false) {
      return $hex;
    }
  }

  return '#E0E0E0'; // Default to light grey
}


/**
 * Converts a custom product attribute into a global taxonomy and terms.
 *
 * @param WC_Product $product The product object.
 * @param string $attribute_name The raw name of the attribute (e.g., "Colour").
 * @param array $options The values for the attribute (e.g., ['Block Leaf', 'White']).
 * @return array An array of term objects for immediate use.
 */
function mytheme_handle_custom_attribute_swatches($product, $attribute_name, $options)
{

  $attribute_slug = sanitize_title($attribute_name);
  $taxonomy_name  = 'pa_' . $attribute_slug;

  // get the taxonomy if it exists
  $existing_taxonomy = get_taxonomy($taxonomy_name);

  if (! taxonomy_exists($taxonomy_name)) {
    wc_create_attribute([
      'name'         => $attribute_name,
      'slug'         => $attribute_slug,
      'type'         => 'select',
      'order_by'     => 'menu_order',
      'has_archives' => false,
    ]);

    // Register the taxonomy immediately
    register_taxonomy(
      $taxonomy_name,
      'product',
      [
        'hierarchical' => true,
        'label'        => $attribute_name,
        'query_var'    => true,
        'rewrite'      => false,
      ]
    );
  }

  // 2. Create terms from the options
  foreach ($options as $option) {
    $term = get_term_by('name', $option, $taxonomy_name);
    if (! term_exists($option, $taxonomy_name)) {
      $term_result = wp_insert_term($option, $taxonomy_name);
      if (! is_wp_error($term_result)) {
        // Save our best guess for the color
        $guessed_color = mytheme_guess_hex_from_name($option);
        update_term_meta($term_result['term_id'], '_swatch_color', $guessed_color);
      }
    } else if(empty(get_term_meta( $term->term_id, '_swatch_color', true ))){
      // If the term exists but has no color, try to guess and set it
      $guessed_color = mytheme_guess_hex_from_name($option);
      update_term_meta($term->term_id, '_swatch_color', $guessed_color);
    }
  }

  // 3. Update the product to use the new global attribute instead of the custom one
  $product_attributes = $product->get_attributes();

  // Remove the old custom attribute
  unset($product_attributes[$attribute_name]);

  // Create and set the new global attribute
  $new_attribute = new WC_Product_Attribute();
  $new_attribute->set_id(wc_attribute_taxonomy_id_by_name($taxonomy_name));
  $new_attribute->set_name($taxonomy_name);
  $new_attribute->set_options($options); // The term names
  $new_attribute->set_position(0);
  $new_attribute->set_visible(true);
  $new_attribute->set_variation(true);

  $product_attributes[] = $new_attribute;
  $product->set_attributes($product_attributes);
  $product->save();

  // 4. Return the newly created terms so we can display them on this page load
  return wc_get_product_terms($product->get_id(), $taxonomy_name, ['fields' => 'all']);
}
