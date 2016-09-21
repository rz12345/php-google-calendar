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
    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
    <tr>
        <td>
            <div>Title:<br/><?php echo e($event['title']); ?></div>
            <div>Description:<br/><?php echo e(nl2br($event['description'])); ?></div>
        </td>
        <td>
            <div>Start at:<br/><?php echo e($event['startAt']); ?></div>
            <div>End at:<br/><?php echo e($event['endAt']); ?></div>
        </td>
        <td><a href="<?php echo e($event['_deleteEventUrl']); ?>">刪除</a></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
    </tbody>
</table>
</body>
</html>