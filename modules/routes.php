<?php
declare(strict_types=1);

/** @var Router $router */

$router->get('/', 'HomeController@index');

$router->get('/gitas', 'GitaController@index');
$router->get('/gitas/{slug}', 'GitaController@show');
$router->get('/gitas/{slug}/chapter/{number}', 'GitaController@chapter');

$router->get('/verses/{gita}/{chapter}/{verse}', 'VerseController@show');

$router->get('/topics', 'TopicController@index');
$router->get('/topics/{slug}', 'TopicController@show');

$router->get('/situations', 'SituationController@index');
$router->get('/situations/{slug}', 'SituationController@show');

$router->get('/mantras', 'MantraController@index');
$router->get('/mantras/{slug}', 'MantraController@show');

$router->get('/search', 'SearchController@index');

$router->get('/daily-wisdom', 'DailyWisdomController@index');

$router->get('/lang/{code}', 'LanguageController@switch');
