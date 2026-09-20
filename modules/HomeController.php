<?php
declare(strict_types=1);

final class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home', [
            'pageTitle' => APP_NAME . ' — ' . __t('home.hero_subtitle'),
            'metaDescription' => Setting::get('default_meta_description'),
            'featuredGitas' => Gita::featured(6),
            'situations' => array_slice(Situation::published(), 0, 8),
            'dailyWisdom' => DailyWisdom::today(),
            'topics' => array_slice(Topic::published(), 0, 10),
        ]);
    }
}
