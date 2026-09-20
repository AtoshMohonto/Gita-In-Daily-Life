<?php
declare(strict_types=1);

return [
    'table' => 'situations',
    'model' => 'Situation',
    'title' => 'Situations',
    'order_by' => 'name ASC',
    'per_page' => 20,
    'list_columns' => [
        'name' => 'Name',
        'status' => 'Status',
        'updated_at' => 'Updated',
    ],
    'search_columns' => ['name', 'description'],
    'fields' => [
        'name' => ['label' => 'Name', 'type' => 'text', 'required' => true, 'help' => 'e.g. "I am afraid of failing"'],
        'slug' => ['label' => 'Slug', 'type' => 'text', 'source' => 'name', 'help' => 'Leave blank to auto-generate.'],
        'description' => ['label' => 'Description', 'type' => 'textarea'],
        'icon' => ['label' => 'Bootstrap Icon Name', 'type' => 'text', 'help' => 'e.g. "fire", "heart", "wind" — see icons.getbootstrap.com'],
        'status' => ['label' => 'Status', 'type' => 'select', 'options' => [
            'draft' => 'Draft', 'pending' => 'Pending Review', 'published' => 'Published', 'archived' => 'Archived',
        ], 'default' => 'published'],
    ],
];
