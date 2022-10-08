<?php
$calendarHookFile =  $app->getExtensionRoot() . 'biz/my/ext/view/effort.calendar.html.hook.php';
if(file_exists($calendarHookFile)) include $calendarHookFile;
