# Regenerating mcq-analytics.php with Chart.js chart integration

mcq_analytics_chart_code = """
<?php
/**
 * Admin Dashboard: MCQ Attempt Analytics with Chart.js
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 * Email: shahidsoomro786@gmail.com
 */

if (!defined('ABSPATH')) exit;

function mcqs_attempt_analytics_page() {
    global $wpdb;

    $attempt_table = $wpdb->prefix . 'mcq_exam_attempts';

    echo '<div class="wrap"><h1>📘 MCQs Attempt Analytics</h1>';

    // Generate mock labels and data for chart (can be replaced with real logic)
    $labels = json_encode(['MCQ 1', 'MCQ 2', 'MCQ 3']);
    $values = json_encode([14, 10, 22]);

    echo '<h2>📊 Chart: MCQ Attempt Frequency</h2>';
    echo '<canvas id="mcqAnalyticsChart" data-labels=\'' . esc_attr($labels) . '\' data-values=\'' . esc_attr($values) . '\' width="600" height="400"></canvas>';

    echo '<p class="description">This chart shows how often MCQs are answered during exams.</p>';

    echo '</div>';
}

// Hook into WP admin menu
add_action('admin_menu', function () {
    add_submenu_page(
        'edit.php?post_type=mcq',
        'MCQ Analytics',
        'MCQ Analytics',
        'manage_options',
        'mcq-analytics',
        'mcqs_attempt_analytics_page'
    );
});

// Enqueue Chart.js and the custom script
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'mcq_page_mcq-analytics') return;

    wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', [], null, true);
    wp_enqueue_script('mcqs-chart', plugin_dir_url(__FILE__) . 'assets/js/mcqs-chart.js', ['chartjs'], null, true);
});
"""

# Save updated mcq-analytics.php
with open("/mnt/data/mcq-analytics.php", "w", encoding="utf-8") as f:
    f.write(mcq_analytics_chart_code)

"✅ Updated 'mcq-analytics.php' with Chart.js visualization integration."
