<?php
/**
 * Tracks individual MCQ quiz attempts by users.
 * Stores data in custom database table `wp_mcq_attempts`.
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class MCQ_Tracker {

    public function __construct() {
        add_action('wp_ajax_submit_mcq_quiz', [$this, 'handle_quiz_submission']);
    }

    public static function create_tracking_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mcq_attempts';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            mcq_id BIGINT(20) UNSIGNED NOT NULL,
            selected_option VARCHAR(5) NOT NULL,
            correct_option VARCHAR(5) NOT NULL,
            is_correct TINYINT(1) NOT NULL,
            attempted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function handle_quiz_submission() {
        if (!is_user_logged_in() || !isset($_POST['answers']) || !is_array($_POST['answers'])) {
            wp_send_json_error(__('Unauthorized or invalid request', 'pakstudy-quiz-manager'));
        }

        // Verify nonce (optional but recommended)
        if (!isset($_POST['mcq_nonce']) || !wp_verify_nonce($_POST['mcq_nonce'], 'submit_mcq_quiz')) {
            wp_send_json_error(__('Security check failed', 'pakstudy-quiz-manager'));
        }

        global $wpdb;
        $table = $wpdb->prefix . 'mcq_attempts';
        $user_id = get_current_user_id();
        $answers = $_POST['answers'];

        foreach ($answers as $mcq_id => $selected_option) {
            $mcq_id = intval($mcq_id);
            $selected = sanitize_text_field($selected_option);
            $correct = get_post_meta($mcq_id, 'correct_option', true);
            $is_correct = ($selected === $correct) ? 1 : 0;

            $wpdb->insert($table, [
                'user_id'        => $user_id,
                'mcq_id'         => $mcq_id,
                'selected_option'=> $selected,
                'correct_option' => $correct,
                'is_correct'     => $is_correct,
                'attempted_at'   => current_time('mysql')
            ]);
        }

        wp_send_json_success(__('Quiz results saved successfully.', 'pakstudy-quiz-manager'));
    }
}

// Register DB table creation properly from main plugin file:
register_activation_hook(__FILE__, ['MCQ_Tracker', 'create_tracking_table']);

// Instantiate
new MCQ_Tracker();
