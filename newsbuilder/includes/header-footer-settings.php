<?php
// Prevent direct access to the file
defined('ABSPATH') or die('Direct script access disallowed.');
// Register settings
function newsbuilder_register_settings() {

}

add_action('admin_init', 'newsbuilder_register_settings');



// Display the header and footer settings page
function newsbuilder_display_header_footer_page($post) {

    if (isset($_POST) && $_GET['page'] = 'newsbuilder_header_footer') {

        newsbuilder_save_settings($_POST);
    }
    newsbuilder_display_settings_meta_box($post);
}




// Display the meta box
function newsbuilder_display_settings_meta_box($post) {
    // Output your color pickers, font selection dropdown, etc. here
?>

    <form method="post" action="options.php">
        <div class="wrap" style="max-width:1024px">
            <h1>Header & Footer Settings</h1>

            <?php
            // Output security fields for the registered setting
            settings_fields('newsbuilder_setting_group');
            // Output setting sections and their fields
            do_settings_sections('newsbuilder_header_footer');

            wp_nonce_field('newsbuilder_save_post_meta', 'newsbuilder_custom_box_nonce');

            ?>
            <fieldset>
                <?php
                // Add WYSIWYG editor for Header
                echo '<h2>Header HTML</h2>';
                wp_editor(get_option('newsbuilder_header_content'), 'newsbuilder_header_content');

                ?>

            </fieldset>


            <div class="col-container">

                <div class="col-left">
                    <fieldset class="form-fields">
                        <legend>Layout Settings</legend>
                        <div class="extra-setting">
                            <label for="default_font_size">Default Font Size: </label>
                            <input type="text" id="default_font_size" name="default_font_size" value="<?php echo esc_attr(get_option('newsbuilder_default_font_size', '14px')); ?>">
                        </div>

                        <!-- H1 Font Size -->
                        <div class="extra-setting">
                            <label for="h1_font_size">H1 Font Size: </label>
                            <input type="text" id="h1_font_size" name="h1_font_size" value="<?php echo esc_attr(get_option('newsbuilder_h1_font_size', '1.3em')); ?>">
                        </div>

                        <!-- H2 Font Size -->
                        <div class="extra-setting">
                            <label for="h2_font_size">H2 Font Size: </label>
                            <input type="text" id="h2_font_size" name="h2_font_size" value="<?php echo esc_attr(get_option('newsbuilder_h2_font_size', '1.1em')); ?>">
                        </div>

                        <!-- Link Font Color and Size -->
                        <div class="extra-setting">
                            <label for="link_font">Link Font Color and Size: </label>
                            <input type="color" id="link_color" name="link_color" value="<?php echo esc_attr(get_option('newsbuilder_link_color', "#ffffff")); ?>">
                            <input type="text" id="link_font_size" name="link_font_size" value="<?php echo esc_attr(get_option('newsbuilder_link_font_size', '1em')); ?>">

                        </div>

                        <!-- Default Page Background Color -->
                        <div class="extra-setting">
                            <label for="page_background_color">Default Page Background Color: </label>
                            <input type="color" id="page_background_color" name="page_background_color" value="<?php echo esc_attr(get_option('newsbuilder_page_background_color', "#ffffff")); ?>">
                        </div>

                        <!-- Max Width of Email -->
                        <div class="extra-setting">
                            <label for="max_email_width">max-width of e-mail: </label>
                            <input type="text" id="max_email_width" name="max_email_width" style="width: 6em" value="<?php echo esc_attr(get_option('newsbuilder_max_email_width', "600px")); ?>">
                        </div>
                        <div class="extra-setting">

                            <label> Primary Color: </label><input type="color" id="primary_color" name="primary_color" value="<?php echo esc_attr(get_option('newsbuilder_primary_color', '#00476b')); ?>"><br>
                        </div>
                        <div class="extra-setting">
                            <label>Secondary Color: </label><input type="color" id="secondary_color" name="secondary_color" value="<?php echo esc_attr(get_option('newsbuilder_secondary_color', '#ffffff')); ?>"><br>
                        </div>
                        <div class="extra-setting">
                            <label>Text Color: </label><input type="color" id="text_color" name="text_color" value="<?php echo esc_attr(get_option('newsbuilder_text_color', '#121212')); ?>"><br>
                        </div>
                        <div class="extra-setting">
                            <label>Font:</label>
                            <select id="font_selection" name="font_selection">
                                <option value="Arial, sans-serif" <?php selected(get_option('newsbuilder_font_selection'), 'Arial, sans-serif'); ?>>Arial</option>
                                <option value="'Courier New', monospace" <?php selected(get_option('newsbuilder_font_selection'), "'Courier New', monospace"); ?>>Courier New</option>
                                <option value="Georgia, serif" <?php selected(get_option('newsbuilder_font_selection'), 'Georgia, serif'); ?>>Georgia</option>
                                <option value="'Times New Roman', serif" <?php selected(get_option('newsbuilder_font_selection'), "'Times New Roman', serif"); ?>>Times New Roman</option>
                                <option value="'Verdana', sans-serif" <?php selected(get_option('newsbuilder_font_selection'), "'Verdana', sans-serif"); ?>>Verdana</option>
                            </select>
                        </div>
                    </fieldset>
                </div>
                <div class="col-right">
                    <div class="preview">
                        <h1 class="newsheader primary_color">H1 Header is Primary Color</h1>
                        <p class="text_color">
                            Lorem ipsum dolor sit amet consectetuer tellus Morbi eu metus Duis. Venenatis nonummy scelerisque et wisi quam orci sit at risus tellus. Lobortis congue laoreet semper eu at vitae eros ligula est nec. Dictumst malesuada In turpis orci non a Maecenas Vivamus nibh elit. Curabitur mus <a href="#">senectus Cum leo id vitae nec mattis Sed ante</a>. Nam a tellus ligula ut feugiat justo sem fermentum habitant ut. Vitae Vivamus tellus.
                        </p>
                        <h2 class="newstitle primary_color" style="text-transform: uppercase"> H2 header in primary color</h2>


                        <p class="abstract text_color">
                            Lorem ipsum dolor sit amet consectetuer tellus Morbi eu metus Duis. Venenatis nonummy scelerisque et wisi quam orci sit at risus tellus. Lobortis congue laoreet semper eu at vitae eros ligula est nec. Dictumst malesuada In turpis orci non a Maecenas Vivamus nibh elit. Curabitur mus <a href="#">senectus Cum leo id vitae nec mattis Sed ante</a>. Nam a tellus ligula ut feugiat justo sem fermentum habitant ut. Vitae Vivamus tellus.
                        </p>
                        <div class="url_object">
                            <a class="secondary_color readme" href="#"><span class="me"> the Link</span></a>
                        </div>

                    </div>
                </div>
            </div>

            <fieldset>
                <?php


                // Add WYSIWYG editor for Footer
                echo '<h2>Footer HTML</h2>';
                wp_editor(get_option('newsbuilder_footer_content'), 'newsbuilder_footer_content');

                ?>

            </fieldset>
            <!-- Save Settings Button -->
            <div style="position: fixed; right: 20px; top: 20px;">
                <?php submit_button('Save Settings'); ?>
            </div>
        </div>
    </form>
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
    </script>

    <style>
        .col-container {
            clear: both;
            padding-bottom: 1em;
            overflow: hidden;
            padding: 5px
        }

        .col-left {
            float: left;
            width: 50%;
        }

        .col-right {
            float: right;
            width: 50%;
        }

        .form-fields input[type="text"] {
            width: 4em
        }

        .form-fields label {
            display: inline-block;
            width: 50%;
            max-width: 50%
        }

        .preview {
            background-color: var(--page-background-color);
            font-size: var(--default-font-size);
            font-family: var(--font-family);
            padding: 10px;
            box-shadow: 4px 4px 4px #000
        }

        .preview p {
            font-size: var(--default-font-size);
            font-family: var(--font-family);
            margin: 2px 0
        }

        .primary_color {
            background-color: var(--primary-color);
        }

        .secondary_color {
            background-color: var(--secondary-color);
        }

        .preview h1,
        .preview h2 {
            color: var(--primary-color);
            background-color: transparent;
            font-family: var(--font-family);
            text-transform: uppercase;
            margin: 0
        }


        .primary_color *,
        .secondary_color * {
            background-color: transparent !important
        }

        .abstract {
            font-size: 0.98em;
            color: var(--text-color);
            font-family: var(--font-family);
        }

        h2.newstitle {
            margin: 0;
            font-weight: bold;
            color: var(--primary-color);
            font-size: var(--h2-font-size);
            font-family: var(--font-family);
        }

        h1.newsheader {
            font-weight: bold;
            color: var(--primary-color);
            font-size: var(--h1-font-size)
        }

        a.readme {
            background-color: var(--secondary-color);
            padding: 5px;
            float: right;
            font-size: var(--link-font-size)
        }

        a.readme .me {
            color: var(--link-color)
        }
    </style>

<?php
}



// Save custom fields when the post is saved
function newsbuilder_save_settings() {
    // Debugging line
    error_log('Saving settings');
    error_log(var_dump($_POST));


    // Check if it's a WordPress autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Check if our nonce is set and valid.
    if (!isset($_POST['newsbuilder_custom_box_nonce']) || !wp_verify_nonce($_POST['newsbuilder_custom_box_nonce'], 'newsbuilder_save_post_meta')) {
        return;
    }

    // Check the user's permissions.
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }
    if (isset($_POST['newsbuilder_header_content'])) {
        $header_content = wp_kses_post($_POST['newsbuilder_header_content']);
        update_post_meta($post_id, 'newsbuilder_header_content', $header_content);
        // Debugging line
        error_log('Header content set: ' . $header_content);
    }
    if (isset($_POST['newsbuilder_footer_content'])) {
        $footer_content = wp_kses_post($_POST['newsbuilder_footer_content']);
        update_post_meta($post_id, 'newsbuilder_footer_content', $footer_content);
        // Debugging line
        error_log('Footer content set: ' . $footer_content);
    }
    // Save each custom field
    update_post_meta($post_id, 'newsbuilder_primary_color', sanitize_text_field($_POST['primary_color']));
    update_post_meta($post_id, 'newsbuilder_secondary_color', sanitize_text_field($_POST['secondary_color']));
    update_post_meta($post_id, 'newsbuilder_text_color', sanitize_text_field($_POST['text_color']));
    update_post_meta($post_id, 'newsbuilder_font_selection', sanitize_text_field($_POST['font_selection']));

    update_post_meta($post_id, 'newsbuilder_default_font_size', sanitize_text_field($_POST['default_font_size']));
    update_post_meta($post_id, 'newsbuilder_h1_font_size', sanitize_text_field($_POST['h1_font_size']));
    update_post_meta($post_id, 'newsbuilder_h2_font_size', sanitize_text_field($_POST['h2_font_size']));
    update_post_meta($post_id, 'newsbuilder_link_font_color', sanitize_text_field($_POST['link_color']));
    update_post_meta($post_id, 'newsbuilder_link_font_size', sanitize_text_field($_POST['link_font_size']));
    update_post_meta($post_id, 'newsbuilder_default_page_bg_color', sanitize_text_field($_POST['page_background_color']));
    update_post_meta($post_id, 'newsbuilder_max_width_email', sanitize_text_field($_POST['max_email_width']));
}
