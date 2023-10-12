<?php
// Prevent direct access to the file
defined('ABSPATH') or die('Direct script access disallowed.');

// Create the admin settings menu
function newsbuilder_create_admin_menu() {
    add_menu_page(
        'NewsBuilder Settings', // Page title
        'NewsBuilder', // Menu title
        'manage_options', // Capability
        'newsbuilder_settings', // Menu slug
        'display_settings_page', // Callback function
        'dashicons-email', // Icon
        3 // Position
    );
    // Add submenu for Create Newsletter
    add_submenu_page(
        'newsbuilder_settings', // Parent slug
        'Create Newsletter', // Page title
        'Create Newsletter', // Menu title
        'manage_options', // Capability
        'newsbuilder_create_newsletter', // Menu slug
        'newsbuilder_display_create_newsletter_page' // Callback function
    );

    // Add submenu for Settings
    add_submenu_page(
        'newsbuilder_settings', // Parent slug
        'NewsBuilder Settings', // Page title
        'Settings', // Menu title
        'manage_options', // Capability
        'newsbuilder_settings', // Menu slug
        'display_settings_page' // Callback function
    );
}
add_action('admin_menu', 'newsbuilder_create_admin_menu');



// Register settings, sections, and fields
function newsbuilder_settings() {

    wp_register_style('newsbuildercss', plugin_dir_url(__FILE__) . '../assets/css/admin.css', false, '1.0.0');
    wp_enqueue_style('newsbuildercss');
    
    
    // Register settings
    register_setting('options-settings-group', 'newsbuilder_header_content');
    register_setting('options-settings-group', 'newsbuilder_footer_content');
    register_setting('options-settings-group', 'newsbuilder_default_font_size');
    register_setting('options-settings-group', 'newsbuilder_h1_font_size');
    register_setting('options-settings-group', 'newsbuilder_h2_font_size');

    register_setting('options-settings-group', 'newsbuilder_page_background_color');
    register_setting('options-settings-group', 'newsbuilder_max_email_width');
    register_setting('options-settings-group', 'newsbuilder_font_selection');

    register_setting('options-settings-group', 'newsbuilder_link_color');
    register_setting('options-settings-group', 'newsbuilder_link_font_size');
    register_setting('options-settings-group', 'newsbuilder_primary_color');
    register_setting('options-settings-group', 'newsbuilder_secondary_color');
    register_setting('options-settings-group', 'newsbuilder_text_color');

    // Add settings sections
    
    add_settings_section('newsbuilder_layout_section', 'Layout Settings', 'newsbuilder_layout_section_callback', 'newsbuilder_settings');
  
    add_settings_section('newsbuilder_header_footer_section', 'Header & Footer Settings', 'newsbuilder_header_footer_section_callback', 'newsbuilder_settings');

    add_settings_field('newsbuilder_default_font_size', 'Default Font Size', 'newsbuilder_default_font_size_callback', 'newsbuilder_settings', 'newsbuilder_layout_section');
    add_settings_field('newsbuilder_h1_font_size', 'H1 Font Size', 'newsbuilder_h1_font_size_callback', 'newsbuilder_settings', 'newsbuilder_layout_section');
    add_settings_field('newsbuilder_h2_font_size', 'H2 Font Size', 'newsbuilder_h2_font_size_callback', 'newsbuilder_settings', 'newsbuilder_layout_section');
    add_settings_field('newsbuilder_font_selection', 'Font Selection', 'newsbuilder_font_selection_callback', 'newsbuilder_settings', 'newsbuilder_layout_section');
    add_settings_field('newsbuilder_max_email_width', 'Max Email Width', 'newsbuilder_max_email_width_callback', 'newsbuilder_settings', 'newsbuilder_layout_section');
    add_settings_field('newsbuilder_page_background_color', 'Page Background Color', 'newsbuilder_page_background_color_callback', 'newsbuilder_settings', 'newsbuilder_layout_section');
    add_settings_field('newsbuilder_primary_color', 'Primary Color', 'newsbuilder_primary_color_callback', 'newsbuilder_settings',  'newsbuilder_layout_section');
    add_settings_field('newsbuilder_secondary_color', 'Secondary Color', 'newsbuilder_secondary_color_callback', 'newsbuilder_settings', 'newsbuilder_layout_section');
    add_settings_field('newsbuilder_link_color', 'Link Color', 'newsbuilder_link_color_callback', 'newsbuilder_settings', 'newsbuilder_layout_section');
    add_settings_field('newsbuilder_link_font_size', 'Link Font Size', 'newsbuilder_link_font_size_callback', 'newsbuilder_settings', 'newsbuilder_layout_section');
    add_settings_field('newsbuilder_text_color', 'Text Color', 'newsbuilder_text_color_callback', 'newsbuilder_settings', 'newsbuilder_layout_section');

    add_settings_field('newsbuilder_header_content', 'Header HTML', 'newsbuilder_header_content_callback', 'newsbuilder_settings', 'newsbuilder_header_footer_section');
    add_settings_field('newsbuilder_footer_content', 'Footer HTML', 'newsbuilder_footer_content_callback', 'newsbuilder_settings', 'newsbuilder_header_footer_section');
    
}


add_action('admin_init', 'newsbuilder_settings');

// Display the admin settings page
function display_settings_page() {
    echo '<div class="wrap">';
    echo '<h1>NewsBuilder Settings</h1>';
    echo '<form method="post" action="options.php">';
    settings_fields('options-settings-group');

    do_settings_sections('newsbuilder_settings');

    submit_button('Save Settings');
    echo '</form>';
    echo "</div>
    <script>
    // JavaScript to update the preview section
    document.addEventListener('DOMContentLoaded', function() {
        const primaryColorInput = document.getElementById('primary_color');
        const secondaryColorInput = document.getElementById('secondary_color');
        const textColorInput = document.getElementById('text_color');
        const fontSelection = document.getElementById('font_selection');
        const preview = document.querySelector('.preview');
        const page_background_color = document.getElementById('page_background_color');
        const link_font_size = document.getElementById('link_font_size');
        const link_color = document.getElementById('link_color');
        const h2_font_size = document.getElementById('h2_font_size');
        const h1_font_size = document.getElementById('h1_font_size');
        const default_font_size = document.getElementById('default_font_size');

        // Update preview on color or font change
        function updatePreview() {
            preview.style.setProperty('--primary-color', primaryColorInput.value);
            preview.style.setProperty('--secondary-color', secondaryColorInput.value);
            preview.style.setProperty('--text-color', textColorInput.value);
            preview.style.setProperty('--font-family', fontSelection.value);
            preview.style.setProperty('--page-background-color', page_background_color.value);
            preview.style.setProperty('--link-font-size', link_font_size.value);
            preview.style.setProperty('--link-color', link_color.value);
            preview.style.setProperty('--h2-font-size', h2_font_size.value);
            preview.style.setProperty('--h1-font-size', h1_font_size.value);
            preview.style.setProperty('--default-font-size', default_font_size.value);
        }

        primaryColorInput.addEventListener('input', updatePreview);
        secondaryColorInput.addEventListener('input', updatePreview);
        textColorInput.addEventListener('input', updatePreview);
        fontSelection.addEventListener('change', updatePreview);
        page_background_color.addEventListener('input', updatePreview);
        link_font_size.addEventListener('input', updatePreview);
        link_color.addEventListener('input', updatePreview);
        h2_font_size.addEventListener('input', updatePreview);
        h1_font_size.addEventListener('input', updatePreview);
        default_font_size.addEventListener('input', updatePreview);
        // Initialize preview
        updatePreview();
    });
</script>";
}


function newsbuilder_secondary_color_callback() {
    $secondary_color = get_option('newsbuilder_secondary_color');
    echo "<input type='color' name='newsbuilder_secondary_color'  id='secondary_color' value='$secondary_color'>";
}

function newsbuilder_link_color_callback() {
    $link_color = get_option('newsbuilder_link_color');
    echo "<input type='color' name='newsbuilder_link_color' id='link_color' value='$link_color'>";
}

function newsbuilder_link_font_size_callback() {
    $link_font_size = get_option('newsbuilder_link_font_size');
    echo "<input type='text' name='newsbuilder_link_font_size' id='link_font_size' value='$link_font_size'>";
}

function newsbuilder_text_color_callback() {
    $text_color = get_option('newsbuilder_text_color');
    echo "<input type='color' name='newsbuilder_text_color' id='text_color' value='$text_color'>";
}

// Callback functions for sections
function newsbuilder_header_footer_section_callback() {
    echo '</div>';
    newsbuilder_preview_callback();
    echo 'Configure your header and footer settings below:';
}

function newsbuilder_layout_section_callback() {
    echo 'Configure your layout settings below:';
    echo '<div class="col-container">
            <div class="col-left">';
}

// Callback functions for fields
function newsbuilder_header_content_callback() {
    wp_editor(get_option('newsbuilder_header_content'), 'newsbuilder_header_content', array('editor_height' => 200,'max_height' => 200));
}

function newsbuilder_footer_content_callback() {
    wp_editor(get_option('newsbuilder_footer_content'), 'newsbuilder_footer_content', array('max_height' => 200));
}

function newsbuilder_default_font_size_callback() {
    echo '<input type="text" id="default_font_size" name="newsbuilder_default_font_size" placeholder="14px"  id="default_font_size" value="' . esc_attr(get_option('newsbuilder_default_font_size', '14px')) . '">';
}



function newsbuilder_font_selection_callback() {
    $font_selection = get_option('newsbuilder_font_selection', 'Arial, sans-serif');
    echo "<select name='newsbuilder_font_selection' id='font_selection'>";
    echo "<option value=\"Arial, sans-serif\" " . selected($font_selection, 'Arial, sans-serif', false) . ">Arial</option>";
    echo "<option value=\"Georgia, serif\" " . selected($font_selection, 'Georgia, serif', false) . ">Georgia</option>";
    echo "<option value=\"'Courier New', monospace\" " . selected($font_selection, '\'Courier New\', monospace', false) . ">Courier New</option>";
    echo "<option value=\"'Times New Roman', serif\" " . selected($font_selection, '\'Times New Roman\', serif', false) . ">Times New Roman</option>";
    echo "<option value=\"Verdana, sans-serif\" " . selected($font_selection, 'Verdana, sans-serif', false) . ">Verdana</option>";
    echo "</select>";
}

function newsbuilder_max_email_width_callback() {
    $max_email_width = get_option('newsbuilder_max_email_width');
    echo "<input type='text' name='newsbuilder_max_email_width' placeholder='600px' id='max_email_width' style='width: 6em' value='$max_email_width'>";
}

function newsbuilder_page_background_color_callback() {
    $page_background_color = get_option('newsbuilder_page_background_color');
    echo "<input type='color' name='newsbuilder_page_background_color' id='page_background_color' value='$page_background_color'>";
}

function newsbuilder_h1_font_size_callback() {
    $h1_font_size = get_option('newsbuilder_h1_font_size');
    echo "<input type='text' name='newsbuilder_h1_font_size' id='h1_font_size' placeholder='20px' value='$h1_font_size'>";
}

function newsbuilder_h2_font_size_callback() {
    $h2_font_size = get_option('newsbuilder_h2_font_size');
    echo "<input type='text' name='newsbuilder_h2_font_size' id='h2_font_size' placeholder='16px'  value='$h2_font_size'>";
}

function newsbuilder_primary_color_callback() {
    $primary_color = get_option('newsbuilder_primary_color', '#00476b');
    echo "<input type='color' name='newsbuilder_primary_color'  id='primary_color' value='$primary_color'>";
}

function newsbuilder_preview_callback (){

    echo '<div class="col-right">
            <div class="preview">
                <h1 class="newsheader primary_color">H1 Header is Primary Color</h1>
                <p class="text_color">Lorem ipsum dolor sit amet consectetuer tellus Morbi eu metus Duis. Venenatis nonummy scelerisque et wisi quam orci sit at risus tellus. Lobortis congue laoreet semper eu at vitae eros ligula est nec. Dictumst malesuada In turpis orci non a Maecenas Vivamus nibh elit. Curabitur mus <a href="#">senectus Cum leo id vitae nec mattis Sed ante</a>. Nam a tellus ligula ut feugiat justo sem fermentum habitant ut. Vitae Vivamus tellus.</p>
                <h2 class="newstitle primary_color" style="text-transform: uppercase"> H2 header in primary color</h2>
                <p class="abstract text_color">Lorem ipsum dolor sit amet consectetuer tellus Morbi eu metus Duis. Venenatis nonummy scelerisque et wisi quam orci sit at risus tellus. Lobortis congue laoreet semper eu at vitae eros ligula est nec. Dictumst malesuada In turpis orci non a Maecenas Vivamus nibh elit. Curabitur mus <a href="#">senectus Cum leo id vitae nec mattis Sed ante</a>. Nam a tellus ligula ut feugiat justo sem fermentum habitant ut. Vitae Vivamus tellus.</p>
                <div class="url_object"><a class="secondary_color readme" href="#"><span class="me"> the Link </span></a></div>
            </div>
        </div>
    </div>';

}