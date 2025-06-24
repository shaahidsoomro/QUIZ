corrected_plugin_file = """
<?php
/**
 * Plugin Name: Pak Study Quiz Manager
 * Description: A comprehensive plugin for managing MCQs, exams, analytics, and student dashboards – built for PAKSTUDY.XYZ
 * Version: 1.0.0
 * Author: PAKSTUDY.XYZ Team
 * Author URI: https://pakstudy.xyz
 * Email: shahidsoomro786@gmail.com
 * License: GPL2
 */

if (!defined('ABSPATH')) exit;

// 🔁 Include all required files from /includes directory
$includes = [
    'class-mcq-post-type.php',
    'class-mcq-taxonomy.php',
    'class-mcq-meta-fields.php',
    'class-mcq-admin-dashboard.php',
    'class-mcq-admin-interface.php',
    'class-mcq-ajax-quiz-attempt.php',
    'class-mcq-email-notifications.php',
    'class-mcq-import-export.php',
    'class-mcq-tracker.php',
    'class-mcq-reporting.php',
    'class-mcq-user-dashboard.php',
    'class-exam-manager.php',
    'class-exam-session.php',
    'exam-analytics.php',
    'mcq-analytics.php',
    'exam-result.php',
    'quiz-summary.php',
    'sample-exam-loader.php',
    'shortcode-mcq-quiz.php',
    'shortcode-exam.php',
    'frontend-exam-ui.php',
    'db-mcq-exams-migration.php'
];

foreach ($includes as $file) {
    $path = plugin_dir_path(__FILE__) . 'includes/' . $file;
    if (file_exists($path)) require_once $path;
}

// 🎨 Enqueue Admin Styles
function pakstudy_admin_styles() {
    wp_enqueue_style('pakstudy-admin-css', plugin_dir_url(__FILE__) . 'assets/css/admin.css', [], '1.0');
}
add_action('admin_enqueue_scripts', 'pakstudy_admin_styles');

// 🎨 Enqueue Frontend Popup Styles and JS
function pakstudy_enqueue_assets() {
    wp_enqueue_style('pakstudy-popup-css', plugin_dir_url(__FILE__) . 'assets/css/popup.css', [], '1.0');
    wp_enqueue_script('pakstudy-js', plugin_dir_url(__FILE__) . 'assets/js/pakstudy.js', ['jquery'], '1.0', true);
}
add_action('wp_enqueue_scripts', 'pakstudy_enqueue_assets');

// 🧭 Admin Menu with Icons
add_action('admin_menu', function () {
    add_menu_page('📘 MCQs Manager', 'MCQs', 'manage_options', 'mcqs_manager', 'render_mcqs_admin_dashboard', 'dashicons-welcome-learn-more', 6);
    add_submenu_page('mcqs_manager', '🧪 Exam Manager', '🧪 Exam Manager', 'manage_options', 'exam_manager', 'render_exam_manager');
    add_submenu_page('mcqs_manager', '📊 Exam Analytics', '📊 Exam Analytics', 'manage_options', 'exam_analytics', 'render_exam_analytics');
    add_submenu_page('mcqs_manager', '📘 MCQ Analytics', '📘 MCQ Analytics', 'manage_options', 'mcq_analytics', 'render_mcq_analytics');
    add_submenu_page('mcqs_manager', '👤 Student Dashboard', '👤 Student Dashboard', 'manage_options', 'mcq_user_dashboard', 'render_mcq_user_dashboard');
});

// 🧱 Plugin Activation: Create Tables
register_activation_hook(__FILE__, function () {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $exam_table = $wpdb->prefix . 'mcq_exams';
    $attempt_table = $wpdb->prefix . 'mcq_exam_attempts';

    $sql1 = "CREATE TABLE IF NOT EXISTS $exam_table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        duration INT,
        disclaimer TEXT,
        categories LONGTEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    $sql2 = "CREATE TABLE IF NOT EXISTS $attempt_table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT NOT NULL,
        exam_id BIGINT NOT NULL,
        answers LONGTEXT,
        score INT DEFAULT 0,
        started_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        completed_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql1);
    dbDelta($sql2);
});
"""
with open("/mnt/data/pakstudy-quiz-manager.php", "w", encoding="utf-8") as f:
    f.write(corrected_plugin_file)

"/mnt/data/pakstudy-quiz-manager.php regenerated with all logic correctly integrated."

