<?php
declare(strict_types=1);

return [
    'table' => 'mantras',
    'model' => 'Mantra',
    'title' => 'Mantras',
    'order_by' => 'title ASC',
    'per_page' => 20,
    'list_columns' => [
        'title' => 'Title',
        'status' => 'Status',
        'updated_at' => 'Updated',
    ],
    'search_columns' => ['title', 'sanskrit', 'meaning'],
    'fields' => [
        'title' => ['label' => 'Title', 'type' => 'text', 'required' => true],
        'slug' => ['label' => 'Slug', 'type' => 'text', 'source' => 'title', 'help' => 'Leave blank to auto-generate.'],
        'sanskrit' => ['label' => 'Sanskrit', 'type' => 'textarea', 'required' => true],
        'transliteration' => ['label' => 'Transliteration', 'type' => 'textarea'],
        'pronunciation_bn' => ['label' => 'Bengali Pronunciation Guide', 'type' => 'textarea'],
        'pronunciation_en' => ['label' => 'English Pronunciation Guide', 'type' => 'textarea'],
        'meaning' => ['label' => 'Meaning / Translation (English)', 'type' => 'richtext', 'required' => true],
        'meaning_bn' => ['label' => 'অর্থ / অনুবাদ (Bengali Meaning)', 'type' => 'richtext'],
        'deity_association' => ['label' => 'Deity / Traditional Association', 'type' => 'text'],
        'purpose' => ['label' => 'Traditional Purpose', 'type' => 'textarea', 'help' => 'Use responsible language, e.g. "Traditionally recited for..." — never a medical claim.'],
        'traditionally_recited' => ['label' => 'Traditionally Recited', 'type' => 'text'],
        'suggested_duration' => ['label' => 'Suggested Practice Duration', 'type' => 'text'],
        'source' => ['label' => 'Source', 'type' => 'text'],
        'situations' => ['label' => 'Related Life Situations', 'type' => 'multiselect',
            'pivot_table' => 'mantra_situations', 'pivot_local_key' => 'mantra_id', 'pivot_foreign_key' => 'situation_id',
            'options_table' => 'situations', 'options_label' => 'name'],
        'teachings' => ['label' => 'Related Teachings', 'type' => 'multiselect',
            'pivot_table' => 'mantra_teachings', 'pivot_local_key' => 'mantra_id', 'pivot_foreign_key' => 'teaching_id',
            'options_table' => 'teachings', 'options_label' => 'title'],
        'status' => ['label' => 'Status', 'type' => 'select', 'options' => [
            'draft' => 'Draft', 'pending' => 'Pending Review', 'published' => 'Published', 'archived' => 'Archived',
        ], 'default' => 'draft'],
    ],
];
