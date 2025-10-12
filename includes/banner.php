<?php
/**
 * Remove the default WooCommerce category title and description,
 * and add a custom banner with the category image.
 */

// 1. Remove the default page title
add_filter( 'woocommerce_show_page_title', '__return_false' );

// 2. Remove the default category description
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );

// 3. Add our custom banner using a more reliable hook
add_action( 'woocommerce_before_shop_loop', 'my_custom_category_banner', 15 );

function my_custom_category_banner() {
    // We only want this to run on product category archives
    if ( is_product_category() ) {
        // Get the current category object to access its data
        $category = get_queried_object();
        $category_id = $category->term_id;

        // Get the URL for the category's thumbnail image
        $thumbnail_id = get_term_meta( $category_id, 'thumbnail_id', true );
        $image_url = wp_get_attachment_url( $thumbnail_id );

        // Get the category's description
        $description = $category->description;

        // Only display the banner if a category image has been set
        if ( $image_url ) {
            ?>
            <div class="custom-category-banner-wrapper">
                <div class="custom-category-banner" style="background-image: url('<?php echo esc_url( $image_url ); ?>');">
                    <div class="banner-content">
                        <h1 class="category-title"><?php echo esc_html( $category->name ); ?></h1>
                        <?php if ( $description ) : ?>
                            <div class="category-description"><?php echo wp_kses_post( $description ); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            // Optional Fallback: If no image is set, display a simpler header
            // so the page doesn't look broken.
            ?>
            <div class="simple-category-header">
                 <h1 class="woocommerce-products-header__title page-title"><?php echo esc_html( $category->name ); ?></h1>
                 <?php if ( $description ) : ?>
                    <div class="category-description"><?php echo wp_kses_post( $description ); ?></div>
                 <?php endif; ?>
            </div>
            <?php
        }

        // We also need to remove the result count and sorting dropdown from their default position
        // because we are essentially creating a new "header" area.
        remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
        remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    }
}

// 4. Re-add the result count and sorting dropdown AFTER our banner
add_action( 'woocommerce_before_shop_loop', 'add_back_sorting_and_count', 35 );
function add_back_sorting_and_count(){
    if ( is_product_category() ){
        ?>
        <div class="woocommerce-sorting-wrapper">
            <?php woocommerce_result_count(); ?>
            <?php woocommerce_catalog_ordering(); ?>
        </div>
        <?php
    }
}