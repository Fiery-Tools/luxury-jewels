<?php
/**
 * The template for displaying all pages
 *
 * @package Luxury_Jewels
 */

get_header();
?>

	<div id="primary" class="content-area luxury-jewels-container">
		<main id="main" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();
                the_content();


				// This will look for a file called content-page.php
				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();