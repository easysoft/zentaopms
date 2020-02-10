<?php
$lang->ci->common         = 'CI';
$lang->ci->at             = ' at ';

$lang->ci->jenkins        = 'Jenkins';
$lang->ci->repo           = 'Repo';
$lang->ci->job            = 'Job';
$lang->ci->browse         = 'View';
$lang->ci->create         = 'Create';
$lang->ci->edit           = 'Edit';

$lang->job->browseBuild   = 'Build Histories';
$lang->job->viewLogs      = 'Build Logs';

$lang->job->exeNow        = 'Execute now';
$lang->job->delete        = 'Delete';
$lang->job->confirmDelete = 'Do you want to delete this Build?';

$lang->job->buildStatus   = 'Build Status';
$lang->job->buildTime     = 'Build Time';

$lang->job->id             = 'ID';
$lang->job->name           = 'Name';
$lang->job->repo           = 'Repo';
$lang->job->svnFolder      = 'SVN tag parent URL';
$lang->job->jenkins        = 'Jenkins Server';
$lang->job->jenkinsJob     = 'Jenkins Task';
$lang->job->triggerType    = 'Trigger';
$lang->job->scheduleType   = 'Schedule';
$lang->job->cronExpression = 'Cron Expression';
$lang->job->custom         = 'Custom';

$lang->job->at               = 'executed on';
$lang->job->exe              = '';
$lang->job->scheduleInterval = 'Every';
$lang->job->day              = 'days';
$lang->job->lastExe          = 'Last Executed';
$lang->job->scheduleTime     = 'Time';

$lang->job->example    = 'e.g.';
$lang->job->tagEx      = 'build_#15, to build Jenkins job that id is 15.';
$lang->job->commitEx   = 'start build #15, to build Jenkins job that id is 15.';
$lang->job->cronSample = 'e.g. 0 0 2 * * 2-6/1 means 2:00 a.m. every weekday.';

$lang->job->buildStatusList  = array('success' => 'Success', 'fail' => 'Fail', 'created' => 'Created', 'building' => 'Building', 'create_fail' => 'Fail to create', 'timeout' => 'Exec Timeout');
$lang->job->dayTypeList      = array('workDay' => 'Weekdays', 'everyDay' => 'Every Day');
$lang->job->triggerTypeList  = array('tag' => 'Tag', 'commit' => 'Code Commit', 'schedule' => 'Schedule');
$lang->job->scheduleTypeList = array('cron' => 'Crontab', 'custom' => 'Custom');
