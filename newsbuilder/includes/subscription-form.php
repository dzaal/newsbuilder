<?php
// Prevent direct access to the file
defined('ABSPATH') or die('Direct script access disallowed.');

// Register the email subscription form shortcode
function newsbuilder_subscription_form_shortcode($atts) {
    ob_start();
    ?>
    <form id="newsbuilder-subscription-form" method="post" action="">
        <label for="subscriber-email">Subscribe to our newsletter:</label>
        <input type="email" id="subscriber-email" name="subscriber_email" required>
        <input type="submit" value="Subscribe">
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('newsbuilder_subscription_form', 'newsbuilder_subscription_form_shortcode');

// Handle subscription form submissions
function newsbuilder_handle_subscription_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subscriber_email'])) {
        $email = sanitize_email($_POST['subscriber_email']);
        
        // Add the email to your subscribers list
        // You can save it in the WordPress database or send it to an external service
        // ...

        // Optionally, send a confirmation email or redirect the user
        // ...
    }
}
add_action('init', 'newsbuilder_handle_subscription_form_submission');
