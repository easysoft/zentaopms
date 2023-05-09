#/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . "/project.class.php";
su('admin');

/**

title=测试 projectTao::doUpdate();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');

$project = new stdclass();
$project->model          = 'scrum';
$project->parent         = '1';
$project->lastEditedBy   = 'admin';
$project->lastEditedDate = '2023-04-27';
$project->suspendedDate  = '2023-04-27';

r($tester->project->doSuspend(2, $project)) && p() && e('1');
