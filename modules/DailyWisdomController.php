<?php
declare(strict_types=1);

final class DailyWisdomController extends Controller
{
    public function index(): void
    {
        $wisdom = DailyWisdom::today();

        $this->view('daily-wisdom/index', [
            'pageTitle' => __t('home.daily_wisdom_title') . ' — ' . APP_NAME,
            'wisdom' => $wisdom,
        ]);
    }
}
