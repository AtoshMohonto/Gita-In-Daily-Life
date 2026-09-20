<?php
declare(strict_types=1);

$verseLabel = "(SELECT CONCAT(gt.name,' ',ct.chapter_number,'.',verses.verse_number) FROM gitas gt JOIN chapters ct ON ct.id = verses.chapter_id WHERE gt.id = verses.gita_id)";

return [
    'table' => 'daily_wisdom',
    'model' => 'DailyWisdom',
    'title' => 'Daily Wisdom',
    'order_by' => 'wisdom_date DESC',
    'per_page' => 20,
    'list_columns' => [
        'wisdom_date' => 'Date',
        'title' => 'Title',
        'status' => 'Status',
    ],
    'search_columns' => ['title', 'short_message'],
    'fields' => [
        'wisdom_date' => ['label' => 'Date', 'type' => 'date', 'required' => true],
        'verse_id' => ['label' => 'Verse', 'type' => 'relation', 'required' => true,
            'relation_table' => 'verses', 'relation_label' => $verseLabel, 'relation_order_by' => 'gita_id, chapter_id, verse_number'],
        'title' => ['label' => 'Title', 'type' => 'text', 'required' => true],
        'short_message' => ['label' => 'Short Message', 'type' => 'textarea'],
        'practical_action' => ['label' => 'Practical Action', 'type' => 'textarea'],
        'reflection_question' => ['label' => 'Reflection Question', 'type' => 'text'],
        'mantra_id' => ['label' => 'Related Mantra (optional)', 'type' => 'relation',
            'relation_table' => 'mantras', 'relation_label' => 'title', 'relation_order_by' => 'title'],
        'featured' => ['label' => 'Featured', 'type' => 'checkbox'],
        'status' => ['label' => 'Status', 'type' => 'select', 'options' => [
            'draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived',
        ], 'default' => 'published'],
    ],
];
