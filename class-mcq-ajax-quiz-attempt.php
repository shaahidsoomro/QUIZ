# Create a new file for handling AJAX quiz attempt email trigger in PHP
ajax_email_php = """
<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class MCQ_AJAX_Quiz_Trigger {

    public function __construct() {
        add_action('wp_ajax_mcq_quiz_attempt', array($this, 'handle_quiz_attempt'));
        add_action('wp_ajax_nopriv_mcq_quiz_attempt', array($this, 'handle_quiz_attempt'));
    }

    public function handle_quiz_attempt() {
        // Verify nonce
        if ( ! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'mcq_quiz_nonce') ) {
            wp_send_json_error('Invalid security token');
        }

        // Validate user
        if ( ! is_user_logged_in() ) {
            wp_send_json_error('User not logged in');
        }

        $user_id = get_current_user_id();
        $quiz_title = sanitize_text_field($_POST['quiz_title']);

        // Fire the action that triggers the email
        do_action('mcq_quiz_attempted', $user_id, $quiz_title);

        wp_send_json_success('Email triggered');
    }
}

new MCQ_AJAX_Quiz_Trigger();
"""

# Save the AJAX handler class
ajax_handler_path = "/mnt/data/MCQS_Maker_Plugin/WP-MCQS-Maker-REAL-FINAL/includes/class-mcq-ajax-quiz-attempt.php"
with open(ajax_handler_path, "w") as f:
    f.write(ajax_email_php)

ajax_handler_path
