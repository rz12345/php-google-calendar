<?php

namespace App\Views;

use Philo\Blade\Blade;
use App\Services\GoogleAuthService;
use App\Services\GoogleCalendarService;

class CalendarView extends View {
    
    const QUERY_START_YEAR = 2016; // calendar events 查詢起始年度

    /**
     * 顯示 User 所有 calendar
     */
    public function showCalendars() {
        $client = GoogleAuthService::oauth2Client();
        $calendars = GoogleCalendarService::queryCalendars($client);

        // 篩出 user 擁有的 calendars
        $owner_calendars = array_filter($calendars, function($calendar) {
            return $calendar->accessRole === "owner";
        });

        // view
        $blade = new Blade(View::VIEWS_DIR, View::VIEWS_CACHE_DIR);
        echo $blade->view()->make('calendar.list', [
            'calendars' => $owner_calendars
        ])->render();
    }
    
    /**
     * 顯示 calendar 的所有事件
     * @param $calendarId
     */
    public function showCalendarEvents($calendarId) {
        $client = GoogleAuthService::oauth2Client();
        $events = GoogleCalendarService::queryEvents($client, $calendarId, self::QUERY_START_YEAR);
        $result = array_map(function($event) {
            $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '/event/' . $event->getId() . '/delete';
            return [
                'id' => $event->getId(),
                'title' => $event->getSummary(),
                'where' => $event->getLocation(),
                'description' => $event->getDescription(),
                'startAt' => $event->getStart()->getDate() !== null ? $event->getStart()->getDate() : $event->getStart()->getDateTime(),
                'endAt' => $event->getEnd()->getDate() !== null ? $event->getEnd()->getDate() : $event->getEnd()->getDateTime(),
                '_deleteEventUrl' => $url
            ];
        }, $events);

        // view
        $blade = new Blade(View::VIEWS_DIR, View::VIEWS_CACHE_DIR);
        echo $blade->view()->make('calendar.event.list', [
            'events' => $result
        ])->render();
    }

    public function createEvent() {
        $client = GoogleAuthService::oauth2Client();
        $calendars = GoogleCalendarService::queryCalendars($client);

        // 篩出 user 擁有的 calendars
        $owner_calendars = array_filter($calendars, function($calendar) {
            return $calendar->accessRole === "owner";
        });

        // view
        $blade = new Blade(View::VIEWS_DIR, View::VIEWS_CACHE_DIR);
        echo $blade->view()->make('calendar.event.create', [
            'calendars' => $owner_calendars
        ])->render();
    }

}
