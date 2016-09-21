<?php

include_once __DIR__ . '/../vendor/autoload.php';

// Router instance
$router = new \Bramus\Router\Router();

// Define routes
$router->mount('/token', function() use ($router) {
    // 顯示 tokens 建立時間
    $router->get('/', '\App\Controllers\TokenController@showTokensInfo');

    // redirect auth page
    $router->get('/auth', '\App\Controllers\TokenController@requestAuth');

    // auth handler
    $router->get('/generate', '\App\Controllers\TokenController@redirectHandler');
});

$router->mount('/calendar', function() use ($router) {
    // show user's owner calendars
    $router->get('/', 'App\Views\CalendarView@showCalendars');

    // show calendar's events
    $router->get('/([^/]+)', 'App\Views\CalendarView@showCalendarEvents');

    // create calendar's event
    $router->get('/event/create', 'App\Views\CalendarView@createEvent');
    $router->post('/event/create', 'App\Controllers\CalendarController@createCalendarEvent');

    // delete calendar's event
    $router->get('/([^/]+)/event/([^/]+)/delete', 'App\Controllers\CalendarController@deleteCalendarEvent');
});

// Run 
$router->run();
