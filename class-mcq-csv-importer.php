<?php
/**
 * Helper Class for Importing MCQs from CSV
 * This class is not a handler but a utility used by other importer classes.
 *
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 * Author: Shahid Hussain Soomro
 */

if (!defined('ABSPATH')) {
    exit;
}

class MCQ_CSV_Importer_Helper {

    public static function import_from_file($csv_path) {
        if (!file_exists($csv_path)) {
            return 0;
        }

        $handle = fopen($csv_path, 'r');
        if (!$handle) {
            return 0;
        }

        $row_count = 0;
        $header = fgetcsv($handle); // Skip header row

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) < 7) {
                continue;
            }

            $post_id = wp_insert_post([
                'post_type'   => 'mcq',
                'post_title'  => sanitize_text_field($data[0]),
                'post_status' => 'publish'
            ]);

            if (!is_wp_error($post_id)) {
                update_post_meta($post_id, 'question_text', sanitize_text_field($data[0]));
                update_post_meta($post_id, 'option_a', sanitize_text_field($data[1]));
                update_post_meta($post_id, 'option_b', sanitize_text_field($data[2]));
                update_post_meta($post_id, 'option_c', sanitize_text_field($data[3]));
                update_post_meta($post_id, 'option_d', sanitize_text_field($data[4]));
                update_post_meta($post_id, 'correct_option', sanitize_text_field($data[5]));
                update_post_meta($post_id, 'difficulty', sanitize_text_field($data[6]));
                $row_count++;
            }
        }

        fclose($handle);
        return $row_count;
    }
}
