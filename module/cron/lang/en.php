<?php
$lang->cron->common      = 'Scheduled Task';
$lang->cron->index       = 'Home';
$lang->cron->list        = 'Tasks';
$lang->cron->create      = 'Add';
$lang->cron->edit        = 'Edit';
$lang->cron->delete      = 'Delete';
$lang->cron->toggle      = 'Activate/Deactivate';
$lang->cron->turnon      = 'On/Off';
$lang->cron->openProcess = 'Restart';

$lang->cron->m        = 'Min';
$lang->cron->h        = 'Hour';
$lang->cron->dom      = 'Day';
$lang->cron->mon      = 'Month';
$lang->cron->dow      = 'Week';
$lang->cron->command  = 'Command';
$lang->cron->status   = 'Status';
$lang->cron->type     = 'Type';
$lang->cron->remark   = 'Remark';
$lang->cron->lastTime = 'Last Executed';

$lang->cron->turnonList['1'] = 'On';
$lang->cron->turnonList['0'] = 'Shutdown';

$lang->cron->statusList['normal']  = 'Normal';
$lang->cron->statusList['running'] = 'Running';
$lang->cron->statusList['stop']    = 'Stop';

$lang->cron->typeList['zentao'] = 'Self call';
$lang->cron->typeList['system'] = 'System Command';

$lang->cron->toggleList['start'] = 'Activate';
$lang->cron->toggleList['stop']  = 'Deactivate';

$lang->cron->confirmDelete = 'Do you want to delete the Scheduled Task?';
$lang->cron->confirmTurnon = 'Do you want to truncoff the Cron?';
$lang->cron->introduction  = <<<EOD
<p>Scheduled Task can do scheduled execution, such as update burndown chart, backup, etc.</p>
<p>This function need to be improved, so it is turned off by default.</p>
EOD;
$lang->cron->confirmOpen = <<<EOD
<p>Do you want to turn it on?<a href="%s" target='hiddenwin'><strong>Turn On Scheduled Task<strong></a></p>
EOD;

$lang->cron->notice = new stdclass();
$lang->cron->notice->m    = 'Range:0-59，"*" means the numbers within the range, "/" means "per", "-" means ranger.';
$lang->cron->notice->h    = 'Range:0-23';
$lang->cron->notice->dom  = 'Range:1-31';
$lang->cron->notice->mon  = 'Range:1-12';
$lang->cron->notice->dow  = 'Range:0-6';
$lang->cron->notice->help = 'Note：If server restarted, or Scheduled Task is not working, it means Scheduled Task has stopped. You can restart it by clicking 【Restart】 or reload this page. If the last execution time in  changed, it means the Task is running.';
$lang->cron->notice->errorRule = '"%s" is not valid';
