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
    <?php $__currentLoopData = $calendars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $calendar): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
    <tr>
        <td>
            <?php echo e($calendar->getId()); ?>

        </td>
        <td>
            <?php echo e($calendar->getSummary()); ?>

        </td>
        <td>
            <a href="<?php echo e('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '/' . $calendar->getId()); ?>">events</a>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
    </tbody>
</table>
</body>
</html>