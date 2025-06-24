# Generate PHP file for frontend exam result display logic

exam_result_code = """
<?php
/**
 * Frontend Exam Result Display
 * Shows total score after final exam submission.
 * Author: Shahid Hussain Soomro
 */

if (!defined('ABSPATH')) exit;

if (!is_user_logged_in() || !isset($_GET['exam_id'])) {
    echo '<p>Please log in to view your exam results.</p>';
    return;
}

$user_id = get_current_user_id();
$exam_id = intval($_GET['exam_id']);
$score = intval($_GET['score']); // Passed via redirect from finalize_exam()

$exam_title = get_the_title($exam_id);
$total_mcqs = count(get_post_meta($exam_id, 'exam_mcqs', true) ?: []);

echo '<div class="exam-result-container">';
echo '<h2>Exam Completed: ' . esc_html($exam_title) . '</h2>';
echo '<p><strong>Your Score:</strong> ' . esc_html($score) . ' / ' . esc_html($total_mcqs) . '</p>';
echo '<p>Thank you for completing the exam!</p>';
echo '<a href="' . esc_url(home_url()) . '" class="button">Return to Homepage</a>';
echo '</div>';

?>
<style>
.exam-result-container {
    max-width: 600px;
    margin: 30px auto;
    background: #f0fdf4;
    padding: 25px;
    border: 1px solid #a3d9a5;
    border-radius: 10px;
    text-align: center;
}
.exam-result-container h2 {
    color: #2e7d32;
}
.exam-result-container a.button {
    margin-top: 20px;
    padding: 10px 18px;
    background: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}
.exam-result-container a.button:hover {
    background: #1e7b34;
}
</style>
"""

# Save the result page logic
with open("/mnt/data/exam-result.php", "w", encoding="utf-8") as f:
    f.write(exam_result_code)

"✅ Created 'exam-result.php' – displays user's score after exam submission."
