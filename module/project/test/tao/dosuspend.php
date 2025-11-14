#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

zenData('project')->gen(5);

/**

title=测试 projectTao::doSuspend();
timeout=0
cid=17901

- 更新项目2 @1
- 更新不存在的项目 @1
- 项目2修改前的状态
 - 属性status @wait
 - 属性suspendedDate @~~
 - 属性lastEditedBy @~~
 - 属性lastEditedDate @~~
- 项目2修改后的状态
 - 属性status @suspended
 - 属性suspendedDate @2023-04-27
 - 属性lastEditedBy @admin
 - 属性lastEditedDate @2023-04-27 00:00:00
- 项目10修改前的状态 @0
- 项目10修改后的状态 @0

*/

global $tester;
$tester->loadModel('project');

$project =  new stdClass;
$project->status         = 'suspended';
$project->lastEditedBy   = 'admin';
$project->lastEditedDate = '2023-04-27';
$project->suspendedDate  = '2023-04-27';

$oldProduct2  = $tester->project->fetchById(2);
$oldProduct10 = $tester->project->fetchById(10);

r($tester->project->doSuspend(2, $project))  && p() && e('1'); // 更新项目2
r($tester->project->doSuspend(10, $project)) && p() && e('1'); // 更新不存在的项目

$newProduct2  = $tester->project->fetchById(2);
$newProduct10 = $tester->project->fetchById(10);

r($oldProduct2)  && p('status,suspendedDate,lastEditedBy,lastEditedDate') && e('wait,~~,~~,~~'); // 项目2修改前的状态
r($newProduct2)  && p('status,suspendedDate,lastEditedBy,lastEditedDate') && e('suspended,2023-04-27,admin,2023-04-27 00:00:00'); // 项目2修改后的状态
r($oldProduct10) && p() && e('0'); // 项目10修改前的状态
r($newProduct10) && p() && e('0'); // 项目10修改后的状态