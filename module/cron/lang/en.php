<?php
$lang->cron->common  = 'Cron';
$lang->cron->index   = 'Index';
$lang->cron->list    = 'List';
$lang->cron->create  = 'Create';
$lang->cron->edit    = 'Edit';
$lang->cron->delete  = 'Delete';
$lang->cron->toggle  = 'Activation/Disable';
$lang->cron->turnon  = 'Open/Close';

$lang->cron->m        = 'Minute';
$lang->cron->h        = 'Hour';
$lang->cron->dom      = 'Day';
$lang->cron->mon      = 'Month';
$lang->cron->dow      = 'Week';
$lang->cron->command  = 'Command';
$lang->cron->status   = 'Status';
$lang->cron->type     = 'Type';
$lang->cron->remark   = 'Remark';
$lang->cron->lastTime = 'Last run time';

$lang->cron->turnonList['1'] = 'Open';
$lang->cron->turnonList['0'] = 'Close';

$lang->cron->statusList['normal']  = 'Normal';
$lang->cron->statusList['running'] = 'Running';
$lang->cron->statusList['stop']    = 'Stop';

$lang->cron->typeList['zentao'] = 'Self call';
$lang->cron->typeList['system'] = 'System command';

$lang->cron->toggleList['start'] = 'Activation';
$lang->cron->toggleList['stop']  = 'Disable';

$lang->cron->confirmDelete = 'Do you want to delete the task?';
$lang->cron->confirmTurnon = 'Do you want to trunoff cron?';
$lang->cron->introduction  = <<<EOD
<p>Timing tasks such as compute burn , backup. Absolve themselves of layout timing task.</p>
<p>This function has yet to be perfect, so the function is turned off by default</p>
EOD;
$lang->cron->confirmOpen = <<<EOD
<p>Whether to open the function? <a href="%s" target='hiddenwin'>Open timing task</a></p>
EOD;

$lang->cron->notice = new stdclass();
$lang->cron->notice->m   = 'Range : 0-59，"*" express the range of numbers, "/" express "Every", "-" express digital range.';
$lang->cron->notice->h   = 'Range : 0-23';
$lang->cron->notice->dom = 'Range : 1-31';
$lang->cron->notice->mon = 'Range : 1-12';
$lang->cron->notice->dow = 'Range : 0-6';
