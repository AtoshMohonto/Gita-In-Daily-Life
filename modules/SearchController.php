<?php
declare(strict_types=1);

final class SearchController extends Controller
{
    public function index(): void
    {
        $q = trim((string) Request::query('q', ''));

        $results = [
            'gitas' => [],
            'verses' => [],
            'topics' => [],
            'situations' => [],
            'mantras' => [],
            'teachings' => [],
        ];

        if ($q !== '') {
            $results['gitas'] = Gita::search(['name', 'short_description'], $q, ['status' => 'published'], 'name ASC', 10);
            $results['verses'] = Verse::searchFull($q, 15);
            $results['topics'] = Topic::search(['name', 'description'], $q, ['status' => 'published'], 'name ASC', 10);
            $results['situations'] = Situation::search(['name', 'description'], $q, ['status' => 'published'], 'name ASC', 10);
            $results['mantras'] = Mantra::search(['title', 'meaning', 'sanskrit'], $q, ['status' => 'published'], 'title ASC', 10);
            $results['teachings'] = Teaching::search(['title', 'life_problem', 'relevant_teaching'], $q, ['status' => 'published'], 'title ASC', 10);
        }

        $totalResults = array_sum(array_map('count', $results));

        $this->view('search/results', [
            'pageTitle' => __t('search.results_for') . ' "' . $q . '" — ' . APP_NAME,
            'q' => $q,
            'results' => $results,
            'totalResults' => $totalResults,
        ]);
    }
}
