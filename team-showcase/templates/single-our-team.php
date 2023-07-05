<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) {
    the_post();
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header>
        
            
            
                <div class="team-members">
                    <?php  if (has_post_thumbnail()) {
                    echo '<div class="team-member-image">' . get_the_post_thumbnail() . '</div>';
                } ?>
                <h3><?php the_title(); ?></h3>
                <div class="team-member-content">
                    <?php the_content(); ?>
                </div>
                
                <?php
                echo '<div class="team-member-deviation">' . get_post_meta(get_the_ID(), 'deviation', true) . '</div>';
                echo '<div class="team-member-social-links">';
                echo '<a href="' . esc_url(get_post_meta(get_the_ID(), 'facebook', true)) . '" target="_blank"><i class="fab fa-facebook"></i></a>';
                echo '<a href="' . esc_url(get_post_meta(get_the_ID(), 'linkedin', true)) . '" target="_blank"><i class="fab fa-linkedin"></i></a>';
                echo '<a href="' . esc_url(get_post_meta(get_the_ID(), 'youtube', true)) . '" target="_blank"><i class="fab fa-youtube"></i>
                </a>';
                echo '</div>';
                ?>

                </div>
    </article>

    <?php
}

get_footer();
?>
