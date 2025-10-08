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
        // Loop through all attribute taxonomies to build our list and add hooks
        foreach ($attribute_taxonomy_objects as $tax) {

          // THIS IS THE FIX: Use the official WooCommerce function to get the full taxonomy name.
          // This correctly gets "pa_metal" from "metal" without us ever typing "pa_".
          $taxonomy_name = wc_attribute_taxonomy_name($tax->attribute_name);

          // Add the official taxonomy name to our class property for later use
          $this->attribute_taxonomies[] = $taxonomy_name;

                          add_action( $taxonomy_name . '_add_form_fields', array( $this, 'add_attribute_display_type_field' ) );
                add_action( $taxonomy_name . '_edit_form_fields', array( $this, 'edit_attribute_display_type' ), 10, 2 );
                add_action( 'created_' . $taxonomy_name, array( $this, 'save_attribute_display_type' ) );
                add_action( 'edited_' . $taxonomy_name, array( $this, 'save_attribute_display_type' ) );

        }
      }



      // Hook to enqueue scripts on the correct admin pages
      add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

    }

    /**
     * Enqueue scripts only on the correct admin pages for any product attribute.
     */
    public function enqueue_admin_scripts($hook_suffix)
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
    public function edit_attribute_display_type_field($attribute)
    {
      $attribute_id = $attribute->attribute_id;
      $display_type = get_option('lj_attribute_display_type_' . $attribute_id, 'swatch');
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
    public function save_attribute_display_type($attribute_id, $attribute)
    {
      if (isset($_POST['attribute_display_type'])) {
        $display_type = sanitize_key($_POST['attribute_display_type']);
        update_option('lj_attribute_display_type_' . $attribute_id, $display_type);
      }
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
