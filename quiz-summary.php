# Create quiz-summary.php – Displays score after the exam is finished
quiz_summary_code = """
<?php
/**
 * Exam Summary Page – displays user's score after finishing an exam.
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 */

if (!defined('ABSPATH')) exit;

function render_exam_summary() {
    if (!is_user_logged_in()) {
        return '<p>Please log in to view your exam result.</p>';
    }

    global $wpdb;
    $user_id = get_current_user_id();
    $exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 0;

    if (!$exam_id) return '<p>Invalid Exam ID.</p>';

    $attempt = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}mcq_exam_attempts WHERE user_id = %d AND exam_id = %d ORDER BY id DESC LIMIT 1",
        $user_id, $exam_id
    ));

    if (!$attempt) return '<p>No exam attempt found.</p>';

    $total_questions = count(maybe_unserialize($attempt->answers));
    $score = intval($attempt->score);
    $percent = round(($score / max($total_questions, 1)) * 100);

    ob_start();
    ?>
    <div class="exam-summary">
        <h2>📝 Exam Summary</h2>
        <p><strong>Total Questions:</strong> <?php echo $total_questions; ?></p>
        <p><strong>Correct Answers:</strong> <?php echo $score; ?></p>
        <p><strong>Score Percentage:</strong> <?php echo $percent; ?>%</p>
        <p><strong>Date:</strong> <?php echo date('d M Y, h:i A', strtotime($attempt->completed_at)); ?></p>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('mcq_exam_summary', 'render_exam_summary');
"""

# Save to quiz-summary.php
with open("/mnt/data/quiz-summary.php", "w", encoding="utf-8") as f:
    f.write(quiz_summary_code)

"✅ quiz-summary.php has been created – users will now see a final score summary after completing the exam."
