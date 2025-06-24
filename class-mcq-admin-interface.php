<?php
/**
 * Customizes admin columns and filters for MCQ post type.
 *
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class MCQ_Admin_Interface {

    public function __construct() {
        add_filter('manage_mcq_posts_columns', [$this, 'add_columns']);
        add_action('manage_mcq_posts_custom_column', [$this, 'render_columns'], 10, 2);
        add_filter('manage_edit-mcq_sortable_columns', [$this, 'make_columns_sortable']);
        add_action('restrict_manage_posts', [$this, 'filter_dropdown']);
        add_filter('parse_query', [$this, 'filter_query']);
    }

    // Add custom admin columns
    public function add_columns($columns) {
        $columns['correct_option'] = esc_html__('Correct Option', 'pakstudy-quiz-manager');
        $columns['difficulty'] = esc_html__('Difficulty', 'pakstudy-quiz-manager');
        return $columns;
    }

    // Display data in custom columns
    public function render_columns($column, $post_id) {
        if ($column === 'correct_option') {
            echo esc_html(get_post_meta($post_id, 'correct_option', true));
        } elseif ($column === 'difficulty') {
            echo esc_html(get_post_meta($post_id, 'difficulty', true));
        }
    }

    // Make difficulty column sortable
    public function make_columns_sortable($columns) {
        $columns['difficulty'] = 'difficulty';
        return $columns;
    }

    // Dropdown filter by difficulty
    public function filter_dropdown() {
        global $typenow;
        if ($typenow === 'mcq') {
            $current = isset($_GET['difficulty_filter']) ? sanitize_text_field($_GET['difficulty_filter']) : '';
            ?>
            <select name="difficulty_filter">
                <option value=""><?php esc_html_e('All Difficulties', 'pakstudy-quiz-manager'); ?></option>
                <?php foreach (['Easy', 'Medium', 'Hard'] as $level): ?>
                    <option value="<?php echo esc_attr($level); ?>" <?php selected($current, $level); ?>>
                        <?php echo esc_html($level); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php
        }
    }

    // Apply query filtering by difficulty
    public function filter_query($query) {
        global $pagenow, $typenow;
        if ($pagenow === 'edit.php' && $typenow === 'mcq' && isset($_GET['difficulty_filter']) && $_GET['difficulty_filter'] !== '') {
            $query->query_vars['meta_key'] = 'difficulty';
            $query->query_vars['meta_value'] = sanitize_text_field($_GET['difficulty_filter']);
        }
    }
}

// Instantiate
new MCQ_Admin_Interface();
