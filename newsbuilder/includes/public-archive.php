<?php
// Prevent direct access to the file
defined('ABSPATH') or die('Direct script access disallowed.');




// Create a shortcode for displaying the public email archive
function newsbuilder_public_archive_shortcode($atts) {
    ob_start();
    
    $query = new WP_Query(array(
        'post_type' => 'newsbuilder_news',
        'posts_per_page' => 10, // Adjust as needed
    ));
    
    if ($query->have_posts()) {
        echo '<ul>';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
        }
        echo '</ul>';
    } else {
        echo 'No newsletters found.';
    }
    
    wp_reset_postdata();
    
    return ob_get_clean();
}
add_shortcode('newsbuilder_public_archive', 'newsbuilder_public_archive_shortcode');


add_filter('template_include', 'use_custom_template_for_newsletter');

function use_custom_template_for_newsletter($template) {
    global $post;

    if (is_singular('newsbuilder_news')) {
        // For single posts
        $new_template = plugin_dir_path(__FILE__) . 'single-newsletter-template.php';
        if (file_exists($new_template)) {
            return $new_template;
        }
    } elseif (is_post_type_archive('newsbuilder_news')) {
        // For archive page
        $new_template = plugin_dir_path(__FILE__) . 'archive-newsletter-template.php';
        if (file_exists($new_template)) {
            return $new_template;
        }
    }

    return $template;
}


// Register the shortcode
add_shortcode('newsbuilder_news', 'newsbuilder_news_shortcode');

// Function to handle the shortcode
function newsbuilder_news_shortcode() {
    ob_start();

    // WP Query to get the latest 'newsbuilder_news' posts
    $query = new WP_Query([
        'post_type' => 'newsbuilder_news',
        'posts_per_page' => 10, // Number of posts to display
        'orderby' => 'date',
        'order' => 'DESC'
    ]);

    // Check if the query returns any posts
    if ($query->have_posts()) {
        echo '<ul>';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
        }
        echo '</ul>';
    } else {
        echo 'No newsbuilder_news posts found.';
    }

    // Restore original post data
    wp_reset_postdata();

    return ob_get_clean();
}

add_filter('template_include', 'newsbuilder_news_custom_template');

function newsbuilder_news_custom_template($template) {
    global $post;

    // Check if the current post belongs to the 'newsbuilder_news' custom post type
    if ($post->post_type == 'newsbuilder_news') {
        // Specify the path to your custom template file
        $new_template = plugin_dir_path(__FILE__) . 'single-newsbuilder_news-template.php';
        
        // Check if the file exists
        if (file_exists($new_template)) {
            return $new_template;
        }
    }

    return $template;
}
