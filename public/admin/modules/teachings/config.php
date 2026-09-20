<?php
declare(strict_types=1);

return [
    'table' => 'teachings',
    'model' => 'Teaching',
    'title' => 'Teachings',
    'order_by' => 'created_at DESC',
    'per_page' => 20,
    'list_columns' => [
        'title' => 'Title',
        'status' => 'Status',
        'updated_at' => 'Updated',
    ],
    'search_columns' => ['title', 'life_problem', 'relevant_teaching'],
    'fields' => [
        'title' => ['label' => 'Title', 'type' => 'text', 'required' => true],
        'slug' => ['label' => 'Slug', 'type' => 'text', 'source' => 'title', 'help' => 'Leave blank to auto-generate.'],
        'life_problem' => ['label' => 'Life Problem', 'type' => 'textarea', 'required' => true, 'help' => 'e.g. "I am afraid of failing an exam."'],
        'relevant_teaching' => ['label' => 'Relevant Teaching', 'type' => 'richtext'],
        'what_it_says' => ['label' => 'What the Teaching Says', 'type' => 'richtext'],
        'what_it_does_not_mean' => ['label' => 'What It Does NOT Mean', 'type' => 'richtext', 'help' => 'Important: avoid oversimplification.'],
        'how_to_apply' => ['label' => 'How to Apply It', 'type' => 'richtext'],
        'reflection_question' => ['label' => 'Reflection Question', 'type' => 'text'],
        'practice' => ['label' => 'Practice (small daily action)', 'type' => 'textarea'],
        'topics' => ['label' => 'Topics', 'type' => 'multiselect',
            'pivot_table' => 'teaching_topics', 'pivot_local_key' => 'teaching_id', 'pivot_foreign_key' => 'topic_id',
            'options_table' => 'topics', 'options_label' => 'name'],
        'situations' => ['label' => 'Related Life Situations', 'type' => 'multiselect',
            'pivot_table' => 'teaching_situations', 'pivot_local_key' => 'teaching_id', 'pivot_foreign_key' => 'situation_id',
            'options_table' => 'situations', 'options_label' => 'name'],
        'status' => ['label' => 'Status', 'type' => 'select', 'options' => [
            'draft' => 'Draft', 'pending' => 'Pending Review', 'published' => 'Published', 'archived' => 'Archived',
        ], 'default' => 'draft'],
    ],
];
