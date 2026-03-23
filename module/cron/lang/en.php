<?php
$lang->cron->common       = 'Scheduled Task';
$lang->cron->id           = 'ID';
$lang->cron->buildin      = 'Built-in';
$lang->cron->index        = 'Scheduled Task List';
$lang->cron->list         = ' Task List';
$lang->cron->create       = 'Create';
$lang->cron->createAction = 'Create Task';
$lang->cron->edit         = 'Edit Task';
$lang->cron->delete       = 'Delete Task';
$lang->cron->toggle       = 'Activate/Deactivate';
$lang->cron->turnon       = 'On/Off';
$lang->cron->openProcess  = 'Restart';
$lang->cron->restart      = 'Restart Scheduled Task';

$lang->cron->m        = 'Minute';
$lang->cron->h        = 'Hour';
$lang->cron->dom      = 'Day';
$lang->cron->mon      = 'Month';
$lang->cron->dow      = 'Week';
$lang->cron->command  = 'Command';
$lang->cron->status   = 'Status';
$lang->cron->type     = 'Task Type';
$lang->cron->remark   = 'Comment';
$lang->cron->lastTime = 'Last Run';

$lang->cron->turnonList['1'] = 'On';
$lang->cron->turnonList['0'] = 'Off';

$lang->cron->statusList['normal']  = 'Active';
$lang->cron->statusList['running'] = 'Running';
$lang->cron->statusList['stop']    = 'Stopped';

$lang->cron->typeList['zentao'] = 'ZenTao Self Call';
global $config;
if($config->features->cronSystemCall) $lang->cron->typeList['system'] = 'System Command';

$lang->cron->toggleList['start'] = 'Enable';
$lang->cron->toggleList['stop']  = 'Disable';

$lang->cron->confirmDelete = 'Are you sure you want to delete the scheduled task?';
$lang->cron->confirmTurnon = 'Are you sure you want to turn off the scheduled task?';
$lang->cron->introduction  = <<<EOD
<p>Scheduled Task is set to do scheduled actions such as update burndown chart, backup, etc.</p>
EOD;
$lang->cron->confirmOpen = <<<EOD
<p>Do you want to turn it on?<a href="%s"><strong>Turn On Scheduled Task<strong></a></p>
EOD;

$lang->cron->notice = new stdclass();
$lang->cron->notice->m    = 'Range:0-59，"*" means the numbers within the range, "/" means "per", "-" means the range.';
$lang->cron->notice->h    = 'Range:0-23';
$lang->cron->notice->dom  = 'Range:1-31';
$lang->cron->notice->mon  = 'Range:1-12';
$lang->cron->notice->dow  = 'Range:0-6';
$lang->cron->notice->help = 'Note: If the server is restarted or you notice that scheduled tasks are not running properly, it means the scheduler has stopped. You need to manually click the Restart button, or refresh the page after one minute, to resume the scheduled tasks. If the “Last Run” of the first record in the task list changes, it indicates that the scheduler has started successfully.';
$lang->cron->notice->errorRule = '"%s" is not a valid value.';
$lang->cron->notice->errorType = 'Cannot create a scheduled task of the “Operating System Command” type.';
