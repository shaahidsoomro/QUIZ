<?php
/**
 * Handles exam session – save score, answers, etc.
 */

if (!defined('ABSPATH')) exit;

class MCQ_Exam_Session {

    public function __construct() {
        add_action('init', [$this, 'handle_exam_submission']);
    }

    public function handle_exam_submission() {
        if (isset($_POST['mcq_exam_submit'])) {
            if (!is_user_logged_in()) return;

            global $wpdb;
            $user_id = get_current_user_id();
            $exam_id = intval($_POST['exam_id']);
            $answers = maybe_serialize($_POST['answers']);
            $score = intval($_POST['score']);

            $wpdb->insert("{$wpdb->prefix}mcq_exam_attempts", [
                'user_id' => $user_id,
                'exam_id' => $exam_id,
                'answers' => $answers,
                'score' => $score,
                'started_at' => current_time('mysql'),
                'completed_at' => current_time('mysql')
            ]);

            wp_redirect(add_query_arg(['exam_result' => 'success'], site_url('/exam-result')));
            exit;
        }
    }
}

new MCQ_Exam_Session();
?>