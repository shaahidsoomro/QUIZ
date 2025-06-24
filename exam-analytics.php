# Create the file exam-analytics.php for admin analytics dashboard

exam_analytics_code = """
<?php
/**
 * Admin Analytics Dashboard for Exams and MCQs
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 * Email: shahidsoomro786@gmail.com
 */

if (!defined('ABSPATH')) exit;

function mcqs_exam_analytics_page() {
    global $wpdb;

    $attempts_table = $wpdb->prefix . 'mcq_exam_attempts';

    // Fetch total attempts
    $total_attempts = $wpdb->get_var("SELECT COUNT(*) FROM $attempts_table");

    // Fetch average score per exam
    $average_scores = $wpdb->get_results("
        SELECT exam_id, AVG(score) as avg_score, COUNT(*) as total
        FROM $attempts_table
        GROUP BY exam_id
    ");

    // Fetch top scorers
    $top_scorers = $wpdb->get_results("
        SELECT u.display_name, a.score, e.post_title AS exam
        FROM $attempts_table a
        JOIN {$wpdb->users} u ON u.ID = a.user_id
        JOIN {$wpdb->posts} e ON e.ID = a.exam_id
        ORDER BY a.score DESC
        LIMIT 10
    ");

    echo '<div class="wrap"><h1>📊 Exam & MCQ Analytics</h1>';
    echo '<p><strong>Total Exam Attempts:</strong> ' . intval($total_attempts) . '</p>';

    echo '<h2>📘 Average Score per Exam</h2><table class="widefat"><thead><tr><th>Exam</th><th>Average Score</th><th>Total Attempts</th></tr></thead><tbody>';
    foreach ($average_scores as $row) {
        $exam_title = get_the_title($row->exam_id);
        echo '<tr><td>' . esc_html($exam_title) . '</td><td>' . round($row->avg_score, 2) . '</td><td>' . intval($row->total) . '</td></tr>';
    }
    echo '</tbody></table>';

    echo '<h2>🏆 Top Scorers</h2><table class="widefat"><thead><tr><th>User</th><th>Exam</th><th>Score</th></tr></thead><tbody>';
    foreach ($top_scorers as $scorer) {
        echo '<tr><td>' . esc_html($scorer->display_name) . '</td><td>' . esc_html($scorer->exam) . '</td><td>' . intval($scorer->score) . '</td></tr>';
    }
    echo '</tbody></table>';
    echo '</div>';
}

// Hook into WP Admin Menu
add_action('admin_menu', function () {
    add_submenu_page(
        'edit.php?post_type=mcq',
        'Exam Analytics',
        'Exam Analytics',
        'manage_options',
        'exam-analytics',
        'mcqs_exam_analytics_page'
    );
});
"""

# Save this file
with open("/mnt/data/exam-analytics.php", "w", encoding="utf-8") as f:
    f.write(exam_analytics_code)

"✅ Created 'exam-analytics.php' — Admin dashboard for exam attempts, average scores, and top scorers."
