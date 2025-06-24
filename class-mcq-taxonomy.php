<?php
/**
 * Registers a custom taxonomy for MCQ post type
 * GitHub: https://github.com/shahidhussainsoomro/MCQS-Manager
 * Author: Shahid Hussain Soomro
 */

class MCQ_Taxonomy {

    public function __construct() {
        add_action('init', [$this, 'register_taxonomy']);
    }

    public function register_taxonomy() {
        register_taxonomy('mcq_category', 'mcq', [
            'labels' => [
                'name'              => __('MCQ Categories', 'pakstudy-quiz-manager'),
                'singular_name'     => __('MCQ Category', 'pakstudy-quiz-manager'),
                'search_items'      => __('Search MCQ Categories', 'pakstudy-quiz-manager'),
                'all_items'         => __('All Categories', 'pakstudy-quiz-manager'),
                'parent_item'       => __('Parent Category', 'pakstudy-quiz-manager'),
                'parent_item_colon' => __('Parent Category:', 'pakstudy-quiz-manager'),
                'edit_item'         => __('Edit Category', 'pakstudy-quiz-manager'),
                'update_item'       => __('Update Category', 'pakstudy-quiz-manager'),
                'add_new_item'      => __('Add New Category', 'pakstudy-quiz-manager'),
                'new_item_name'     => __('New Category Name', 'pakstudy-quiz-manager'),
                'menu_name'         => __('MCQ Categories', 'pakstudy-quiz-manager'),
            ],
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'rewrite'           => ['slug' => 'mcq-category'],
            'show_in_rest'      => true
        ]);
    }
}

// Instantiate the class
new MCQ_Taxonomy();
