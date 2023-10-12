<?php

/**
 * Plugin Name: NewsBuilder
 * Plugin URI: https://digizaal.nl/plugins
 * Description: A WordPress plugin for creating newsletters in a WYSIWYG way.
 * Version: 1.0
 * Author: Dirk Zaal
 * Author URI: https://digizaal.nl
 * License: GPL2
 */

// Prevent direct access to the file
defined('ABSPATH') or die('Direct script access disallowed.');

// Include other necessary files
require_once(plugin_dir_path(__FILE__) . 'includes/admin-settings.php');
require_once(plugin_dir_path(__FILE__) . 'includes/newsletter-creation.php');
require_once(plugin_dir_path(__FILE__) . 'includes/subscription-form.php');
require_once(plugin_dir_path(__FILE__) . 'includes/public-archive.php');

// Activation Hook
function newsbuilder_activation() {
    // Tasks to perform upon plugin activation, like creating custom tables
}
register_activation_hook(__FILE__, 'newsbuilder_activation');

// Deactivation Hook
function newsbuilder_deactivation() {
    // Tasks to perform upon plugin deactivation, like cleaning up
}
register_deactivation_hook(__FILE__, 'newsbuilder_deactivation');

function enqueue_custom_admin_style($hook_suffix) {
    global $post_type;

    if (in_array($hook_suffix, array('edit.php', 'post.php', 'post-new.php')) && 'newsbuilder_news' == $post_type ) {

        wp_enqueue_script( 'wp-tinymce' );
        wp_enqueue_script( 'editor_js', admin_url('js/editor.js'), array(), false, true );
        wp_enqueue_style( 'tinymce_css', includes_url('css/editor.css') );
  
        wp_register_style('newsbuildercss', plugin_dir_url(__FILE__) . '/assets/css/admin.css', false, '1.0.0');
        wp_enqueue_style('newsbuildercss');
        wp_enqueue_script('my-admin-script', plugin_dir_url(__FILE__) . 'assets/js/admin-scripts.js', array('jquery', 'editor'), '1.0.0', true);
        wp_enqueue_script('jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', array('jquery'), '1.12.1', true);

        remove_post_type_support('newsbuilder_news', 'editor');
        wp_localize_script('my-admin-script', 'my_script_vars', array(
            'nonce' => wp_create_nonce('newsbuilder')
        ));
    }
}
add_action('admin_enqueue_scripts', 'enqueue_custom_admin_style');

// frontend scritp!
add_image_size('newsletter-thumb', 320, 180, true); // 316:9 20 pixels wide by 180 pixels tall, hard crop mode
function enqueue_newsletter_styles() {
    // Check if it's a single post and if the post type is 'newsbuilder_news' (or whatever your custom post type is)
    if (is_singular('newsbuilder_news')) {
        // Enqueue the stylesheet
        wp_enqueue_style('my-newsletter-styles', plugin_dir_url(__FILE__) . 'assets/css/frontend.css');
    }
}
add_action('wp_enqueue_scripts', 'enqueue_newsletter_styles');

// Enqueue scripts and styles for the admin area
function newsbuilder_admin_enqueue_scripts() {
    // Enqueue your admin scripts and styles here
    // Example: wp_enqueue_script('newsbuilder-admin-js', plugin_dir_url(__FILE__) . 'assets/js/admin.js', array('jquery'), '1.0', true);
    // Enqueue Select2 library (assuming it's not already enqueued)
    //wp_enqueue_script('select2', 'path/to/select2.min.js', array('jquery'), '4.1.0', true);
    // Enqueue your custom admin script

    // Add localized variables to the script
 
   // wp_enqueue_script('tiny_mce');

    // Pass ajax_url to admin-scripts.js
    wp_localize_script('my-admin-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('admin_enqueue_scripts', 'newsbuilder_admin_enqueue_scripts');



// Your additional hooks and functionalities can go here

