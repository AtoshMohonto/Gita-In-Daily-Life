<?php
declare(strict_types=1);

return [
    'table' => 'gitas',
    'model' => 'Gita',
    'title' => 'Gitas',
    'order_by' => 'created_at DESC',
    'per_page' => 15,
    'list_columns' => [
        'name' => 'Name',
        'status' => 'Status',
        'featured' => 'Featured',
        'updated_at' => 'Updated',
    ],
    'search_columns' => ['name', 'short_description'],
    'fields' => [
        'name' => ['label' => 'Name', 'type' => 'text', 'required' => true],
        'slug' => ['label' => 'Slug', 'type' => 'text', 'source' => 'name', 'help' => 'Leave blank to auto-generate from the name.'],
        'alternate_names' => ['label' => 'Alternate Names', 'type' => 'text'],
        'short_description' => ['label' => 'Short Description', 'type' => 'textarea'],
        'introduction' => ['label' => 'Introduction', 'type' => 'richtext'],
        'historical_context' => ['label' => 'Historical / Traditional Context', 'type' => 'richtext'],
        'philosophy' => ['label' => 'Philosophy', 'type' => 'richtext'],
        'main_themes' => ['label' => 'Main Themes', 'type' => 'textarea'],
        'author_attribution' => ['label' => 'Author / Attribution', 'type' => 'text'],
        'cover_image' => ['label' => 'Cover Image URL', 'type' => 'text'],
        'language_available' => ['label' => 'Languages Available', 'type' => 'text', 'default' => 'en'],
        'featured' => ['label' => 'Featured on Homepage', 'type' => 'checkbox'],
        'status' => ['label' => 'Status', 'type' => 'select', 'options' => [
            'draft' => 'Draft', 'pending' => 'Pending Review', 'published' => 'Published', 'archived' => 'Archived',
        ], 'default' => 'draft'],
        'meta_title' => ['label' => 'SEO Meta Title', 'type' => 'text'],
        'meta_description' => ['label' => 'SEO Meta Description', 'type' => 'textarea'],
    ],
];
