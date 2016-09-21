<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List Calendar's Event</title>
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
<h1>List Calendar's Event</h1>
<table>
    <thead>
    <tr>
        <th>Title/Description</th>
        <th>Start/End Datetime</th>
        <th>Delete!</th>
    </tr>

    </thead>
    <tbody>
    @foreach ($events as $event)
    <tr>
        <td>
            <div>Title:<br/>{{$event['title']}}</div>
            <div>Description:<br/>{{nl2br($event['description'])}}</div>
        </td>
        <td>
            <div>Start at:<br/>{{$event['startAt']}}</div>
            <div>End at:<br/>{{$event['endAt']}}</div>
        </td>
        <td><a href="{{$event['_deleteEventUrl']}}">刪除</a></td>
    </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>