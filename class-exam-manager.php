# Create class-exam-manager.php extension: Add exam listing, edit, and delete functionality
manage_exam_list_code = """
<?php
/**
 * Admin Exam Manager – list view, edit & delete exams.
 * Author: Shahid Hussain Soomro
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 */

if (!defined('ABSPATH')) exit;

class MCQ_Exam_Manager {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_exam_menu']);
        add_action('admin_post_save_mcq_exam', [$this, 'save_exam']);
        add_action('admin_post_delete_mcq_exam', [$this, 'delete_exam']);
    }

    public function add_exam_menu() {
        add_menu_page('Exam Manager', 'Exam Manager', 'manage_options', 'mcq-exam-manager', [$this, 'exam_list_page'], 'dashicons-clock', 28);
    }

    public function exam_list_page() {
        global $wpdb;
        $exams = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}mcq_exams ORDER BY created_at DESC");

        echo '<div class="wrap"><h1>Manage Exams</h1>';
        echo '<a href="' . admin_url('admin.php?page=mcq-exam-manager&action=new') . '" class="button button-primary">+ Add New Exam</a><br><br>';

        if ($_GET['action'] === 'new' || $_GET['action'] === 'edit') {
            $edit = false;
            $exam = ['id' => 0, 'title' => '', 'duration' => '', 'disclaimer' => '', 'categories' => []];

            if ($_GET['action'] === 'edit' && isset($_GET['id'])) {
                $exam = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}mcq_exams WHERE id = %d", intval($_GET['id'])), ARRAY_A);
                $exam['categories'] = maybe_unserialize($exam['categories']);
                $edit = true;
            }

            echo '<h2>' . ($edit ? 'Edit Exam' : 'Add New Exam') . '</h2>';
            ?>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="save_mcq_exam">
                <input type="hidden" name="exam_id" value="<?php echo esc_attr($exam['id']); ?>">
                <table class="form-table">
                    <tr><th><label for="exam_title">Title</label></th>
                        <td><input type="text" name="exam_title" value="<?php echo esc_attr($exam['title']); ?>" required class="regular-text"></td></tr>
                    <tr><th><label for="exam_duration">Duration (minutes)</label></th>
                        <td><input type="number" name="exam_duration" value="<?php echo esc_attr($exam['duration']); ?>" required></td></tr>
                    <tr><th><label for="exam_disclaimer">Disclaimer</label></th>
                        <td><textarea name="exam_disclaimer" rows="4" cols="60"><?php echo esc_textarea($exam['disclaimer']); ?></textarea></td></tr>
                    <tr><th><label for="exam_categories">Categories</label></th>
                        <td>
                            <?php
                            $terms = get_terms(['taxonomy' => 'mcq_category', 'hide_empty' => false]);
                            foreach ($terms as $term) {
                                $checked = in_array($term->term_id, $exam['categories']) ? 'checked' : '';
                                echo '<label><input type="checkbox" name="exam_categories[]" value="' . esc_attr($term->term_id) . '" ' . $checked . '> ' . esc_html($term->name) . '</label><br>';
                            }
                            ?>
                        </td></tr>
                </table>
                <p><input type="submit" value="<?php echo $edit ? 'Update' : 'Create'; ?> Exam" class="button button-primary"></p>
            </form>
            <?php
        } else {
            echo '<table class="widefat"><thead><tr>
                    <th>ID</th><th>Title</th><th>Duration</th><th>Created</th><th>Actions</th>
                  </tr></thead><tbody>';
            foreach ($exams as $exam) {
                $edit_url = admin_url('admin.php?page=mcq-exam-manager&action=edit&id=' . $exam->id);
                $del_url = wp_nonce_url(admin_url('admin-post.php?action=delete_mcq_exam&id=' . $exam->id), 'delete_mcq_exam_' . $exam->id);
                echo "<tr>
                        <td>{$exam->id}</td>
                        <td>{$exam->title}</td>
                        <td>{$exam->duration} min</td>
                        <td>{$exam->created_at}</td>
                        <td><a href='{$edit_url}' class='button'>Edit</a> <a href='{$del_url}' class='button-delete'>Delete</a></td>
                      </tr>";
            }
            echo '</tbody></table>';
        }

        echo '</div>';
    }

    public function save_exam() {
        if (!current_user_can('manage_options')) wp_die('Unauthorized');

        global $wpdb;

        $id = intval($_POST['exam_id']);
        $data = [
            'title' => sanitize_text_field($_POST['exam_title']),
            'duration' => intval($_POST['exam_duration']),
            'disclaimer' => sanitize_textarea_field($_POST['exam_disclaimer']),
            'categories' => maybe_serialize($_POST['exam_categories']),
        ];

        if ($id > 0) {
            $wpdb->update($wpdb->prefix . 'mcq_exams', $data, ['id' => $id]);
        } else {
            $wpdb->insert($wpdb->prefix . 'mcq_exams', $data);
        }

        wp_redirect(admin_url('admin.php?page=mcq-exam-manager'));
        exit;
    }

    public function delete_exam() {
        if (!current_user_can('manage_options') || !isset($_GET['id'])) wp_die('Unauthorized');

        $id = intval($_GET['id']);
        check_admin_referer('delete_mcq_exam_' . $id);
        global $wpdb;
        $wpdb->delete($wpdb->prefix . 'mcq_exams', ['id' => $id]);

        wp_redirect(admin_url('admin.php?page=mcq-exam-manager'));
        exit;
    }
}

new MCQ_Exam_Manager();
"""

# Save the updated exam manager file
with open("/mnt/data/class-exam-manager.php", "w", encoding="utf-8") as f:
    f.write(manage_exam_list_code)

"✅ class-exam-manager.php now includes exam list view, edit, and delete functionality."
