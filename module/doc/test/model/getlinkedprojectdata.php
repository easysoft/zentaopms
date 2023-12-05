#!/usr/bin/env php
<?php
/**

title=测试 docModel->getLinkedProjectData();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$projectstoryTable = zdTable('projectstory');
$projectstoryTable->project->range('11, 60, 61, 100');
$projectstoryTable->gen(20);

$issuetable = zdtable('issue');
$issuetable->project->range('11, 60, 61, 100');
$issuetable->gen(20);

$meetingTable = zdTable('meeting');
$meetingTable->project->range('11, 60, 61, 100');
$meetingTable->gen(20);

$reviewTable = zdTable('review');
$reviewTable->project->range('11, 60, 61, 100');
$reviewTable->gen(20);

$designTable = zdTable('design');
$designTable->project->range('11, 60, 61, 100');
$designTable->gen(20);

$taskTable = zdTable('task');
$taskTable->execution->range('101-110');
$taskTable->gen(20);

$buildTable = zdTable('build');
$buildTable->execution->range('101-110');
$buildTable->gen(20);

zdTable('project')->config('execution')->gen(10);
zdTable('user')->gen(5);
su('admin');

$projects = array(0, 11, 60);
$editions = array('open', 'max', 'ipd');

$docTester = new docTest();
r($docTester->getLinkedProjectDataTest($projects[0], $editions[0])) && p('4') && e("SELECT id FROM `zt_design` WHERE `project`  = '0' AND  `deleted`  = '0'");   // 获取开源版系统中所有关联项目的数据
r($docTester->getLinkedProjectDataTest($projects[0], $editions[1])) && p('1') && e("SELECT id FROM `zt_issue` WHERE `project`  = '0' AND  `deleted`  = '0'");    // 获取旗舰版系统中所有关联项目的数据
r($docTester->getLinkedProjectDataTest($projects[0], $editions[2])) && p('2') && e("SELECT id FROM `zt_meeting` WHERE `project`  = '0' AND  `deleted`  = '0'");  // 获取ipd版系统中所有关联项目的数据
r($docTester->getLinkedProjectDataTest($projects[1], $editions[0])) && p('4') && e("SELECT id FROM `zt_design` WHERE `project`  = '11' AND  `deleted`  = '0'");  // 获取开源版系统中所有关联项目ID=11的数据
r($docTester->getLinkedProjectDataTest($projects[1], $editions[1])) && p('1') && e("SELECT id FROM `zt_issue` WHERE `project`  = '11' AND  `deleted`  = '0'");   // 获取旗舰版系统中所有关联项目ID=11的数据
r($docTester->getLinkedProjectDataTest($projects[1], $editions[2])) && p('2') && e("SELECT id FROM `zt_meeting` WHERE `project`  = '11' AND  `deleted`  = '0'"); // 获取ipd版系统中所有关联项目ID=11的数据
r($docTester->getLinkedProjectDataTest($projects[2], $editions[0])) && p('4') && e("SELECT id FROM `zt_design` WHERE `project`  = '60' AND  `deleted`  = '0'");  // 获取开源版系统中所有关联项目ID=60的数据
r($docTester->getLinkedProjectDataTest($projects[2], $editions[1])) && p('1') && e("SELECT id FROM `zt_issue` WHERE `project`  = '60' AND  `deleted`  = '0'");   // 获取旗舰版系统中所有关联项目ID=60的数据
r($docTester->getLinkedProjectDataTest($projects[2], $editions[2])) && p('2') && e("SELECT id FROM `zt_meeting` WHERE `project`  = '60' AND  `deleted`  = '0'"); // 获取ipd版系统中所有关联项目ID=60的数据
