<?php
/**
 * Registers the custom post type 'mcq' for MCQs.
 * Part of the PakStudy Quiz Manager Plugin
 * @link https://github.com/shahidhussainsoomro/MCQS-Manager
 * @author Shahid
 */

class MCQ_Post_Type {

    public function __construct() {
        add_action('init', [$this, 'register_mcq_post_type']);
    }

    public function register_mcq_post_type() {
        register_post_type('mcq', [
            'labels' => [
                'name' => __('MCQs', 'pakstudy-quiz-manager'),
                'singular_name' => __('MCQ', 'pakstudy-quiz-manager'),
                'add_new' => __('Add New MCQ', 'pakstudy-quiz-manager'),
                'add_new_item' => __('Add New MCQ Item', 'pakstudy-quiz-manager'),
                'edit_item' => __('Edit MCQ', 'pakstudy-quiz-manager'),
                'new_item' => __('New MCQ', 'pakstudy-quiz-manager'),
                'view_item' => __('View MCQ', 'pakstudy-quiz-manager'),
                'search_items' => __('Search MCQs', 'pakstudy-quiz-manager'),
                'not_found' => __('No MCQs found', 'pakstudy-quiz-manager'),
                'not_found_in_trash' => __('No MCQs found in trash', 'pakstudy-quiz-manager')
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'mcq'],
            'supports' => ['title', 'editor', 'custom-fields'],
            'menu_icon' => 'dashicons-welcome-learn-more',
            'show_in_rest' => true
        ]);
    }
}

// Instantiate the class
new MCQ_Post_Type();
