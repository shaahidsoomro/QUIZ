<?php
/**
 * Frontend Exam UI – Lists available exams for logged-in users and launches the exam
 * Shortcode: [mcq_exam_list]
 */

if (!defined('ABSPATH')) exit;

function render_exam_list_ui() {
    if (!is_user_logged_in()) {
        return '<p>You must be <a href="' . wp_login_url() . '">logged in</a> to view available exams.</p>';
    }

    global $wpdb;
    $exams = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mcq_exams ORDER BY created_at DESC");

    ob_start();
    echo '<div class="exam-list"><h2>Available Exams</h2><ul>';
    foreach ($exams as $exam) {
        $start_link = esc_url(add_query_arg(['exam_id' => $exam->id], site_url('/start-exam')));
        echo '<li><strong>' . esc_html($exam->title) . '</strong> (' . intval($exam->duration) . ' min) – <a class="button" href="' . $start_link . '">Start</a></li>';
    }
    echo '</ul></div>';
    return ob_get_clean();
}

add_shortcode('mcq_exam_list', 'render_exam_list_ui');
?>