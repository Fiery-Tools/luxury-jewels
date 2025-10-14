<?php
/**
 * The header for our theme
 *
 * @package luxury-jewels
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'luxury-jewels' ); ?></a>

    <header id="masthead" class="site-header" role="banner">
        <?php
        // Get the selected header layout from the Customizer.
        $header_layout = get_theme_mod( 'luxury_header_layout', 'default' );
        ?>
        <div class="luxury-jewels-container">


        <!-- This new wrapper div controls the layout (default flex-row vs. centered flex-column) -->
        <div class="header-inner header-layout-<?php echo esc_attr( $header_layout ); ?>">

            <div class="site-branding">
                <?php
                if ( has_custom_logo() ) {
                    the_custom_logo();
                } elseif ( is_front_page() && is_home() ) {
                    echo '<h1 class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . get_bloginfo( 'name' ) . '</a></h1>';
                } else {
                    echo '<p class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . get_bloginfo( 'name' ) . '</a></p>';
                }
                $luxury_jewels_description = get_bloginfo( 'description', 'display' );
                if ( $luxury_jewels_description || is_customize_preview() ) :
                    echo '<p class="site-description">' . $luxury_jewels_description . '</p>';
                endif;
                ?>
            </div><!-- .site-branding -->

            <nav id="site-navigation" class="main-navigation" role="navigation">
                <?php
                // CRITICAL: This preserves your custom navigation function.
                luxury_jewels_custom_nav_menu();
                ?>
            </nav><!-- #site-navigation -->

        </div><!-- .header-inner -->
        </div>

    </header><!-- #masthead -->

    <div id="content" class="site-content">