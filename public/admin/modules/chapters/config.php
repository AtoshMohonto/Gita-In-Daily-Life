<?php
declare(strict_types=1);

return [
    'table' => 'chapters',
    'model' => 'Chapter',
    'title' => 'Chapters',
    'order_by' => 'gita_id ASC, chapter_number ASC',
    'per_page' => 20,
    'list_columns' => [
        'chapter_number' => '#',
        'name' => 'Name',
        'status' => 'Status',
        'updated_at' => 'Updated',
    ],
    'search_columns' => ['name', 'sanskrit_name', 'summary'],
    'fields' => [
        'gita_id' => ['label' => 'Gita', 'type' => 'relation', 'required' => true,
            'relation_table' => 'gitas', 'relation_label' => 'name', 'relation_order_by' => 'name'],
        'chapter_number' => ['label' => 'Chapter Number', 'type' => 'number', 'required' => true],
        'name' => ['label' => 'Chapter Name', 'type' => 'text', 'required' => true],
        'sanskrit_name' => ['label' => 'Sanskrit Name', 'type' => 'text'],
        'translation_name' => ['label' => 'Translated Name', 'type' => 'text'],
        'summary' => ['label' => 'Summary', 'type' => 'richtext'],
        'main_ideas' => ['label' => 'Main Ideas', 'type' => 'textarea'],
        'status' => ['label' => 'Status', 'type' => 'select', 'options' => [
            'draft' => 'Draft', 'pending' => 'Pending Review', 'published' => 'Published', 'archived' => 'Archived',
        ], 'default' => 'draft'],
    ],
];
