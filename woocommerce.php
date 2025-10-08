<?php
/**
 * The template for displaying WooCommerce pages.
 *
 * @package Luxury_Jewels
 */

get_header();

// Get the sidebar layout from the Customizer. You might need to create this setting.
// For now, we can default it to 'sidebar-right' to ensure it shows up.
$sidebar_layout = get_theme_mod( 'luxury_jewels_shop_sidebar_layout', 'sidebar-right' );
?>
<!-- <?php echo $sidebar_layout; ?>:<?php echo is_active_sidebar( 'shop-sidebar' ) ? 1 : 0 ?> -->
<div id="primary" class="content-area luxury-jewels-container">
    <main id="main" class="site-main">

        <?php // We only create the two-column layout if a sidebar is chosen and it has widgets. ?>
        <?php if ( 'no-sidebar' !== $sidebar_layout && is_active_sidebar( 'shop-sidebar' ) ) : ?>

            <div class="shop-container <?php echo esc_attr( $sidebar_layout ); ?>">

                <?php // This is the main content area for your products. ?>
                <div class="shop-content">
                    <?php woocommerce_content(); ?>
                </div><!-- .shop-content -->

                <?php
                /**
                 * THIS IS THE CRUCIAL PART THAT WAS MISSING.
                 * This code actively calls and displays the sidebar.
                 * We wrap it in an <aside> tag for correct HTML5 semantics.
                 */
                ?>
                <aside id="secondary" class="widget-area">
                    <?php get_sidebar( 'sidebar-shop' ); ?>
                    ss
                </aside><!-- #secondary -->

            </div><!-- .shop-container -->

        <?php else : ?>

            <?php // If 'no-sidebar' is chosen or the sidebar has no widgets, show products full-width. ?>
            <?php woocommerce_content(); ?>

        <?php endif; ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();