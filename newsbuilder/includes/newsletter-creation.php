<?php
// Prevent direct access to the file
defined('ABSPATH') or die('Direct script access disallowed.');

// Register custom post type for newsletters
function newsbuilder_register_newsletter_post_type() {
    $args = array(
        'public' => true,
        'label'  => 'Newsletters',
        'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
        'show_in_menu' => 'newsbuilder_settings', // Make it appear under the "NewsBuilder" menu
    );
    register_post_type('newsbuilder_news', $args);
}
add_action('init', 'newsbuilder_register_newsletter_post_type');
// Enqueue scripts and styles
function newsbuilder_enqueue_scripts() {
    // Enqueue jQuery (WordPress already includes it)
    wp_enqueue_script('jquery');

    // Enqueue Select2 CSS and JS (from a CDN in this example)
    wp_enqueue_style('select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
    wp_enqueue_script('select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), null, true);
}

add_action('admin_enqueue_scripts', 'newsbuilder_enqueue_scripts');


// Redirect to the standard post editor for your custom post type
function newsbuilder_display_create_newsletter_page() {
    wp_redirect(admin_url('post-new.php?post_type=newsbuilder_news'));
    exit;
}




// Add a new meta box for the sidebar
add_action('add_meta_boxes', 'newsbuilder_add_sidebox_meta_box');

function newsbuilder_display_sidebox_meta_box($post) {
    $post_id = $_GET['post'];

    // Get current post meta data
    $pre_header = get_post_meta($post_id, 'pre_header', true);
    $columns = get_post_meta($post_id, 'columns', true);
    $text_color = get_post_meta($post_id, 'text_color', true) ? get_post_meta($post_id, 'text_color', true) : get_option('newsbuilder_text_color');
    $primary_color = get_post_meta($post_id, 'primary_color', true) ? get_post_meta($post_id, 'primary_color', true) : get_option('newsbuilder_primary_color');
    $secondary_color = get_post_meta($post_id, 'secondary_color', true) ? get_post_meta($post_id, 'secondary_color', true) : get_option('newsbuilder_secondary_color');
    $font_selection = get_post_meta($post_id, 'font_selection', true) ? get_post_meta($post_id, 'font_selection', true) : get_option('newsbuilder_font_selection');
    $layout = get_post_meta($post_id, 'layout', true) ? get_post_meta($post_id, 'layout', true) : get_option('newsbuilder_layout');
    $title_transform = get_post_meta($post_id, 'title_transform', true) ? get_post_meta($post_id, 'title_transform', true) : get_option('newsbuilder_title_transform');
    $page_background = get_post_meta($post_id, 'page_background', true) ? get_post_meta($post_id, 'page_background', true) :'#ffffff';
    // Output form fields
?>
    <div class="newsletter_fields">

        <div class="element">
            <label for="pre_header">Pre Header:</label>
            <input type="text" id="pre_header" name="pre_header" placeholder="eye-catching text" value="<?php echo esc_attr($pre_header); ?>" maxlength="64">
            <div class="element"></div>
        </div>

        <div class="element">
            <label for="columns">Columns:</label>
            <input type="number" id="columns" name="columns" value="<?php echo esc_attr($columns ? $columns : 2); ?>" min="1" max="4">
        </div>
        <div class="element">
            <label for="primary_color">Primary Color:</label>
            <input type="color" id="primary_color" name="primary_color" value="<?php echo esc_attr($primary_color); ?>">
        </div>
        <div class="element">
            <label for="text_color">Text Color:</label>
            <input type="color" id="text_color" name="text_color" value="<?php echo esc_attr($text_color); ?>">
        </div>

        

        <div class="element">
            <label for="secondary_color">Secondary Color:</label>
            <input type="color" id="secondary_color" name="secondary_color" value="<?php echo esc_attr($secondary_color); ?>">
        </div>

        <div class="element">
            <label for="layout">Layout:</label>
            <input type="text" id="layout" name="layout" value="<?php echo esc_attr($layout); ?>">
        </div>

        <div class="element">
            <label for="font_selection">Font:</label>
            <select class="select2" id="font_selection" name="font_selection">
                <option value="Arial, sans-serif" <?php selected($font_selection, 'Arial, sans-serif'); ?>>Arial</option>
                <option value="'Courier New', monospace" <?php selected($font_selection, "'Courier New', monospace"); ?>>Courier New</option>
                <option value="Georgia, serif" <?php selected($font_selection, 'Georgia, serif'); ?>>Georgia</option>
                <option value="'Times New Roman', serif" <?php selected($font_selection, "'Times New Roman', serif"); ?>>Times New Roman</option>
                <option value="'Verdana', sans-serif" <?php selected($font_selection, "'Verdana', sans-serif"); ?>>Verdana</option>
            </select>
        </div>

        <div class="element">
            <label for="title_transform">Title Transform:</label>
            <select class="select2" id="title_transform" name="title_transform">
                <option value="none" <?php selected($title_transform, 'none'); ?>>None</option>
                <option value="capitalize" <?php selected($title_transform, 'capitalize'); ?>>Capitalize</option>
                <option value="uppercase" <?php selected($title_transform, 'uppercase'); ?>>Uppercase</option>
                <option value="lowercase" <?php selected($title_transform, 'lowercase'); ?>>Lowercase</option>
            </select>
        </div>

        <div class="element">
            <label for="page_background">Page Background:</label>
            <input type="color" id="page_background" name="page_background" value="<?php echo esc_attr($page_background); ?>">
        </div>

    </div>
<?php
}


// Register the new sidebar meta box (now called "sidebox")
function newsbuilder_add_sidebox_meta_box() {
    add_meta_box(
        'newsbuilder_sidebox_meta_box',
        'Sidebar Custom Fields',
        'newsbuilder_display_sidebox_meta_box',
        'newsbuilder_news',
        'side',  // This makes it appear in the right sidebar
        'default'
    );
}


add_action('edit_form_after_editor', 'newsbuilder_display_custom_fields_flat');

function console($obj) {
    $js = json_encode($obj);
    print_r('<script>console.log(' . $js . ')</script>');
}

// Display the meta box
function newsbuilder_display_custom_fields_flat($post) {
    $post_id = $post->ID;
    $post_items = get_post_meta($post->ID, 'post_items', true);
    $layout = get_post_meta($post->ID, 'layout', true) ?: get_option('newsbuilder_layout');
    $default_title_color  = get_post_meta($post_id, 'primary_color', true);
    $default_text_color = get_post_meta($post_id, 'text_color', true);
    $default_background_color = "transparent";

  

?>
    <div class="itemsWrapper <?php echo $layout; ?>">
        <?php
        // Assuming $post_items is already unserialized and available as an array
        if (is_serialized($post_items)) {
            $post_items = unserialize($post_items);
        }

        // console($post_items);
        if (is_array($post_items)) {
            foreach ($post_items as $item) {

                // Create a NewsletterObject instance for each item
                $newsletterObject = new NewsletterObject(
                    $item['id'] ?? null,
                    $item['thumbnail'] ?? null,
                    $item['url'] ?? null,
                    $item['header'] ?? null,
                    $item['abstract'] ?? null,
                    $item['titleColor'] ?? $default_title_color,
                    $item['textColor'] ?? $default_text_color,
                    $item['backgroundColor'] ?? $default_background_color,
                    $item['columnSpan'] ?? 1,
                    $item['rowSpan'] ?? 1
                );


                // Generate the HTML using the toScreenHTML method
                echo $newsletterObject->toFormFields();
            }
        }
        ?>
    </div>
    <div class="selector">
       <?php

create_select2_menu();
?>
    </div>
    </div>

<?php
}


add_action('wp_ajax_fetch_post_details', 'fetch_post_details');

function fetch_post_details() {
   
    $main_post_id=$_POST['main_post_id'];
    $default_title_color  = get_post_meta($main_post_id, 'primary_color', true);
    $default_text_color   = get_post_meta($main_post_id, 'text_color', true);
    $default_background_color = "transparent";
    $post_id = $_POST['post_id'];
    //$titleTransform   = get_post_meta($main_post_id, 'title_transform', true);
 

   
    $post = get_post($post_id);
    $thumbnail = get_the_post_thumbnail_url($post_id, 'newsletter-thumb');
    $abstract = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', ($post->post_content));
    $abstract = wp_trim_words($abstract, 32);
    $link = get_permalink($post_id);
    $title = $post->post_title;

    $newsletterObject = new NewsletterObject(
        $post_id,
        $thumbnail,
        $link,
        $title,
        $abstract,
        $default_title_color,
        $default_text_color,
        $default_background_color,
        1,
        1
    );

    // Generate HTML using toFormFields
    $html = $newsletterObject->toFormFields();

    $response = array(
        'html' => $html
    );

    // Output JSON
    echo json_encode($response);
    wp_die();
}

// Hook for saving custom fields
add_action('save_post', 'newsbuilder_save_custom_fields');

// Hook for AJAX action
add_action('wp_ajax_save_post_items', 'save_post_items');

// Function to save custom fields
function newsbuilder_save_custom_fields($post_id) {
    // Check if it's the correct post type
    if (get_post_type($post_id) !== 'newsbuilder_news') {
        return;
    }

    // Debugging: Check if function is triggered
   // error_log("Function newsbuilder_save_custom_fields triggered." . $post_id);

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Debugging: Check POST data
    //error_log("POST data: " . print_r($_POST, true));

    // Save each custom field
    $result = update_post_meta($post_id, 'pre_header', sanitize_text_field($_POST['pre_header']));
 
    $result = update_post_meta($post_id, 'columns', intval($_POST['columns']));
 
    $result = update_post_meta($post_id, 'text_color', sanitize_hex_color($_POST['text_color']));
    //error_log("Saving text_color: " . var_export($result, true));
    update_post_meta($post_id, 'primary_color', sanitize_hex_color($_POST['primary_color']));
    update_post_meta($post_id, 'secondary_color', sanitize_hex_color($_POST['secondary_color']));
    update_post_meta($post_id, 'font_selection', sanitize_text_field($_POST['font_selection']));
    update_post_meta($post_id, 'layout', sanitize_text_field($_POST['layout']));
    update_post_meta($post_id, 'email_header', sanitize_text_field($_POST['email_header']));

    update_post_meta($post_id, 'title_transform', sanitize_text_field($_POST['title_transform']));
    update_post_meta($post_id, 'page_background', sanitize_text_field($_POST['page_background']));
    if (array_key_exists('hidden_textarea', $_POST)) {
        // Update the post's main content
        $post_content = $_POST['hidden_textarea'];
        $post = array(
            'ID' => $post_id,
            'post_content' => $post_content,
        );
        remove_action('save_post', 'newsbuilder_save_custom_fields');
        wp_update_post($post);
        add_action('save_post', 'newsbuilder_save_custom_fields');
    }
}

// Function to handle AJAX request
function save_post_items() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['security'], 'newsbuilder')) {
        wp_send_json_error('Nonce verification failed');
        exit;
    }

    // Get post ID and items from AJAX request
    $post_id = intval($_POST['post_id']);
    $post_items = json_decode(stripslashes($_POST['post_items']), true);

    // Log for debugging
    ///error_log("Post ID: $post_id");
    ///error_log("Post Items: " . print_r($post_items, true));

    // Perform some validation here if needed

    // Save post items as post meta
    if ($post_id > 0 && !empty($post_items)) {
        update_post_meta($post_id, 'post_items', $post_items);
        wp_send_json_success('Post items saved successfully');
    } else {
        wp_send_json_error('Invalid data');
    }
}



function unserialize_corrupted(string $str): array {
    // Fix serialized array with unquoted strings
    if (preg_match('/^(a:\d+:{)/', $str)) {
        preg_match_all('/(s:\d+:(?!").+(?!");)/U', $str, $pm_corruptedStringValues);

        foreach ($pm_corruptedStringValues[0] as $_corruptedStringValue) {
            // Get post string data
            preg_match('/^(s:\d+:)/', $_corruptedStringValue, $pm_strBase);

            // Get unquoted string
            $stringValue = substr($_corruptedStringValue, strlen($pm_strBase[0]), -1);
            // Rebuild serialized data with quoted string
            $correctedStringValue = "$pm_strBase[0]\"$stringValue\";";

            // replace corrupted data
            $str = str_replace($_corruptedStringValue, $correctedStringValue, $str);
        }
    }

    // Fix offset error
    $str = preg_replace_callback(
        '/s:(\d+):\"(.*?)\";/',
        function ($matches) {
            return "s:" . strlen($matches[2]) . ':"' . $matches[2] . '";';
        },
        $str
    );

    $unserializedString = unserialize($str);

    if ($unserializedString === false) {
        // Return empty array if string can't be fixed
        $unserializedString = array();
    }

    return $unserializedString;
}



function add_inline_editor_and_featured_image() {
    global $post;
 
    // Include the saved header for newsbuilder
    $newsbuilder_header = get_option('newsbuilder_header_content'); // Replace with the actual option name

    echo '<div class="newsbuilder-wrapper">';

    // Display the saved header from options
    if ($newsbuilder_header) {
        echo '<div class="newsbuilder-header">' . $newsbuilder_header . '</div>';
    }

    // Display the title as an input field
    echo '<p class="email_title"><span>Newsletter | </span><input type="text" name="post_title" id="post_title" value="' . esc_attr(get_the_title($post->ID)) . '" /></p>';

    // Display the featured image
    if (has_post_thumbnail($post->ID)) {
        echo get_the_post_thumbnail($post->ID, 'medium_large');
    }
    // Add extra input for email_header
    $email_header = get_post_meta($post->ID, 'email_header', true);
      echo '<input type="text" id="email_header" name="email_header" class="email_header primary_color" value="' . esc_attr($email_header) . '" />';

    // Display the inline editor
?>
    <div id="main_editor" contenteditable="true">
        <?php echo apply_filters('the_content', $post->post_content); ?>
    </div>
    <textarea id="hidden_textarea" name="hidden_textarea" style="display:none;"></textarea>
<?php
    //echo '</div>';  // Close the newsbuilder-wrapper div
}


add_action('edit_form_after_title', 'add_inline_editor_and_featured_image');
 
function create_select2_menu() {
    $args = array(
        'post_type' => array('post', 'page', 'product'),
        'posts_per_page' => -1,
        'post_status' => array('publish', 'draft'),
        'meta_key' => '_thumbnail_id', // this ensures the post has a featured image
        'orderby' => 'date',
        'order' => 'DESC'
    );
    $query = new WP_Query($args);
    $posts = $query->posts;
    $post_options = '';
    $page_options = '';
    $product_options = '';
    foreach ($posts as $post) {
        if (has_post_thumbnail($post->ID)) {
            $option = '<option value="' . $post->ID . '"';
            if ($post->post_status == 'draft') {
                $option .= ' class="draft"';
            }
            $option .= '>' . get_the_date('Y-m-d', $post->ID) . ' - ' . $post->post_title . '</option>';
            if ($post->post_type == 'post') {
                $post_options .= $option;
            } elseif ($post->post_type == 'page') {
                $page_options .= $option;
            } elseif ($post->post_type == 'product') {
                $product_options .= $option;
            }
        }
    }
    $menu = ' <select class="new selectie" id="selectie">
    <option value="" disabled selected>Select an item</option>
    ';
    if ($post_options) {
        $menu .= '<optgroup label="Blog Posts (news)">' . $post_options . '</optgroup>';
    }
    if ($page_options) {
        $menu .= '<optgroup label="Pages">' . $page_options . '</optgroup>';
    }
    if ($product_options) {
        $menu .= '<optgroup label="Products">' . $product_options . '</optgroup>';
    }
    $menu .='<optgroup label="special"><option value="Header">Page-wide Header</option></optgroup>';
    $menu .= '</select>';
    echo $menu;
}

class NewsletterObject {
    private $id;
    private $thumbnail;
    private $url;
    private $header;
    private $abstract;
    private $titleColor;
    private $textColor;
    private $backgroundColor;
    private $columnSpan;
    private $rowSpan;
    private $textTransform;  

    // Constructor to initialize properties
    public function __construct($id, $img, $url, $header, $abstract, $titleColor, $textColor, $backgroundColor, $columnSpan, $rowSpan) {
        global $post;
        $this->id = $id;
        $this->thumbnail = $img;
        $this->url = $url;
        $this->header = $header;
        $this->abstract = $abstract;
        $this->titleColor = $titleColor;
        $this->textColor = $textColor;
        $this->backgroundColor = ($backgroundColor?$backgroundColor:'transparent');
        $this->columnSpan = $columnSpan;
        $this->rowSpan = $rowSpan;
        $post_id = $post->ID;
        $this->textTransform = get_post_meta($post_id, 'title_transform', 'none'); // 'none' is the default value
    }

    // Method to generate a dynamic HTML form based on object properties
    public function toFormFields() {
        
        $formHTML = '<div class="item" id="m_' . $this->id . '" data-column-span="' . ($this->columnSpan ? $this->columnSpan : 1) . '"
         style="background-color: '.$this->backgroundColor .'">';
        $formHTML .= '<div class="inner-content">';
        $formHTML .= '<div class="menu-button">';
        $formHTML .= '<div class="dashicons dashicons-arrow-down-alt2"></div>';
        $formHTML .= '<div class="menu">';
        $formHTML .= '<button class="delete-item"><span class="dashicons dashicons-no"></span> Delete Item</button>';
        $formHTML .= '<div class="toggle-option"><input type="checkbox" id="headerToggle"  ' . (isset($this->header) ? 'checked' : '') . '><label for="headerToggle">Header</label></div>';
        $formHTML .= '<div class="toggle-option"><input type="checkbox" id="abstractToggle" ' . (isset($this->abstract) ? 'checked' : '') . '><label for="abstractToggle">Abstract</label></div>';
        $formHTML .= '<div class="toggle-option"><input type="checkbox" id="urlToggle"' . (isset($this->url) ? 'checked' : '') . '><label for="urlToggle">URL</label></div>';
        $formHTML .= '<div class="number-input"><label for="columnSpan">Column Span: </label><input type="number" min="1" max="5" name="columnSpan" id="columnSpan" value="' . $this->columnSpan . '"></div>';
        $formHTML .= '<div class="number-input"><label for="rowSpan">Row Span: </label><input type="number" id="rowSpan" name="rowpan" value="' . $this->rowSpan . '"></div>';

        $formHTML .= '<div class="color-input"><label for="titleColor">Title Color: </label><input type="color" id="TitleColor"  name="titleColor" value="' . $this->titleColor . '"></div>';
        $formHTML .= '<div class="color-input"><label for="textColor">Text Color: </label><input type="color" id="textColor" name="textColor" value="' . $this->textColor . '"></div>';
        $formHTML .= '<div class="color-input bgcolor"><label for="bgColor">Background: </label><input type="'.($this->backgroundColor =='transparent'?"text":"color") .'" id="bgColor"  name="backgroundColor" value="' . ($this->backgroundColor?$this->backgroundColor:'transparent') . '"></div>';

        $formHTML .= '</div>';
        $formHTML .= '</div>';

        if ($this->thumbnail) $formHTML .= '<img alt="' . $this->header . '" src="' . $this->thumbnail . '">';
        $formHTML .= '<input type="hidden" name="id" value="' . $this->id . '">';
        $formHTML .= '<input type="hidden" name="thumbnail" value="' . $this->thumbnail . '">';
        

        if ($this->titleColor) $styling ='color:'.$this->titleColor.';'; else $styling='';
        //$styling .= 'text-transform: '.$this-> textTransform.';';
       

        if ($this->header) $formHTML .= '<textarea class="newsTitle primary_color" name="header" style="'.$styling.'">' . $this->header . '</textarea>';
        if ($this->textColor) $styling = 'style="color:'.$this->textColor.'"'; else $styling="";
        if ($this->abstract) $formHTML .= '<textarea class="abstract" name="abstract" '.$styling .'>' . $this->abstract . '</textarea>';
        if ($this->url) $formHTML .= '<div class="urlObject">';
        if ($this->url) $formHTML .= '<input type="hidden" name="url" value="' . $this->url . '"><a href="' . $this->url . '" class="readmore secondary_color"><span class="more ">Read More &gt;</span></a></div>';
        $formHTML .= '</div></div>';
        return $formHTML;
    }


    // Method to generate screen-readable HTML
    public function toScreenHTML() {
        $html = '<div class="item" id="' . $this->id . '" data-column-span="' . ($this->columnSpan ? $this->columnSpan : 1) . '" style="background-color:' . $this->backgroundColor . '">';
        
        $html .= '<div class="inner-content">';


        if (isset($this->thumbnail) && strlen($this->thumbnail)> 8 ) {
            $html .= '<div class="thumb">';
            $html .= '<img alt="' . $this->header . '" src="' . $this->thumbnail . '">';
            $html .= '</div>';
        }
        if ($this->titleColor) $styling ='color:'.$this->titleColor.';'; else $styling='';
        $styling .= 'text-transform: '.$this -> textTransform.';';

        $html .= '<h2 class="newstitle primary_color" style="'.$styling.'">' . $this->header . '</h2>';

        if (isset($this->abstract)) {
            $html .= '<div class="abstractarea">';
            $html .= '<div class="abstract mce-content-body"  style="color:' . $this->textColor . '">';
            $html .= $this->abstract;
            $html .= '</div>';
            $html .= '</div>';
        }
        if (isset($this->url)) {
            $html .= '<div class="urlObject">';
             $html .= '<a href="' . $this->url . '" class="readmore secondary_color"><span class="more ">Read More &gt;</span></a>';
            $html .= "</div>";
        }

        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    // Method to convert object to email HTML
    public function toEmailHTML() {
        return "<div id='{$this->id}'><h1>{$this->header}</h1></div>";
    }

    // Method to convert object to email text
    public function toEmailText() {
        return "ID: {$this->id}\nTitle: {$this->header}";
    }
}
