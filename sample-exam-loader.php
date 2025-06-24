# PHP code to insert a sample exam with 3 MCQs for testing purposes

sample_data_code = """
<?php
// Hook to run only once on admin_init to insert sample exam + MCQs
add_action('admin_init', 'mcq_insert_sample_exam');

function mcq_insert_sample_exam() {
    if (get_option('mcq_sample_exam_inserted')) return;

    // Create Exam (Custom Post Type)
    $exam_id = wp_insert_post([
        'post_title' => 'Sample General Knowledge Exam',
        'post_type' => 'mcq_exam',
        'post_status' => 'publish',
        'post_content' => 'This is a demo test for MCQS Maker.'
    ]);

    // Save metadata
    update_post_meta($exam_id, 'exam_duration', '15');
    update_post_meta($exam_id, 'exam_disclaimer', 'This is a demo test for MCQS Maker.');

    // Add 3 sample MCQs
    $sample_mcqs = [
        [
            'question' => 'What is the capital of Pakistan?',
            'options' => ['Lahore', 'Karachi', 'Islamabad', 'Peshawar'],
            'correct' => 'Islamabad'
        ],
        [
            'question' => 'Which is the largest continent?',
            'options' => ['Africa', 'Europe', 'Asia', 'Australia'],
            'correct' => 'Asia'
        ],
        [
            'question' => 'In which year did Pakistan gain independence?',
            'options' => ['1945', '1947', '1950', '1930'],
            'correct' => '1947'
        ]
    ];

    foreach ($sample_mcqs as $mcq) {
        $mcq_id = wp_insert_post([
            'post_title' => wp_strip_all_tags($mcq['question']),
            'post_type' => 'mcq',
            'post_status' => 'publish'
        ]);
        update_post_meta($mcq_id, 'option_a', $mcq['options'][0]);
        update_post_meta($mcq_id, 'option_b', $mcq['options'][1]);
        update_post_meta($mcq_id, 'option_c', $mcq['options'][2]);
        update_post_meta($mcq_id, 'option_d', $mcq['options'][3]);
        update_post_meta($mcq_id, 'correct_option', $mcq['correct']);
        update_post_meta($mcq_id, 'difficulty', 'Easy');
        wp_set_object_terms($mcq_id, 'General Knowledge', 'mcq_category');
    }

    // Prevent re-running
    update_option('mcq_sample_exam_inserted', true);
}
?>
"""

# Save the sample data injection logic to a new file
with open("/mnt/data/sample-exam-loader.php", "w", encoding="utf-8") as f:
    f.write(sample_data_code)

"✅ `sample-exam-loader.php` created – inserts a sample exam and 3 MCQs for testing."
