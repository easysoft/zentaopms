<?php
$lang->integration->browse        = 'Browse Integration';
$lang->integration->create        = 'Create Integration';
$lang->integration->edit          = 'Edit Integration';
$lang->integration->execNow       = 'Execute now';
$lang->integration->delete        = 'Delete Integration';
$lang->integration->confirmDelete = 'Do you want to delete this Build?';

$lang->integration->id          = 'ID';
$lang->integration->name        = 'Name';
$lang->integration->repo        = 'Repo';
$lang->integration->svnFolder   = 'SVN Tag Watch Path';
$lang->integration->jenkins     = 'Jenkins Server';
$lang->integration->buildType   = 'Build Type';
$lang->integration->jenkinsJob  = 'Jenkins Task';
$lang->integration->triggerType = 'Trigger';
$lang->integration->scheduleDay = 'Custom Days';
$lang->integration->lastExec    = 'Last Executed';

$lang->integration->example    = 'e.g.';
$lang->integration->commitEx   = '%build% %integration% %id%15, to build Jenkins job that id is 15.';
$lang->integration->cronSample = 'e.g. 0 0 2 * * 2-6/1 means 2:00 a.m. every weekday.';
$lang->integration->sendExec   = 'Send execute request success.';

$lang->integration->buildTypeList['build']          = 'Only Build';
$lang->integration->buildTypeList['buildAndDeploy'] = 'Build And Deploy';
$lang->integration->buildTypeList['buildAndTest']   = 'Build And Test';

$lang->integration->triggerTypeList['tag']      = 'Tag';
$lang->integration->triggerTypeList['commit']   = 'Code Commit';
$lang->integration->triggerTypeList['schedule'] = 'Schedule';
