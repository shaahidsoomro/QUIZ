<?php
/**
 * Handles Import/Export of MCQs in CSV format.
 * Requires: class-mcq-csv-importer.php (helper class)
 *
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include the helper class for CSV import
require_once plugin_dir_path(__FILE__) . 'class-mcq-csv-importer.php';

class MCQ_Import_Export {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_post_import_mcqs', [$this, 'handle_import']);
        add_action('admin_post_export_mcqs', [$this, 'handle_export']);
    }

    public function add_menu() {
        add_submenu_page(
            'edit.php?post_type=mcq',
            __('Import/Export MCQs', 'pakstudy-quiz-manager'),
            __('Import/Export MCQs', 'pakstudy-quiz-manager'),
            'manage_options',
            'import-export-mcqs',
            [$this, 'render_page']
        );
    }

    public function render_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Import MCQs (CSV Format)', 'pakstudy-quiz-manager'); ?></h1>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
                <input type="hidden" name="action" value="import_mcqs">
                <?php wp_nonce_field('import_mcqs_nonce'); ?>
                <input type="file" name="mcq_csv" accept=".csv" required>
                <p><em>CSV format: Question, Option A, Option B, Option C, Option D, Correct Option, Difficulty</em></p>
                <input type="submit" value="<?php _e('Import', 'pakstudy-quiz-manager'); ?>" class="button button-primary">
            </form>

            <hr>

            <h2><?php _e('Export All MCQs', 'pakstudy-quiz-manager'); ?></h2>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="export_mcqs">
                <?php wp_nonce_field('export_mcqs_nonce'); ?>
                <input type="submit" value="<?php _e('Export as CSV', 'pakstudy-quiz-manager'); ?>" class="button">
            </form>
        </div>
        <?php
    }

    public function handle_import() {
        if (!current_user_can('manage_options') || !check_admin_referer('import_mcqs_nonce')) {
            wp_die(__('Unauthorized or invalid request.', 'pakstudy-quiz-manager'));
        }

        if (!isset($_FILES['mcq_csv']) || $_FILES['mcq_csv']['error'] !== UPLOAD_ERR_OK) {
            wp_die(__('CSV upload failed.', 'pakstudy-quiz-manager'));
        }

        // Call helper class
        $count = MCQ_CSV_Importer_Helper::import_from_file($_FILES['mcq_csv']['tmp_name']);

        wp_redirect(admin_url('edit.php?post_type=mcq&imported=' . $count));
        exit;
    }

    public function handle_export() {
        if (!current_user_can('manage_options') || !check_admin_referer('export_mcqs_nonce')) {
            wp_die(__('Unauthorized or invalid request.', 'pakstudy-quiz-manager'));
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=mcqs-export.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Question', 'Option A', 'Option B', 'Option C', 'Option D', 'Correct Option', 'Difficulty']);

        $mcqs = get_posts(['post_type' => 'mcq', 'posts_per_page' => -1]);

        foreach ($mcqs as $mcq) {
            fputcsv($output, [
                get_post_meta($mcq->ID, 'question_text', true),
                get_post_meta($mcq->ID, 'option_a', true),
                get_post_meta($mcq->ID, 'option_b', true),
                get_post_meta($mcq->ID, 'option_c', true),
                get_post_meta($mcq->ID, 'option_d', true),
                get_post_meta($mcq->ID, 'correct_option', true),
                get_post_meta($mcq->ID, 'difficulty', true),
            ]);
        }

        fclose($output);
        exit;
    }
}

// Instantiate the class
new MCQ_Import_Export();
