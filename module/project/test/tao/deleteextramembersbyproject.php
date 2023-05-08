#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

/**

title=测试 projectTao::deleteextramembersbyproject();
timeout=0
cid=1
- 执行project模块的doSuspend方法，参数是2, $project @1
*/

global $tester;
$tester->loadModel('project');

$project =  new stdClass;
$team    =  new stdClass;
$members =  new stdClass;

r($tester->project->deleteextramembersbyproject(2, $project, $team, $members)) && p() && e(1);
