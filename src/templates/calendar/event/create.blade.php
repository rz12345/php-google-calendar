<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="./../../../bower_components/jquery/dist/jquery.min.js"></script>
    <script src="./../../../bower_components/datetimepicker/build/jquery.datetimepicker.full.min.js"></script>
    <link rel="stylesheet" href="./../../../bower_components/datetimepicker/jquery.datetimepicker.css">
    <title>Create Event</title>
</head>
<body>
<form action="" method="post">    
    行事曆ID
    <select name="calendarId">
        @foreach ($calendars as $calendar)
        <option value="{{$calendar->getId()}}">{{$calendar->getSummary()}}</option>
        @endforeach
    </select>
    <br/>
    標題<input type="text" name="title"><br/>
    地點<input type="text" name="where"><br/>
    與會者<input type="text" name="attendees" placeholder="example@abc.com"><br/>
    敘述<textarea name="description" cols="30" rows="10"></textarea><br/>
    起始時間<input type="text" name="start" id="datetimepicker1" placeholder="click me"><br/>
    結束時間<input type="text" name="end" id="datetimepicker2" placeholder="click me"><br/>
    popup 提醒時間<input type="number" name="reminderTime" placeholder="留白會套用預設提醒時間"><br/>
    <input type="submit" value="提交">
</form>
<script>
    var option = {
        format: 'Y-m-d H:i'
    };
    jQuery('#datetimepicker1').datetimepicker(option);
    jQuery('#datetimepicker2').datetimepicker(option);
</script>
</body>
</html>