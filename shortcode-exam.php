# Start building the frontend exam logic: shortcode-exam.php
shortcode_exam_code = """
<?php
/**
 * Shortcode to display the Exam Interface to logged-in users
 * Includes: disclaimer, timer, one-MCQ-per-page navigation
 */

if (!defined('ABSPATH')) exit;

function render_mcq_exam() {
    if (!is_user_logged_in()) {
        return '<p>Please <a href="' . wp_login_url(get_permalink()) . '">log in</a> to attempt the exam.</p>';
    }

    global $wpdb;
    $exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 0;

    if (!$exam_id) {
        return '<p>No exam selected.</p>';
    }

    $exam = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}mcq_exams WHERE id = %d", $exam_id));
    if (!$exam) return '<p>Invalid Exam ID.</p>';

    $started = isset($_GET['start']) && $_GET['start'] === '1';
    $current_page = isset($_GET['q']) ? intval($_GET['q']) : 1;

    // Get all MCQs in selected categories
    $categories = maybe_unserialize($exam->categories);
    $args = [
        'post_type' => 'mcq',
        'posts_per_page' => 1,
        'paged' => $current_page,
        'tax_query' => [
            [
                'taxonomy' => 'mcq_category',
                'field' => 'term_id',
                'terms' => $categories
            ]
        ]
    ];
    $query = new WP_Query($args);

    ob_start();

    if (!$started) {
        ?>
        <div class="exam-disclaimer">
            <h2><?php echo esc_html($exam->title); ?></h2>
            <p><?php echo esc_html($exam->disclaimer); ?></p>
            <a href="<?php echo esc_url(add_query_arg('start', '1')); ?>" class="button button-primary">Start Exam</a>
        </div>
        <?php
    } else {
        echo '<div id="exam-timer" data-minutes="' . esc_attr($exam->duration) . '">Time Remaining: <span id="timer-countdown"></span></div>';
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $correct = get_post_meta(get_the_ID(), 'correct_option', true);
                ?>
                <form method="post" class="mcq-question-form">
                    <h3><?php the_title(); ?></h3>
                    <p><?php echo get_post_meta(get_the_ID(), 'question_text', true); ?></p>
                    <?php foreach (['a', 'b', 'c', 'd'] as $opt): ?>
                        <label><input type="radio" name="answer" value="<?php echo $opt; ?>"> <?php echo esc_html(get_post_meta(get_the_ID(), 'option_' . $opt, true)); ?></label><br>
                    <?php endforeach; ?>
                    <input type="submit" value="Next" class="button button-primary">
                </form>
                <?php
                echo '<input type="hidden" id="correct-answer" value="' . esc_attr($correct) . '">';
            }

            // Pagination
            $big = 999999999;
            echo paginate_links([
                'base' => add_query_arg('q', '%#%'),
                'format' => '',
                'current' => max(1, $current_page),
                'total' => $query->max_num_pages
            ]);
        } else {
            echo '<p>No questions found for this exam.</p>';
        }
        wp_reset_postdata();
    }

    return ob_get_clean();
}
add_shortcode('mcq_exam', 'render_mcq_exam');
"""

# Write to shortcode-exam.php
with open("/mnt/data/shortcode-exam.php", "w", encoding="utf-8") as f:
    f.write(shortcode_exam_code)

"✅ shortcode-exam.php has been created – users can view a timed exam with disclaimer and 1 MCQ per page."
