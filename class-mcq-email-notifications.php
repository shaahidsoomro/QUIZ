<?php
/**
 * Handles user-related email notifications in MCQS Maker
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class MCQ_Email_Notifications {

    public function __construct() {
        add_action('user_register', [$this, 'send_welcome_email'], 10, 1);
        add_action('mcq_quiz_attempted', [$this, 'send_quiz_attempt_email'], 10, 2);
        add_action('mcq_check_abandoned_quizzes', [$this, 'check_abandoned_quizzes']);

        if (!wp_next_scheduled('mcq_check_abandoned_quizzes')) {
            wp_schedule_event(time(), 'hourly', 'mcq_check_abandoned_quizzes');
        }
    }

    private function get_footer() {
        return "\n\n" . __('Best regards,', 'pakstudy-quiz-manager') . "\n" .
               __('PAKSTUDY.XYZ Team', 'pakstudy-quiz-manager') . "\n" .
               __('Visit us:', 'pakstudy-quiz-manager') . ' ' . home_url();
    }

    public function send_welcome_email($user_id) {
        $user_info = get_userdata($user_id);
        $to = $user_info->user_email;
        $subject = __('Welcome to MCQS Maker!', 'pakstudy-quiz-manager');
        $message = sprintf(
            __("Hello %s,\n\nThanks for registering at MCQS Maker. Start attempting quizzes and track your progress today.", 'pakstudy-quiz-manager'),
            esc_html($user_info->first_name)
        );
        $message .= $this->get_footer();

        wp_mail($to, $subject, $message);
    }

    public function send_quiz_attempt_email($user_id, $quiz_title) {
        $user_info = get_userdata($user_id);
        $to = $user_info->user_email;
        $subject = __('Quiz Attempted Successfully', 'pakstudy-quiz-manager');
        $message = sprintf(
            __("Hi %s,\n\nYou have successfully completed the quiz: %s", 'pakstudy-quiz-manager'),
            esc_html($user_info->first_name),
            esc_html($quiz_title)
        );
        $message .= $this->get_footer();

        wp_mail($to, $subject, $message);
    }

    public function check_abandoned_quizzes() {
        // Placeholder: You may log, check user sessions, or remove stale attempts here.
        error_log('[MCQS Maker] Cron: Abandoned quiz check triggered.');
    }
}

// Instantiate the class
new MCQ_Email_Notifications();
