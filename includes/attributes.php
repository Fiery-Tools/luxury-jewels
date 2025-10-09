<?php
// In your theme's functions.php or a dedicated include file.

// Ensure the class doesn't exist before declaring it.
if (! class_exists('MyTheme_Attribute_Fields')) {

  class MyTheme_Attribute_Fields
  {

    /**
     * A list to hold all registered product attribute taxonomy names.
     * @var array
     */
    private $attribute_taxonomies = [];

    /**
     * Initialize our hooks.
     */
    public function __construct()
    {
      // Get all product attribute taxonomy objects
      $attribute_taxonomy_objects = wc_get_attribute_taxonomies();

      if (! empty($attribute_taxonomy_objects)) {
        // Loop through all attribute taxonomies to build our list and add hooks for TERM META (swatch color)
        foreach ($attribute_taxonomy_objects as $tax) {

          // THIS IS THE FIX: Use the official WooCommerce function to get the full taxonomy name.
          // This correctly gets "pa_metal" from "metal" without us ever typing "pa_".
          $taxonomy_name = wc_attribute_taxonomy_name($tax->attribute_name);

          // Add the official taxonomy name to our class property for later use
          $this->attribute_taxonomies[] = $taxonomy_name;

          // custom attribute data for swatch color and display type
          add_action($taxonomy_name . '_add_form_fields', array($this, 'add_swatch_color_field'));
          add_action($taxonomy_name . '_edit_form_fields', array($this, 'edit_swatch_color_field'), 10, 2);
          add_action('created_' . $taxonomy_name, array($this, 'save_swatch_color_field'), 10, 2);
          add_action('edited_' . $taxonomy_name, array($this, 'save_swatch_color_field'), 10, 2);

        }
      }

      // Hook to enqueue scripts on the correct admin pages
      add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

      // Hooks for adding and saving the custom "Display Type" field for ATTRIBUTES themselves.
      add_action('woocommerce_after_add_attribute_fields', array($this, 'add_attribute_display_type_field'));
      add_action('woocommerce_after_edit_attribute_fields', array($this, 'edit_attribute_display_type_field'), 10, 1);
      add_action('woocommerce_attribute_added', array($this, 'save_attribute_display_type_field'), 10, 2);
      add_action('woocommerce_attribute_updated', array($this, 'save_attribute_display_type_field'), 10, 2);

      // Attribute-level fields go here
      add_action('woocommerce_after_add_attribute_fields', array($this, 'add_display_in_card_field'));
      add_action('woocommerce_after_edit_attribute_fields', array($this, 'edit_display_in_card_field'));
      add_action('woocommerce_attribute_added', array($this, 'save_display_in_card_field'), 10, 2);
      add_action('woocommerce_attribute_updated', array($this, 'save_display_in_card_field'), 10, 2);

      // Position field for ordering attributes
      add_action('woocommerce_after_add_attribute_fields', array($this, 'add_position_field'));
      add_action('woocommerce_after_edit_attribute_fields', array($this, 'edit_position_field'));
      add_action('woocommerce_attribute_added', array($this, 'save_position_field'), 10, 2);
      add_action('woocommerce_attribute_updated', array($this, 'save_position_field'), 10, 2);
    }

    /**
     * Enqueue scripts only on the correct admin pages for any product attribute.
     */
    public function enqueue_admin_scripts()
    {
      $screen = get_current_screen();

      // This robust check now works perfectly with the corrected taxonomy list.
      if ($screen && ($screen->base === 'term' || $screen->base) && in_array($screen->taxonomy, $this->attribute_taxonomies)) {

        wp_enqueue_style('wp-color-picker');

        wp_enqueue_script(
          'mytheme-admin-script',
          get_template_directory_uri() . '/js/admin-script.js',
          array('jquery', 'wp-color-picker'),
          '1.0.3',
          true
        );
      }
    }


    /**
     * Add "Swatch Color" field to the "Add Term" page for an attribute.
     */
    public function add_swatch_color_field()
    {
    ?>
      <div class="form-field term-color-wrap">
        <label for="term-color"><?php _e('Swatch Color', 'mytheme'); ?></label>
        <input name="_swatch_color" value="#ffffff" class="color-picker" id="term-color" />
        <p><?php _e('The hex code for the swatch. Leave blank if using an image.', 'mytheme'); ?></p>
      </div>
    <?php
    }

    /**
     * Add "Swatch Color" field to the "Edit Term" page for an attribute.
     */
    public function edit_swatch_color_field($term)
    {
      $color = get_term_meta($term->term_id, '_swatch_color', true);
      if (!$color) {
        $color = '#ffffff';
      }
    ?>
      <tr class="form-field term-color-wrap">
        <th scope="row">
          <label for="term-color"><?php _e('Swatch Color', 'mytheme'); ?></label>
        </th>
        <td>
          <input name="_swatch_color" value="<?php echo esc_attr($color); ?>" class="color-picker" id="term-color" />
          <p class="description"><?php _e('The hex code for the swatch. Leave blank if using an image.', 'mytheme'); ?></p>
        </td>
      </tr>
    <?php
    }

    /**
     * Save the "Swatch Color" field value.
     */
    public function save_swatch_color_field($term_id)
    {
      if (isset($_POST['_swatch_color']) && '' !== $_POST['_swatch_color']) {
        update_term_meta($term_id, '_swatch_color', sanitize_hex_color($_POST['_swatch_color']));
      }
    }

    /**
     * Add "Display Type" field to the "Add Attribute" page.
     */
    public function add_attribute_display_type_field()
    {
?>
      <div class="form-field">
        <label for="attribute_display_type"><?php esc_html_e('Display Type', 'luxury-jewels'); ?></label>
        <select name="attribute_display_type" id="attribute_display_type">
          <option value="swatch"><?php esc_html_e('Color Swatch', 'luxury-jewels'); ?></option>
          <option value="button"><?php esc_html_e('Button', 'luxury-jewels'); ?></option>
          <option value="dropdown"><?php esc_html_e('Dropdown', 'luxury-jewels'); ?></option>
        </select>
        <p class="description"><?php esc_html_e('Determines how this attribute is shown on the product page and in filters.', 'luxury-jewels'); ?></p>
      </div>
    <?php
    }

    /**
     * Add "Display Type" field to the "Edit Attribute" page.
     */
    public function edit_attribute_display_type_field()
    {

      if (!isset($_GET['edit'])) {
        return;
      }
      $attribute_id = absint($_GET['edit']);
      $display_type = get_option('luxury_jewels_attribute_display_type_' . $attribute_id, 'swatch');

    ?>
      <tr class="form-field">
        <th scope="row" valign="top">
          <label for="attribute_display_type"><?php esc_html_e('Display Type', 'luxury-jewels'); ?></label>
        </th>
        <td>
          <select name="attribute_display_type" id="attribute_display_type">
            <option value="swatch" <?php selected($display_type, 'swatch'); ?>><?php esc_html_e('Color Swatch', 'luxury-jewels'); ?></option>
            <option value="button" <?php selected($display_type, 'button'); ?>><?php esc_html_e('Button', 'luxury-jewels'); ?></option>
            <option value="dropdown" <?php selected($display_type, 'dropdown'); ?>><?php esc_html_e('Dropdown', 'luxury-jewels'); ?></option>
          </select>
          <p class="description"><?php esc_html_e('Determines how this attribute is shown on the product page and in filters.', 'luxury-jewels'); ?></p>
        </td>
      </tr>
<?php
    }

    /**
     * Save the "Display Type" field value.
     */
    public function save_attribute_display_type_field($attribute_id, $attribute)
    {
      if (isset($_POST['attribute_display_type'])) {
        $display_type = sanitize_key($_POST['attribute_display_type']);
        update_option('luxury_jewels_attribute_display_type_' . $attribute_id, $display_type);
      }
    }

    /**
     * Add "Position" field to the "Add Term" page for an attribute.
     */
    public function add_position_field()
    {
    ?>
      <div class="form-field">
        <label for="attribute_position"><?php _e('Position', 'mytheme'); ?></label>
        <input type="number" name="_position" id="attribute_position" value="0" style="width: 100px;">
        <p class="description"><?php _e('A number to control the display order of this attribute in filters.', 'mytheme'); ?></p>
      </div>
    <?php
    }

    /**
     * Add "Position" field to the "Edit Term" page for an attribute.
     */
    public function edit_position_field()
    {
      if (!isset($_GET['edit'])) {
        return;
      }
      $attribute_id = absint($_GET['edit']);
      $position = get_option('luxury_jewels_attribute_position_' . $attribute_id, 0);
    ?>
      <tr class="form-field">
        <th scope="row" valign="top">
          <label for="attribute_position"><?php _e('Position', 'mytheme'); ?></label>
        </th>
        <td>
          <input type="number" name="_position" value="<?php echo esc_attr($position); ?>" id="attribute_position" style="width: 100px;" />
          <p class="description"><?php _e('A number to control the display order of this attribute in filters.', 'mytheme'); ?></p>
        </td>
      </tr>
    <?php
    }

    /**
     * Save the "Position" field value.
     */
    public function save_position_field($attribute_id)
    {
      if (isset($_POST['_position'])) {
        update_option('luxury_jewels_attribute_position_' . $attribute_id, absint($_POST['_position']));
      }
    }

    /**
     * Add "Display in Product Card" field to the "Add Term" page.
     */
    public function add_display_in_card_field()
    {
    ?>
      <div class="form-field">
        <label for="attribute_display_in_card">
          <input type="checkbox" name="_display_in_card" id="attribute_display_in_card" value="yes" />
          <?php _e('Display in Product Card?', 'mytheme'); ?>
        </label>
        <p class="description"><?php _e('If checked, this attribute will be visible on the shop/archive page product cards.', 'mytheme'); ?></p>
      </div>
    <?php
    }

    /**
     * Add "Display in Product Card" field to the "Edit Term" page.
     */
    public function edit_display_in_card_field()
    {
      if (!isset($_GET['edit'])) {
        return;
      }
      $attribute_id = absint($_GET['edit']);
      $display_in_card = get_option('luxury_jewels_attribute_display_in_card_' . $attribute_id, 'no');
    ?>
      <tr class="form-field">
        <th scope="row" valign="top">
          <label for="attribute_display_in_card"><?php _e('Display in Product Card', 'mytheme'); ?></label>
        </th>
        <td>
          <input type="checkbox" name="_display_in_card" id="attribute_display_in_card" value="yes" <?php checked($display_in_card, 'yes'); ?> />
          <p class="description"><?php _e('If checked, this attribute will be visible on the shop/archive page product cards.', 'mytheme'); ?></p>
        </td>
      </tr>
    <?php
    }

    /**
     * Save the "Display in Product Card" field value.
     */
    public function save_display_in_card_field($attribute_id)
    {
      $value = isset($_POST['_display_in_card']) && $_POST['_display_in_card'] === 'yes' ? 'yes' : 'no';
      update_option('luxury_jewels_attribute_display_in_card_' . $attribute_id, $value);
    }
  }

  /**
   * Instantiate the class on the 'init' hook to ensure all plugins are loaded.
   */
}

function mytheme_instantiate_attribute_fields_class()
{
  new MyTheme_Attribute_Fields();
}

add_action('init', 'mytheme_instantiate_attribute_fields_class');

$luxury_jewels_taxonomies = array_map(function ($taxonomy) {
  return [
    "name" => $taxonomy->attribute_name,
    "label" => $taxonomy->attribute_label,
    "id" => $taxonomy->attribute_id,
    "display_type" => get_option('luxury_jewels_attribute_display_type_' . $taxonomy->attribute_id),
    "position" => get_option('luxury_jewels_attribute_position_' . $taxonomy->attribute_id),
    "display_in_card" => get_option('luxury_jewels_attribute_display_in_card_' . $taxonomy->attribute_id),
  ];
}, wc_get_attribute_taxonomies());

function luxury_jewels_get_taxonomy($taxonomy_name_or_label_or_id)
{
  global $luxury_jewels_taxonomies;

  foreach ($luxury_jewels_taxonomies as $taxonomy) {
    if (
      $taxonomy['name'] == $taxonomy_name_or_label_or_id ||
      $taxonomy['name'] == str_replace('pa_', '', $taxonomy_name_or_label_or_id) ||
      $taxonomy['label'] == $taxonomy_name_or_label_or_id ||
      $taxonomy['label'] == str_replace('pa_', '', $taxonomy_name_or_label_or_id) ||
      $taxonomy['id'] == $taxonomy_name_or_label_or_id
    ) {
      return $taxonomy;
    }
  }

  return null;
}
