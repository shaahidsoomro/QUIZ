<?php
/**
 * Frontend shortcode: [mcq_quiz]
 * Displays all MCQs with interactive answer checking (AJAX expected externally).
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 */

if (!defined('ABSPATH')) exit;

function mcq_quiz_shortcode($atts) {
    ob_start();

    $args = [
        'post_type' => 'mcq',
        'posts_per_page' => -1
    ];

    $mcq_query = new WP_Query($args);

    if ($mcq_query->have_posts()) {
        echo '<div class="mcq-quiz-container">';

        while ($mcq_query->have_posts()) {
            $mcq_query->the_post();
            $post_id = get_the_ID();

            $question = get_post_meta($post_id, 'question_text', true);
            $correct = get_post_meta($post_id, 'correct_option', true);
            $explanation = get_post_meta($post_id, 'explanation', true); // optional
            ?>

            <div class="mcq-question" data-id="<?php echo esc_attr($post_id); ?>" data-correct="<?php echo esc_attr($correct); ?>">
                <p><strong><?php echo esc_html($question); ?></strong></p>
                <form class="mcq-form">
                    <?php foreach (['a', 'b', 'c', 'd'] as $opt): ?>
                        <label>
                            <input type="radio" name="answer_<?php echo esc_attr($post_id); ?>" value="<?php echo esc_attr($opt); ?>" required>
                            <?php echo esc_html(get_post_meta($post_id, 'option_' . $opt, true)); ?>
                        </label><br>
                    <?php endforeach; ?>
                    <button type="submit"><?php _e('Check Answer', 'pakstudy-quiz-manager'); ?></button>
                </form>
                <div class="mcq-result" style="display:none; margin-top:10px;"></div>
            </div>
            <hr>

            <?php
        }

        echo '</div>';
    } else {
        echo '<p>' . __('No MCQs available at the moment.', 'pakstudy-quiz-manager') . '</p>';
    }

    wp_reset_postdata();
    return ob_get_clean();
}

add_shortcode('mcq_quiz', 'mcq_quiz_shortcode');
