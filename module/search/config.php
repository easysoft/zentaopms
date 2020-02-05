<?php
$config->search = new stdclass();
$config->search->groupItems = 3;

$config->search->dynamic['$lastMonth'] = '$lastMonth';
$config->search->dynamic['$thisMonth'] = '$thisMonth';
$config->search->dynamic['$lastWeek']  = '$lastWeek';
$config->search->dynamic['$thisWeek']  = '$thisWeek';
$config->search->dynamic['$yesterday'] = '$yesterday';
$config->search->dynamic['$today']     = '$today';
