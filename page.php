<?php

/**
 * The template for displaying all pages
 *
 * @package luxury-jewels
 */

get_header();
?>

<div id="primary" class="content-area luxury-jewels-container">
	<main id="main" class="site-main">

		<?php
		while (have_posts()) :
		?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php

				the_post();
				the_post_thumbnail();
				the_content();

				get_template_part('template-parts/content', 'page');

				// If comments are open or we have at least one comment, load up the comment template.
				if (comments_open() || get_comments_number()) :
					comments_template();
				endif;

				wp_link_pages(array(
					'before' => '<div class="page-links">' . esc_html__('Pages:', 'luxury-jewels'),
					'after'  => '</div>',
				));

				?></article>
		<?php
		endwhile; // End of the loop.
		the_posts_pagination();
		the_tags();

		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
