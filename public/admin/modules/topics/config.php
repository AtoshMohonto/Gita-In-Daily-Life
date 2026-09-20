<?php
declare(strict_types=1);

return [
    'table' => 'topics',
    'model' => 'Topic',
    'title' => 'Topics',
    'order_by' => 'name ASC',
    'per_page' => 20,
    'list_columns' => [
        'name' => 'Name',
        'status' => 'Status',
        'updated_at' => 'Updated',
    ],
    'search_columns' => ['name', 'description'],
    'fields' => [
        'name' => ['label' => 'Name', 'type' => 'text', 'required' => true],
        'slug' => ['label' => 'Slug', 'type' => 'text', 'source' => 'name', 'help' => 'Leave blank to auto-generate.'],
        'description' => ['label' => 'Description', 'type' => 'textarea'],
        'status' => ['label' => 'Status', 'type' => 'select', 'options' => [
            'draft' => 'Draft', 'pending' => 'Pending Review', 'published' => 'Published', 'archived' => 'Archived',
        ], 'default' => 'published'],
    ],
];
