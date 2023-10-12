<?php
// Optionally include the standard site header
get_header(); 

global $post;

$post_id = get_the_ID(); 
 
 
$post_items = get_post_meta($post_id, 'post_items', true);

$layout = get_post_meta($post_id, 'layout', true) ?: get_option('newsbuilder_layout');
 
$textColor = get_post_meta($post_id, 'text_color', true);
$columns = get_post_meta($post_id, 'columns', true);
$font_selection  = get_post_meta($post_id, 'font_selection', true); 
$primary_color   = get_post_meta($post_id, 'primary_color', true); 
$secondary_color = get_post_meta($post_id, 'secondary_color', true); 
$titleTransform  = get_post_meta($post_id, 'title_transform', true);
$pageBackground  = get_post_meta($post_id, 'page_background', true);
$default_background_color = "#ffffff";
$email_header    = get_post_meta($post_id, 'email_header', true);
// Fetch all published newsletters
$args = array(
    'post_type' => 'newsbuilder_news', 
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC'
);
$newsletters = get_posts($args);
// Custom styling for content area
echo '<div class="newsbuilder_wrapper">';
// Generate select box
echo '<div class="select-newsletter-wrapper">';
echo '<label for="select-newsletter">Select Newsletter:</label>';
echo '<select id="select-newsletter">';
$last_year = null;
foreach ($newsletters as $newsletter) {
    $year = get_the_date('Y', $newsletter);
    $formatted_date = get_the_date('M d', $newsletter);
    if ($last_year !== $year) {
        if ($last_year !== null) {
            echo '</optgroup>';
        }
        echo '<optgroup label="' . $year . '">';
    }
    echo '<option value="' . get_permalink($newsletter) . '"><span class="option-date">' . $formatted_date . '</span> ' . get_the_title($newsletter) . '</option>';
    $last_year = $year;
}
echo '</optgroup>';
echo '</select>';
echo '</div>';

echo '<div class="newsbuilder_news" style="background-color: '.$pageBackground.'">';




// Include the saved header for newsbuilder
$newsbuilder_header = get_option('newsbuilder_header_content'); // Replace with the actual option name
if ($newsbuilder_header) {
    echo '<div class="newsbuilder-header">' . $newsbuilder_header . '</div>';
}
// Display the post title
the_title('<p class="email_title">Newsletter | ', '</p>');
// Display the featured image
if (has_post_thumbnail()) {
    the_post_thumbnail();
}
echo '<h1 class="email_header">'.$email_header.'</h1>';

echo '<div class="main_content">';
the_content();
echo '</div>';
// Loop through the post items and display them using toHTML()
// Assuming $post_items is the PHP object containing your post items
echo '<div class="newsWrapper" style="grid-template-columns: repeat('.$columns.', 1fr)">';
//$post_items = get_post_meta(get_the_ID(), 'post_items', true); // Replace with the actual meta key
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
            $item['titleColor'] ?? $title_color,
            $item['textColor'] ?? $text_color,
            $item['backgroundColor'] ?? $page_background,
            $item['columnSpan'] ?? 1,
            $item['rowSpan'] ?? 1
        );


        // Generate the HTML using the toScreenHTML method
        echo $newsletterObject->toScreenHTML();
    }
}
echo '</div>';
// Include the saved footer for newsbuilder
$newsbuilder_footer = get_option('newsbuilder_footer_content'); // Replace with the actual option name
if ($newsbuilder_footer) {
    echo '<div class="newsbuilder-footer">' . $newsbuilder_footer . '</div>';
}

echo '</div>'; // Close the custom content area
echo '</div>'; 

echo "
<script>
var textColor    = '$textColor';
var primaryColor = '$primary_color';
var secondaryColor = '$secondary_color';
var fontSelection = '$font_selection';
var numberOfColumns = '$secondary_color';
var titleTransform = '$titleTransform';
var pageBackground ='$pageBackground';
var numberOfColumns = '$columns';
// Update styles
document.documentElement.style.setProperty('--primary-color', primaryColor);
document.documentElement.style.setProperty('--secondary-color', secondaryColor);
document.documentElement.style.setProperty('--text-color', textColor);
document.documentElement.style.setProperty('--font-selection', fontSelection);
document.documentElement.style.setProperty('--numberofcolumns', numberOfColumns);
document.documentElement.style.setProperty('--title-transform', titleTransform);
document.documentElement.style.setProperty('--page-background', pageBackground);

document.addEventListener('DOMContentLoaded', function() {
    const selectNewsletter = document.getElementById('select-newsletter');
    selectNewsletter.addEventListener('change', function() {
        window.location.href = this.value;
    });
});

</script>";




global $wpdb;

// Query to get an option by its name
$header_option = $wpdb->get_var(
    $wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name = %s", 'newsbuilder_header')
);

$footer_option = $wpdb->get_var(
    $wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name = %s", 'newsbuilder_footer')
);

 


// Optionally include the standard site footer
get_footer();
