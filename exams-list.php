<?php
/**
 * Public-facing Exam List Interface
 * Shows all exams available for attempt
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 */

if (!defined('ABSPATH')) exit;

// Enqueue frontend styles and JS
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('mcqs-frontend', plugin_dir_url(__FILE__) . '../assets/css/frontend.css');
    wp_enqueue_script('mcqs-frontend', plugin_dir_url(__FILE__) . '../assets/js/frontend.js', [], false, true);
});

add_shortcode('mcq_exam_list', function () {
    if (!is_user_logged_in()) {
        return '<div class="mcq-question-box"><strong>🔒 Please <a href="' . wp_login_url() . '">log in</a> to view available exams.</strong></div>';
    }

    $args = [
        'post_type' => 'mcq_exam',
        'post_status' => 'publish',
        'posts_per_page' => -1
    ];
    $exams = new WP_Query($args);

    if (!$exams->have_posts()) {
        return '<div class="mcq-question-box">No exams are currently available.</div>';
    }

    ob_start();
    echo '<div class="mcq-question-box"><h2>🧪 Available Exams</h2></div>';
    echo '<div class="mcq-exam-list">';

    while ($exams->have_posts()) {
        $exams->the_post();
        $exam_id = get_the_ID();
        echo '<div class="mcq-question-box">';
        echo '<h3>' . esc_html(get_the_title()) . '</h3>';
        echo '<p>' . esc_html(get_the_excerpt()) . '</p>';
        echo '<a class="mcq-option" href="' . esc_url(get_permalink() . '?exam_id=' . $exam_id . '&start=1&q=1') . '">🚀 Start Exam</a>';
        echo '</div>';
    }

    echo '</div>';
    wp_reset_postdata();

    return ob_get_clean();
});
