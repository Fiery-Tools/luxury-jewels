<?php

/**
 * Template for the homepage
 *
 * @package luxury-jewels
 */

get_header();
?>

<main id="main" class="site-main" role="main">

    <!-- ====== HERO SECTION ====== -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Timeless by Design</h1>
            <p class="hero-subtitle">Discover jewelry crafted to be cherished for a lifetime.</p>
            <a href="/shop/" class="button hero-button">Explore the Collection</a>
        </div>
    </section>

    <!-- ====== FEATURED COLLECTIONS SECTION ====== -->
    <section class="featured-collections">
        <h2 class="section-title">Shop by Collection</h2>
        <div class="collections-grid">
            <?php
            $args = array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'number'     => 3,
            );
            $product_categories = get_terms($args);

            $default_images = [
                'assets/images/celestial.jpg',
                'assets/images/ethereal.jpg',
                'assets/images/opulence.jpg',
            ];

            $i = 0;
            if (! empty($product_categories) && ! is_wp_error($product_categories)) {
                foreach ($product_categories as $category) {
                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                    $image_url    = wp_get_attachment_url($thumbnail_id);
                    if ( ! $image_url && isset( $default_images[ $i ] ) ) {
                        $image_url = get_template_directory_uri() . '/' . $default_images[ $i ];
                    }
                    echo '<div class="collection-item">';
                    echo '<a href="' . esc_url(get_term_link($category)) . '">';
                    if ($image_url) {
                        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($category->name) . '">';
                    }
                    echo '<h3>' . esc_html($category->name) . '</h3>';
                    echo '</a>';
                    echo '</div>';
                    $i += 1;
                }
            }
            ?>
        </div>
    </section>

</main><!-- #main -->

<?php get_footer(); ?>
