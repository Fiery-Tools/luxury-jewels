<?php
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
                printf(
                    _nx(
                        'One thought on "%2$s"',
                        '%1$s thoughts on "%2$s"',
                        get_comments_number(),
                        'comments title',
                        'luxury-jewels'
                    ),
                    number_format_i18n( get_comments_number() ),
                    '<span>' . get_the_title() . '</span>'
                );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
                wp_list_comments( array(
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 56,
                ) );
            ?>
        </ol>

        <?php the_comments_pagination(); ?>

    <?php endif; // Check for have_comments(). ?>

    <?php
    // If comments are closed and there are comments, leave a little note.
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
    ?>
        <p class="no-comments"><?php _e( 'Comments are closed.', 'luxury-jewels' ); ?></p>
    <?php endif; ?>

    <?php comment_form(); ?>

</div><!-- #comments -->