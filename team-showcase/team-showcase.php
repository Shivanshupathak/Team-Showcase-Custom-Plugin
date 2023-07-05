<?php
/*
 * Plugin Name:Team Showcase
 * Description: Handle the basics with this plugin.
 * Version: 1.10.3
 * Author: Shivanshu Pathak
 * Text Domain: Team_showcase
 * Domain Path: /languages
 */

// Register the "Our Team" custom post type
class Team_Showcase {
    public function __construct() {
        add_action('init', array($this, 'team_showcase_register_post_type'));
        add_shortcode('team_showcase', array($this, 'team_showcase_shortcode'));
        add_action('wp_ajax_team_showcase_load_more', array($this, 'team_showcase_load_more'));
        add_action('wp_ajax_nopriv_team_showcase_load_more', array($this, 'team_showcase_load_more'));
        add_action('wp_enqueue_scripts', array($this, 'team_showcase_enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_plugin_styles'));
        add_action('add_meta_boxes', array($this, 'add_social_media_meta_boxes'));
        add_action('save_post', array($this, 'save_social_media_meta'));
        add_filter('single_template', array($this, 'custom_single_team_member_template'));
    }

public function team_showcase_register_post_type() {
    $labels = array(
        'name' => 'Our Team',
        'singular_name' => 'Team Member',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Team Member',
        'edit_item' => 'Edit Team Member',
        'new_item' => 'New Team Member',
        'view_item' => 'View Team Member',
        'view_items' => 'View Team Members',
        'search_items' => 'Search Team Members',
        'not_found' => 'No team members found.',
        'not_found_in_trash' => 'No team members found in trash.',
        'all_items' => 'All Team Members',
        'archives' => 'Team Member Archives',
        'attributes' => 'Team Member Attributes',
        'insert_into_item' => 'Insert into team member',
        'uploaded_to_this_item' => 'Uploaded to this team member',
        'filter_items_list' => 'Filter team members list',
        'items_list_navigation' => 'Team members list navigation',
        'items_list' => 'Team members list',
        'item_published' => 'Team member published.',
        'item_published_privately' => 'Team member published privately.',
        'item_reverted_to_draft' => 'Team member reverted to draft.',
        'item_scheduled' => 'Team member scheduled.',
        'item_updated' => 'Team member updated.',
    );

    $args = array(
        'label' => 'Team Members',
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => array('title', 'thumbnail', 'editor'),
        'rewrite' => array('slug' => 'our-team'),
    );

    register_post_type('team_member', $args);
}
//add_action('init', 'team_showcase_register_post_type');


// Shortcode to display team members
public function team_showcase_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => 6, // Number of team members to display per page
    ), $atts);

    $args = array(
        'post_type' => 'team_member',
        'posts_per_page' => $atts['count'],
    );

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            // Get the post permalink
            $permalink = get_permalink();
            // Display team member content here
            ?>
            <link rel="preconnect" href="https://fonts.gstatic.com">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    
            
                <div class="team-member">
                    <?php  if (has_post_thumbnail()) {
                    echo '<div class="team-member-image">' . get_the_post_thumbnail() . '</div>';
                } ?>
                <h3><a href="<?php echo esc_url($permalink); ?>"><?php the_title(); ?></a></h3>
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
            <?php
        }
        wp_reset_postdata();
    }
    $output = ob_get_clean();

    //$output .= '<div id="load-more-container" data-count="' . $atts['count'] . '" data-offset="' . $atts['count'] . '"><button id="load-more-button">Load More</button></div>';
    $output .= '<button id="load-more-button" style="display: flex;">Load More</button>';

    return $output;
}
//add_shortcode('team_showcase', 'team_showcase_shortcode');


// AJAX handler for loading more team members
public function team_showcase_load_more() {
    $count = isset($_POST['count']) ? intval($_POST['count']) : 6;
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;

    $args = array(
        'post_type' => 'team_member',
        'posts_per_page' => $count,
        'offset' => $offset,
    );

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            // Get the post permalink
            $permalink = get_permalink();
            // Display team member content here
            ?>
            <div class="team-member">
            <?php if (has_post_thumbnail()) {
                        echo '<div class="team-member-image">' . get_the_post_thumbnail() . '</div>';
                    }?>
                <h3><a href="<?php echo esc_url($permalink); ?>"><?php the_title(); ?></a></h3>
                <div class="team-member-content">
                    <?php the_content(); 

                    echo '<div class="team-member-deviation">' . get_post_meta(get_the_ID(), 'deviation', true) . '</div>';
                    echo '<div class="team-member-social-links">';
                    echo '<a href="' . esc_url(get_post_meta(get_the_ID(), 'facebook', true)) . '" target="_blank"><i class="fab fa-facebook"></i></a>';
                    echo '<a href="' . esc_url(get_post_meta(get_the_ID(), 'linkedin', true)) . '" target="_blank"><i class="fab fa-linkedin"></i></a>';
                    echo '<a href="' . esc_url(get_post_meta(get_the_ID(), 'youtube', true)) . '" target="_blank"><i class="fab fa-youtube"></i></a>';
                    echo '</div>';?>
                </div>
            </div>
            <?php
        }
        wp_reset_postdata();
    }
    $output = ob_get_clean();

    // Determine if there are more posts to load
    $has_more_posts = $query->found_posts > ($offset + $count);

    wp_send_json_success(array(
        'output' => $output,
        'has_more_posts' => $has_more_posts,
    ));
}


//add_action('wp_ajax_team_showcase_load_more', 'team_showcase_load_more');
//add_action('wp_ajax_nopriv_team_showcase_load_more', 'team_showcase_load_more');


public function team_showcase_enqueue_scripts() {
    wp_enqueue_script('team-showcase-script', plugin_dir_url(__FILE__) . 'js/team-showcase.js', array('jquery'), '1.0', true);
    wp_localize_script('team-showcase-script', 'teamShowcase', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
    ));
}
//add_action('wp_enqueue_scripts', 'team_showcase_enqueue_scripts');
public function enqueue_plugin_styles() {
    // Enqueue your plugin's CSS file
   wp_enqueue_style('preconnect', 'https://fonts.gstatic.com');
    wp_enqueue_style('preconnect1', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
    wp_enqueue_style('preconnect2', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap');
    wp_enqueue_style('plugin-style', plugins_url('css/style.css', __FILE__));
}

// Add meta boxes for social media links
public function add_social_media_meta_boxes() {
    add_meta_box(
        'social_media_meta_box',
        'Social Media URLs',
        array($this, 'social_media_meta_box_callback'),
        'team_member',
        'normal',
        'default'
    );
}

public function social_media_meta_box_callback($post) {
    $facebook_url = get_post_meta($post->ID, 'facebook', true);
    $linkedin_url = get_post_meta($post->ID, 'linkedin', true);
    $youtube_url = get_post_meta($post->ID, 'youtube', true);
    ?>
    <label for="facebook_url">Facebook URL:</label>
    <input type="text" id="facebook_url" name="facebook_url" value="<?php echo esc_attr($facebook_url); ?>" target="_blank">

    <label for="linkedin_url">LinkedIn URL:</label>
    <input type="text" id="linkedin_url" name="linkedin_url" value="<?php echo esc_attr($linkedin_url); ?>" target="_blank">

    <label for="youtube_url">YouTube URL:</label>
    <input type="text" id="youtube_url" name="youtube_url" value="<?php echo esc_attr($youtube_url); ?>" target="_blank">
    <?php
}

public function save_social_media_meta($post_id) {
    if (isset($_POST['facebook_url'])) {
        update_post_meta($post_id, 'facebook', sanitize_text_field($_POST['facebook_url']));
    }

    if (isset($_POST['linkedin_url'])) {
        update_post_meta($post_id, 'linkedin', sanitize_text_field($_POST['linkedin_url']));
    }

    if (isset($_POST['youtube_url'])) {
        update_post_meta($post_id, 'youtube', sanitize_text_field($_POST['youtube_url']));
    }
}



public function custom_single_team_member_template($single_template) {
    if (is_singular('team_member')) {
        $template_path = plugin_dir_path(__FILE__) . 'templates/single-our-team.php';
        if (file_exists($template_path)) {
            return $template_path;
        }
    }
    return $single_template;
}

}

$team_showcase = new Team_Showcase();
