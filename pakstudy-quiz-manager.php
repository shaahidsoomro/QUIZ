<?php
/**
 * Plugin Name: PakStudy Quiz Manager
 * Plugin URI: https://pakstudy.xyz
 * Description: Custom MCQ Quiz plugin for PakStudy.XYZ — create, manage, import/export, and display quizzes.
 * Version: 1.1
 * Author: Shahid Hussain Soomro
 * Author URI: https://pakstudy.xyz
 * Text Domain: pakstudy-quiz-manager
 * License: GPL2
 * 
 * Powered by PakStudy.XYZ. Designed and developed by Shahid Hussain Soomro.
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-post-type.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-meta-fields.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-import-export.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-admin-interface.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-taxonomy.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-tracker.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-reporting.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-admin-dashboard.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-leaderboard.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-attempt-logger.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-analytics.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-attempt-reports.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-certificate.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-content-display.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode-mcq-quiz.php';

// Initialize plugin
function pakstudy_quiz_manager_init() {
    new MCQ_Post_Type();
    new MCQ_Meta_Fields();
    new MCQ_Import_Export();
    new MCQ_Admin_Interface();
    new MCQ_Taxonomy();
    new MCQ_Tracker();
    new MCQ_Reporting();
    new MCQ_Content_Display();
    new MCQ_Admin_Dashboard();
    new MCQ_Leaderboard();
    new MCQ_Analytics();
    new MCQ_Attempt_Logger();
    new MCQ_Attempt_Reports();
    new MCQ_Certificate();
}
add_action('plugins_loaded', 'pakstudy_quiz_manager_init');

// Flush rewrite rules on activation
register_activation_hook(__FILE__, function () {
    require_once plugin_dir_path(__FILE__) . 'includes/class-mcq-post-type.php';
    $type = new MCQ_Post_Type();
    $type->register_mcq_post_type();
    flush_rewrite_rules();
});
?>
