<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package luxury-jewels
 */

?>
</div><!-- #content -->
<div class="luxury-jewels-container">
    <footer id="colophon" class="site-footer">
        <?php
        // Get the number of columns selected in the Customizer.
        $footer_columns = get_theme_mod('luxury_jewels_footer_widget_columns', 4);

        // Check if any of the selected footer sidebars are active.
        $is_any_sidebar_active = false;
        if ($footer_columns > 0) {
            for ($i = 1; $i <= $footer_columns; $i++) {
                if (is_active_sidebar('footer-' . $i)) {
                    $is_any_sidebar_active = true;
                    break;
                }
            }
        }
        ?>

        <?php if ($is_any_sidebar_active) : ?>
            <div class="footer-widgets">
                <?php
                // Loop through and display the active widget columns.
                for ($i = 1; $i <= $footer_columns; $i++) {
                    if (is_active_sidebar('footer-' . $i)) {
                        echo '<div class="widget-area footer-column-' . esc_attr($i) . '">';
                        dynamic_sidebar('footer-' . $i);
                        echo '</div>';
                    }
                }
                ?>
            </div><!-- .footer-widgets -->
        <?php endif; ?>

        <div class="site-info">
            <?php
            // Display the custom copyright text from the Customizer.
            echo wp_kses_post(get_theme_mod('luxury_jewels_copyright_text', 'Copyright &copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All Rights Reserved.'));
            ?>
        </div><!-- .site-info -->
    </footer><!-- #colophon -->
</div>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>