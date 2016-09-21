<?php

namespace App\Services;

use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Google_Service_Calendar_EventAttendee;
use Google_Service_Calendar_EventReminders;

class GoogleCalendarService {

    /**
     * 查詢 Calendar events
     * @param type $client
     * @param type $calendar_id
     * @param type $start_year
     * @return type
     */
    public static function queryEvents($client, $calendar_id, $start_year) {
        $service = new Google_Service_Calendar($client);
        $query_events = [];

        // 逐年讀取 events
        for ($year = $start_year; $year < date('Y', strtotime('+1 years')); $year++) {
            $start = date(DATE_ATOM, mktime(0, 0, 0, 1, 1, $year));
            $end = date(DATE_ATOM, mktime(0, 0, 0, 12, 31, $year));
            $params = array(
                'singleEvents' => 'true',
                'orderBy' => 'startTime',
                'timeMin' => $start,
                'timeMax' => $end
            );
            try {
                $query = $service->events->listEvents($calendar_id, $params)->getItems();
            } catch (\Exception $e) {
                return $e->getCode();
            }
            $query_events = array_merge($query_events, $query);

            // 延遲向 API request 的速度 (單位 second)
            sleep(1);
        }
        return $query_events;
    }

    /**
     * 查詢 User 所有的 Calendars
     * @param type $client
     * @return type
     */
    public static function queryCalendars($client) {
        $service = new Google_Service_Calendar($client);
        $calendars = $service->calendarList->listCalendarList()->getItems();
        return $calendars;
    }

    /**
     * 新建 event
     * @param type $client
     * @param type $calendar_id
     * @param type $title
     * @param type $where
     * @param type $description
     * @param type $attendees
     * @param string $startAt
     * @param string $endAt
     * @param type $reminderTime
     * @return type
     */
    public static function createEvent($client, $calendar_id, $title, $where, $description, $attendees, $startAt, $endAt, $reminderTime) {
        // insert event params
        $optParams = [];

        $service = new Google_Service_Calendar($client);

        // 經由 API 寫入 event
        $event = new Google_Service_Calendar_Event;
        $event->setSummary($title);   // 事件標題
        $event->setLocation($where);  // 事件地點
        $event->setDescription($description); // 備註
        $start = new Google_Service_Calendar_EventDateTime();
        if (preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $startAt)) {
            $start->setDate($startAt);
        }
        if (preg_match("/^\d{4}\-\d{2}\-\d{2}\s\d{2}\:\d{2}$/", $startAt)) {
            $startAt = substr($startAt, 0, 10) . "T" . substr($startAt, 11, 5) . ":00+08:00";
            $start->setDateTime($startAt);
        }
        $event->setStart($start);   // 事件起始時間
        $end = new Google_Service_Calendar_EventDateTime();
        if (preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $endAt)) {
            $end->setDate($endAt);
        }
        if (preg_match("/^\d{4}\-\d{2}\-\d{2}\s\d{2}\:\d{2}$/", $endAt)) {
            $endAt = substr($endAt, 0, 10) . "T" . substr($endAt, 11, 5) . ":00+08:00";
            $end->setDateTime($endAt);
        }
        $event->setEnd($end);   // 事件結束時間
        // 與會者, 傳入的字串值必須以分號 ';' 隔開
        // example:  "someone@abc.com;somebody@abc.com"
        if (!empty($attendees)) {
            $allAttendees = [];
            foreach (explode(';', $attendees) as $email) {
                $attendee = new Google_Service_Calendar_EventAttendee();
                $attendee->setEmail($email);
                array_push($allAttendees, $attendee);
            }
            $event->setAttendees($allAttendees);
        }

        // reminder
        if (!empty($reminderTime)) {
            $reminders = new Google_Service_Calendar_EventReminders();
            $reminders->setUseDefault(false);
            $overrides = [
                "method" => "popup",
                "minutes" => $reminderTime
            ];
            $reminders->setOverrides(array($overrides));
            $event->setReminders($reminders);
        }

        try {
            $createdEvent = $service->events->insert($calendar_id, $event, $optParams); // 寫入事件
        } catch (\Exception $e) {
            return $e->getCode();
        }
        $eventId = $createdEvent->getId();
        $result = [
            'eventId' => $eventId
        ];
        return $result;
    }

    /**
     * 刪除 event
     * @param $client
     * @param $calendar_id
     * @param $event_id
     * @return int|mixed
     */
    public static function deleteEvent($client, $calendar_id, $event_id) {
        $service = new Google_Service_Calendar($client);
        try {
            $service->events->delete($calendar_id, $event_id);
        } catch (\Exception $e) {
            // 如果發現物件已被刪除會返回 http status code 410
            return $e->getCode();
        }
        return 1;
    }

}
