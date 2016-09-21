<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List User's Calendars</title>
    <style>
        thead{
            background: #CCC;
        }
        tbody > tr:nth-child(even) {
            background: #b1cccc;
        }
    </style>
</head>
<body>
<h1>List User's Calendars</h1>
<table>
    <thead>
    <tr>
        <th>id</th>
        <th>title</th>
        <th>event url</th>
    </tr>

    </thead>
    <tbody>
    @foreach ($calendars as $calendar)
    <tr>
        <td>
            {{$calendar->getId()}}
        </td>
        <td>
            {{$calendar->getSummary()}}
        </td>
        <td>
            <a href="{{'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '/' . $calendar->getId()}}">events</a>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>