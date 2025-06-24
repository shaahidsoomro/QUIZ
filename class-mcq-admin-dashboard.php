<?php
/**
 * Displays an Admin Dashboard for MCQS Maker plugin.
 *
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class MCQ_Admin_Dashboard {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_dashboard_page']);
    }

    public function add_admin_dashboard_page() {
        add_submenu_page(
            'edit.php?post_type=mcq',
            __('Admin Dashboard', 'pakstudy-quiz-manager'),
            __('Admin Dashboard', 'pakstudy-quiz-manager'),
            'manage_options',
            'mcq-admin-dashboard',
            [$this, 'render_admin_dashboard']
        );
    }

    public function render_admin_dashboard() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'pakstudy-quiz-manager'));
        }

        ?>
        <div class="wrap">
            <h1><?php _e('MCQS Maker - Admin Dashboard', 'pakstudy-quiz-manager'); ?></h1>
            <p><?php _e('Welcome to the Admin Dashboard. From here, you can manage quizzes, view user performance, and perform imports/exports.', 'pakstudy-quiz-manager'); ?></p>
            <ul>
                <li><a href="<?php echo esc_url(admin_url('edit.php?post_type=mcq')); ?>"><?php _e('Manage MCQs', 'pakstudy-quiz-manager'); ?></a></li>
                <li><a href="<?php echo esc_url(admin_url('admin.php?page=mcq-settings')); ?>"><?php _e('Plugin Settings', 'pakstudy-quiz-manager'); ?></a></li>
                <li><a href="#"><?php _e('User Reports (Coming Soon)', 'pakstudy-quiz-manager'); ?></a></li>
                <li><a href="#"><?php _e('Import/Export Tools (Coming Soon)', 'pakstudy-quiz-manager'); ?></a></li>
            </ul>
        </div>
        <?php
    }
}

new MCQ_Admin_Dashboard();
