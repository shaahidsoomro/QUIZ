<?php
/**
 * Displays admin-side reports of user quiz attempts.
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class MCQ_Reporting {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_report_menu']);
    }

    public function add_report_menu() {
        add_submenu_page(
            'edit.php?post_type=mcq',
            __('MCQ Attempt Reports', 'pakstudy-quiz-manager'),
            __('MCQ Reports', 'pakstudy-quiz-manager'),
            'manage_options',
            'mcq-reports',
            [$this, 'render_report_page']
        );
    }

    public function render_report_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permission to view this page.', 'pakstudy-quiz-manager'));
        }

        global $wpdb;
        $table = $wpdb->prefix . 'mcq_attempts';

        $results = $wpdb->get_results("
            SELECT a.*, u.display_name, p.post_title
            FROM {$table} a
            LEFT JOIN {$wpdb->users} u ON a.user_id = u.ID
            LEFT JOIN {$wpdb->posts} p ON a.mcq_id = p.ID
            ORDER BY attempted_at DESC
            LIMIT 100
        ");

        echo '<div class="wrap"><h1>' . esc_html__('MCQ Attempt Reports', 'pakstudy-quiz-manager') . '</h1>';
        echo '<table class="widefat striped"><thead><tr>
            <th>' . esc_html__('User', 'pakstudy-quiz-manager') . '</th>
            <th>' . esc_html__('MCQ', 'pakstudy-quiz-manager') . '</th>
            <th>' . esc_html__('Selected', 'pakstudy-quiz-manager') . '</th>
            <th>' . esc_html__('Correct', 'pakstudy-quiz-manager') . '</th>
            <th>' . esc_html__('Result', 'pakstudy-quiz-manager') . '</th>
            <th>' . esc_html__('Date', 'pakstudy-quiz-manager') . '</th>
            </tr></thead><tbody>';

        if ($results) {
            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . esc_html($row->display_name) . '</td>';
                echo '<td><a href="' . esc_url(get_edit_post_link($row->mcq_id)) . '">' . esc_html($row->post_title) . '</a></td>';
                echo '<td>' . esc_html($row->selected_option) . '</td>';
                echo '<td>' . esc_html($row->correct_option) . '</td>';
                echo '<td>' . ($row->is_correct ? '<span style="color:green;">✔</span>' : '<span style="color:red;">✖</span>') . '</td>';
                echo '<td>' . esc_html(date_i18n(get_option('date_format') . ' H:i', strtotime($row->attempted_at))) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="6">' . esc_html__('No attempts found.', 'pakstudy-quiz-manager') . '</td></tr>';
        }

        echo '</tbody></table></div>';
    }
}

new MCQ_Reporting();
