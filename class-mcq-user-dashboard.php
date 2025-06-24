<?php
/**
 * Renders a user-facing dashboard showing quiz attempts.
 *
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class MCQ_User_Dashboard {

    public function __construct() {
        add_shortcode('mcq_user_dashboard', [$this, 'render_user_dashboard']);
    }

    public function render_user_dashboard() {
        if (!is_user_logged_in()) {
            return '<p>' . sprintf(__('Please <a href="%s">log in</a> to view your quiz dashboard.', 'pakstudy-quiz-manager'), esc_url(wp_login_url())) . '</p>';
        }

        $user_id = get_current_user_id();
        global $wpdb;
        $table = $wpdb->prefix . 'mcq_user_attempts';

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT quiz_title, score, attempt_date FROM {$table} WHERE user_id = %d ORDER BY attempt_date DESC",
            $user_id
        ));

        ob_start(); ?>
        <div class="mcq-user-dashboard">
            <h2><?php _e('Your Quiz Activity', 'pakstudy-quiz-manager'); ?></h2>
            <p><?php _e('Here you can view your attempted quizzes and performance summary.', 'pakstudy-quiz-manager'); ?></p>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Quiz Title', 'pakstudy-quiz-manager'); ?></th>
                        <th><?php _e('Score', 'pakstudy-quiz-manager'); ?></th>
                        <th><?php _e('Date', 'pakstudy-quiz-manager'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($results) : ?>
                    <?php foreach ($results as $row) : ?>
                        <tr>
                            <td><?php echo esc_html($row->quiz_title); ?></td>
                            <td><?php echo esc_html($row->score); ?></td>
                            <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($row->attempt_date))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3"><?php _e('No quiz attempts found.', 'pakstudy-quiz-manager'); ?></td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }
}

new MCQ_User_Dashboard();
