<?php
/**
 * Adds and saves custom meta fields for MCQ post type
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 */

class MCQ_Meta_Fields {
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
    }

    public function add_meta_boxes() {
        add_meta_box(
            'mcq_meta_box',
            __('MCQ Details', 'pakstudy-quiz-manager'),
            [$this, 'render_meta_box'],
            'mcq',
            'normal',
            'high'
        );
    }

    public function render_meta_box($post) {
        wp_nonce_field('save_mcq_meta', 'mcq_meta_nonce');

        $fields = ['question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option', 'difficulty'];
        foreach ($fields as $field) {
            $$field = get_post_meta($post->ID, $field, true);
        }

        ?>
        <p><strong><?php _e('Question Text', 'pakstudy-quiz-manager'); ?>:</strong></p>
        <textarea name="question_text" style="width:100%" required><?php echo esc_textarea($question_text); ?></textarea>

        <?php foreach (['a', 'b', 'c', 'd'] as $opt): ?>
            <p><strong><?php echo __('Option', 'pakstudy-quiz-manager') . ' ' . strtoupper($opt); ?>:</strong><br>
            <input type="text" name="option_<?php echo $opt; ?>" value="<?php echo esc_attr(${'option_'.$opt}); ?>" style="width:100%" required></p>
        <?php endforeach; ?>

        <p><strong><?php _e('Correct Option (a, b, c, or d)', 'pakstudy-quiz-manager'); ?>:</strong></p>
        <input type="text" name="correct_option" value="<?php echo esc_attr($correct_option); ?>" style="width:100%" maxlength="1" required>

        <p><strong><?php _e('Difficulty Level (easy, medium, hard)', 'pakstudy-quiz-manager'); ?>:</strong></p>
        <input type="text" name="difficulty" value="<?php echo esc_attr($difficulty); ?>" style="width:100%">
        <?php
    }

    public function save_meta_boxes($post_id) {
        if (!isset($_POST['mcq_meta_nonce']) || !wp_verify_nonce($_POST['mcq_meta_nonce'], 'save_mcq_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;

        $fields = ['question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option', 'difficulty'];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
}

// Instantiate the class
new MCQ_Meta_Fields();
