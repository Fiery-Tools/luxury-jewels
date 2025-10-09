<?php

/**
 * Luxury Jewels Theme Customizer functionality (Semantic Version)
 *
 * @package LuxuryJewelsTheme
 */

/**
 * Register all theme options in the WordPress Customizer.
 */
function luxury_jewels_customize_register($wp_customize)
{

  $luxury_jewels_palette = ['#D4AF37', '#B8941E', '#E8D4A0', '#1A1A1A', '#2C2C2C', '#FAF8F3', '#333333', '#666666', '#E5DDD0'];

  $wp_customize->add_panel('luxury_jewels_options_panel', [
    'title' => __('Luxury Theme Options', 'luxury-jewels'),
    'priority' => 10,
  ]);







  // --- Header Options Section (NEW) ---
  $wp_customize->add_section('luxury_jewels_header_section', [
    'title' => __('Header & Navigation', 'luxury-jewels'),
    'priority' => 15,
    'panel' => 'luxury_jewels_options_panel',
  ]);

  $wp_customize->add_setting('luxury_jewels_logo_size', [
    'default' => 180,
    'sanitize_callback' => 'absint',
    'transport' => 'refresh',
  ]);
  $wp_customize->add_control('luxury_jewels_logo_size_control', [
    'label' => __('Logo Max Width (px)', 'luxury-jewels'),
    'section' => 'luxury_jewels_header_section',
    'settings' => 'luxury_jewels_logo_size',
    'type' => 'range',
    'input_attrs' => ['min' => 50, 'max' => 400, 'step' => 5],
  ]);

  $wp_customize->add_setting('luxury_jewels_header_layout', [
    'default' => 'default',
    'sanitize_callback' => 'sanitize_key',
  ]);
  $wp_customize->add_control('luxury_jewels_header_layout_control', [
    'label' => __('Header Layout', 'luxury-jewels'),
    'section' => 'luxury_jewels_header_section',
    'settings' => 'luxury_jewels_header_layout',
    'type' => 'radio',
    'choices' => [
      'default' => __('Logo Left, Navigation Right', 'luxury-jewels'),
      'centered' => __('Logo Centered, Navigation Below', 'luxury-jewels'),
    ],
  ]);

  $wp_customize->add_setting('luxury_jewels_sticky_header', [
    'default' => 0,
    'sanitize_callback' => 'absint',
  ]);
  $wp_customize->add_control('luxury_jewels_sticky_header_control', [
    'label' => __('Enable Sticky Header on Scroll', 'luxury-jewels'),
    'section' => 'luxury_jewels_header_section',
    'settings' => 'luxury_jewels_sticky_header',
    'type' => 'checkbox',
  ]);



  // --- Brand Colors Section ---
  $wp_customize->add_section('luxury_jewels_colors_section', [
    'title' => __('Brand Colors', 'luxury-jewels'),
    'priority' => 10,
    'panel' => 'luxury_jewels_options_panel',
  ]);

  // Semantic setting IDs and user-friendly labels
  $wp_customize->add_setting('luxury_jewels_color_accent', ['default' => '#D4AF37', 'sanitize_callback' => 'sanitize_hex_color']);
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'luxury_jewels_color_accent_control', [
    'label' => __('Accent Color (Buttons, Highlights)', 'luxury-jewels'),
    'section' => 'luxury_jewels_colors_section',
    'settings' => 'luxury_jewels_color_accent',
    'palettes' => $luxury_jewels_palette,
  ]));

  $wp_customize->add_setting('luxury_jewels_color_accent_dark', ['default' => '#B8941E', 'sanitize_callback' => 'sanitize_hex_color']);
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'luxury_jewels_color_accent_dark_control', [
    'label' => __('Accent Dark (Prices, Hover States)', 'luxury-jewels'),
    'section' => 'luxury_jewels_colors_section',
    'settings' => 'luxury_jewels_color_accent_dark',
    'palettes' => $luxury_jewels_palette,
  ]));


  $wp_customize->add_setting('luxury_jewels_color_primary_cta', [
    'default' => '#D4AF37', // Default to your existing accent color
    'sanitize_callback' => 'sanitize_hex_color',
  ]);
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'luxury_jewels_color_primary_cta_control', [
    'label' => __('Primary Action Color (Add to Cart)', 'luxury-jewels'),
    'description' => __('The most important button on the site.', 'luxury-jewels'),
    'section' => 'luxury_jewels_colors_section',
    'settings' => 'luxury_jewels_color_primary_cta',
    'palettes' => $luxury_jewels_palette,
  ]));

  $wp_customize->add_setting('luxury_jewels_color_background', ['default' => '#FAF8F3', 'sanitize_callback' => 'sanitize_hex_color']);
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'luxury_jewels_color_background_control', [
    'label' => __('Site Background Color', 'luxury-jewels'),
    'section' => 'luxury_jewels_colors_section',
    'settings' => 'luxury_jewels_color_background',
    'palettes' => $luxury_jewels_palette,
  ]));

  $wp_customize->add_setting('luxury_jewels_color_text_headings', ['default' => '#1A1A1A', 'sanitize_callback' => 'sanitize_hex_color']);
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'luxury_jewels_color_text_headings_control', [
    'label' => __('Headings & Titles Color', 'luxury-jewels'),
    'section' => 'luxury_jewels_colors_section',
    'settings' => 'luxury_jewels_color_text_headings',
    'palettes' => $luxury_jewels_palette,
  ]));

  $wp_customize->add_setting('luxury_jewels_color_text_body', ['default' => '#333333', 'sanitize_callback' => 'sanitize_hex_color']);
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'luxury_jewels_color_text_body_control', [
    'label' => __('Body Text Color', 'luxury-jewels'),
    'section' => 'luxury_jewels_colors_section',
    'settings' => 'luxury_jewels_color_text_body',
    'palettes' => $luxury_jewels_palette,
  ]));

  $wp_customize->add_setting('luxury_jewels_color_border', ['default' => '#E5DDD0', 'sanitize_callback' => 'sanitize_hex_color']);
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'luxury_jewels_color_border_control', [
    'label' => __('Border Color', 'luxury-jewels'),
    'section' => 'luxury_jewels_colors_section',
    'settings' => 'luxury_jewels_color_border',
    'palettes' => $luxury_jewels_palette,
  ]));

  // --- Typography Section ---
  $wp_customize->add_section('luxury_jewels_typography_section', [
    'title' => __('Typography', 'luxury-jewels'),
    'priority' => 20,
    'panel' => 'luxury_jewels_options_panel',
  ]);

  $wp_customize->add_setting('luxury_jewels_font_headings', ['default' => 'Cormorant Garamond', 'sanitize_callback' => 'sanitize_text_field']);
  $wp_customize->add_control('luxury_jewels_font_headings_control', [
    'label' => __('Heading Font (Google Fonts)', 'luxury-jewels'),
    'section' => 'luxury_jewels_typography_section',
    'settings' => 'luxury_jewels_font_headings',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('luxury_jewels_font_body', ['default' => 'Lato', 'sanitize_callback' => 'sanitize_text_field']);
  $wp_customize->add_control('luxury_jewels_font_body_control', [
    'label' => __('Body Font (Google Fonts)', 'luxury-jewels'),
    'section' => 'luxury_jewels_typography_section',
    'settings' => 'luxury_jewels_font_body',
    'type' => 'text',
  ]);

  $wp_customize->add_setting('luxury_jewels_color_header_bg', ['default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color']);
  $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'luxury_jewels_color_header_bg_control', [
    'label' => __('Header & Footer Background', 'luxury-jewels'),
    'section' => 'luxury_jewels_colors_section',
    'settings' => 'luxury_jewels_color_header_bg',
    'palettes' => $luxury_jewels_palette,
  ]));

  // --- Footer Section ---
  $wp_customize->add_section('luxury_jewels_footer_section', [
    'title' => __('Footer Options', 'luxury-jewels'),
    'priority' => 40,
    'panel' => 'luxury_jewels_options_panel',
  ]);

  // Control for Footer Widget Columns
  $wp_customize->add_setting('luxury_jewels_footer_widget_columns', [
    'default' => 4,
    'sanitize_callback' => 'absint',
  ]);

  $wp_customize->add_control('luxury_jewels_footer_widget_columns_control', [
    'label' => __('Footer Widget Columns', 'luxury-jewels'),
    'section' => 'luxury_jewels_footer_section',
    'settings' => 'luxury_jewels_footer_widget_columns',
    'type' => 'select',
    'choices' => [
      '1' => __('1 Column', 'luxury-jewels'),
      '2' => __('2 Columns', 'luxury-jewels'),
      '3' => __('3 Columns', 'luxury-jewels'),
      '4' => __('4 Columns', 'luxury-jewels'),
    ],
  ]);

  $wp_customize->add_setting('luxury_jewels_copyright_text', [
    'default' => 'Copyright &copy; ' . date('Y') . ' Luxury Jewels. All Rights Reserved.',
    'sanitize_callback' => 'wp_kses_post', // Allows basic HTML like links
  ]);

  $wp_customize->add_control('luxury_jewels_copyright_text_control', [
    'label' => __('Copyright Text', 'luxury-jewels'),
    'section' => 'luxury_jewels_footer_section',
    'settings' => 'luxury_jewels_copyright_text',
    'type' => 'textarea',
  ]);

  // --- Shop Layout Section (NEW) ---
  $wp_customize->add_section('luxury_jewels_shop_section', [
    'title' => __('Shop & Product Pages', 'luxury-jewels'),
    'priority' => 25,
    'panel' => 'luxury_jewels_options_panel',
  ]);

  $wp_customize->add_setting('luxury_jewels_shop_columns', [
    'default' => 3,
    'sanitize_callback' => 'absint',
  ]);

  $wp_customize->add_control('luxury_jewels_shop_columns_control', [
    'label' => __('Products Per Row on Shop Page', 'luxury-jewels'),
    'section' => 'luxury_jewels_shop_section',
    'settings' => 'luxury_jewels_shop_columns',
    'type' => 'select',
    'choices' => [
      '2' => __('2 Columns', 'luxury-jewels'),
      '3' => __('3 Columns', 'luxury-jewels'),
      '4' => __('4 Columns', 'luxury-jewels'),
    ],
  ]);

  $wp_customize->add_setting('luxury_jewels_shop_sidebar_layout', [
    'default' => 'no-sidebar',
    'sanitize_callback' => 'sanitize_key',
  ]);

  $wp_customize->add_control('luxury_jewels_shop_sidebar_layout_control', [
    'label' => __('Shop & Archive Sidebar', 'luxury-jewels'),
    'section' => 'luxury_jewels_shop_section',
    'settings' => 'luxury_jewels_shop_sidebar_layout',
    'type' => 'radio',
    'choices' => [
      'no-sidebar'    => __('No Sidebar', 'luxury-jewels'),
      'left-sidebar'  => __('Left Sidebar', 'luxury-jewels'),
      'right-sidebar' => __('Right Sidebar', 'luxury-jewels'),
    ],
  ]);

  // Control for Sale Badge Text
  $wp_customize->add_setting('luxury_jewels_sale_badge_text', [
    'default' => __('Sale!', 'luxury-jewels'),
    'sanitize_callback' => 'sanitize_text_field',
  ]);

  $wp_customize->add_control('luxury_jewels_sale_badge_text_control', [
    'label' => __('Sale Badge Text', 'luxury-jewels'),
    'section' => 'luxury_jewels_shop_section',
    'settings' => 'luxury_jewels_sale_badge_text',
    'type' => 'text',
  ]);




  $wp_customize->add_section('luxury_jewels_global_tabs_section', array(
    'title'       => __('Global Product Tabs', 'luxury-jewels'),
    'priority'    => 160,
    'description' => __('These tabs will appear on every product page. Leave a tab\'s title and content blank to hide it.', 'luxury-jewels'),
  ));

  // Define how many global tabs we want to create
  $number_of_tabs = 3;

  // Loop to create settings and controls for each of the 3 tabs
  for ($i = 1; $i <= $number_of_tabs; $i++) {

    // --- SEPARATOR (for better UI) ---
    $wp_customize->add_setting('global_tab_separator_' . $i, array('sanitize_callback' => 'esc_html'));
    $wp_customize->add_control(new WP_Customize_Control(
      $wp_customize,
      'global_tab_separator_control_' . $i,
      array(
        'type'     => 'hidden', // We just need it to output HTML
        'section'  => 'luxury_jewels_global_tabs_section',
        'settings' => 'global_tab_separator_' . $i,
        'description' => '<hr><h3>' . sprintf(__('Global Tab #%d', 'luxury-jewels'), $i) . '</h3>',
      )
    ));

    // --- TITLE ---
    $wp_customize->add_setting('global_tab_' . $i . '_title', array(
      'default'           => '',
      'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('global_tab_' . $i . '_title_control', array(
      'label'       => sprintf(__('Tab #%d Title', 'luxury-jewels'), $i),
      'section'     => 'luxury_jewels_global_tabs_section',
      'settings'    => 'global_tab_' . $i . '_title',
      'type'        => 'text',
    ));

    // --- CONTENT ---
    $wp_customize->add_setting('global_tab_' . $i . '_content', array(
      'default'           => '',
      'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('global_tab_' . $i . '_content_control', array(
      'label'       => sprintf(__('Tab #%d Content', 'luxury-jewels'), $i),
      'section'     => 'luxury_jewels_global_tabs_section',
      'settings'    => 'global_tab_' . $i . '_content',
      'type'        => 'textarea',
    ));
  }
}
add_action('customize_register', 'luxury_jewels_customize_register');


/**
 * Generate and output the dynamic CSS with SEMANTIC custom variables.
 */
function luxury_jewels_generate_customizer_css()
{
  $css = '';

  // Map CSS custom properties to their Customizer settings and defaults
  // Find the $settings_map array in luxury_jewels_generate_customizer_css()

  $settings_map = [
    '--custom-color-accent'       => ['setting' => 'luxury_jewels_color_accent', 'default' => '#D4AF37'],
    '--custom-color-accent-dark'  => ['setting' => 'luxury_jewels_color_accent_dark', 'default' => '#B8941E'],
    '--custom-color-background'   => ['setting' => 'luxury_jewels_color_background', 'default' => '#FAF8F3'],
    '--custom-color-text-headings' => ['setting' => 'luxury_jewels_color_text_headings', 'default' => '#1A1A1A'],
    '--custom-color-text-body'    => ['setting' => 'luxury_jewels_color_text_body', 'default' => '#333333'],
    '--custom-color-primary-cta'  => ['setting' => 'luxury_jewels_color_primary_cta', 'default' => '#D4AF37'],
    '--custom-color-border'       => ['setting' => 'luxury_jewels_color_border', 'default' => '#E5DDD0'],
    '--custom-color-header-bg'    => ['setting' => 'luxury_jewels_color_header_bg', 'default' => '#ffffff'], // <-- ADD THIS LINE
    '--custom-font-headings'      => ['setting' => 'luxury_jewels_font_headings', 'default' => 'Cormorant Garamond', 'is_font' => true, 'suffix' => ", serif"],
    '--custom-font-body'          => ['setting' => 'luxury_jewels_font_body', 'default' => 'Lato', 'is_font' => true, 'suffix' => ", sans-serif"],
  ];



  foreach ($settings_map as $css_var => $data) {
    $value = get_theme_mod($data['setting'], $data['default']);

    if (strtolower($value) !== strtolower($data['default'])) {
      if (! empty($data['is_font'])) {
        $css .= "    {$css_var}: '{$value}'{$data['suffix']};\n";
      } else {
        $css .= "    {$css_var}: {$value};\n";
      }
    }
  }

  // Enqueue Google Fonts if they have been changed
  $heading_font = get_theme_mod('luxury_jewels_font_headings', 'Cormorant Garamond');
  $body_font    = get_theme_mod('luxury_jewels_font_body', 'Lato');
  if ($heading_font !== 'Cormorant Garamond' || $body_font !== 'Lato') {
    $heading_font_safe = str_replace(' ', '+', $heading_font);
    $body_font_safe    = str_replace(' ', '+', $body_font);
    wp_enqueue_style('my-theme-google-fonts', "https://fonts.googleapis.com/css2?family={$heading_font_safe}:wght@400;700&family={$body_font_safe}:wght@400;700&display=swap", [], null);
  }

  // --- Direct CSS Rules (NEW for Logo Size) ---
  $logo_size = get_theme_mod('luxury_jewels_logo_size', 180);
  $direct_css = '';
  if ($logo_size !== 180) {
    $direct_css .= ".site-branding .custom-logo { max-width: {$logo_size}px; }\n";
  }

  // --- Final Output ---
  if (! empty($css) || ! empty($direct_css)) {
    echo "<style type=\"text/css\" id=\"luxury-jewels-customizer-css\">\n";
    if (! empty($css)) {
      echo ":root {\n" . rtrim($css) . "\n}\n";
    }
    if (! empty($direct_css)) {
      echo $direct_css;
    }
    echo "</style>\n";
  }
}
add_action('wp_head', 'luxury_jewels_generate_customizer_css');
