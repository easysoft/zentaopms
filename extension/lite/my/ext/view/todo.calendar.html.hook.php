<?php
$calendarHookFile = $app->getExtensionRoot() . 'biz/my/ext/view/todo.calendar.html.hook.php';
if(file_exists($calendarHookFile)) include $calendarHookFile;
