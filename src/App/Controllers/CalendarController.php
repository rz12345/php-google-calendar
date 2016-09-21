<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Google_Service_Calendar;
use App\Services\GoogleAuthService;
use App\Services\GoogleCalendarService;

class CalendarController extends Controller {

    /**
     * 新增 calendar event
     */
    public function createCalendarEvent() {
        //  request params
        $requests = Request::createFromGlobals();

        // check form POST
        if (count($requests->request->all()) === 0) {
            die('request without POST parameters');
        }
        $request = $requests->request;

        // 資料欄位      
        $calendarId = $request->get('calendarId'); // string
        $title = $request->get('title'); // string
        $where = $request->get('where'); // string 
        $description = $request->get('description'); // string 
        $startDatetime = $request->get('start'); // string: '2016-05-17 08:00'
        $endDatetime = $request->get('end'); // string: '2016-05-17 10:00'
        $attendees = $request->get('attendees'); // string: 'abc@mail.com;xyz@mail.com' (多個 email 以 ; 分隔)
        $reminderTime = $request->get('reminderTime'); // integers: 50 
        // create event
        $client = GoogleAuthService::oauth2Client();
        $result = GoogleCalendarService::createEvent($client, $calendarId, $title, $where, $description, $attendees, $startDatetime, $endDatetime, $reminderTime);
        // 成功時,僅輸出　eventId
        echo $result['eventId'];
    }

    /**
     * 刪除 calendar event
     * 成功刪除時, 返回 1
     * 
     * @param type $calendarId
     * @param type $eventId
     */
    public function deleteCalendarEvent($calendarId, $eventId) {
        // delete event
        $client = GoogleAuthService::oauth2Client();
        $result = GoogleCalendarService::deleteEvent($client, $calendarId, $eventId);
        echo $result;
    }

}
