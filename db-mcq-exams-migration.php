<?php
/**
 * Migration script to create mcq_exams table
 */

function mcq_create_exam_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mcq_exams';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        duration INT NOT NULL,
        disclaimer TEXT,
        categories TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'mcq_create_exam_table');
?>