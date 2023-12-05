#!/usr/bin/env php
<?php
/**

title=测试 docModel->getLinkedExecutionData();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$projectstoryTable = zdTable('projectstory');
$projectstoryTable->project->range('101-110');
$projectstoryTable->gen(20);

$taskTable = zdTable('task');
$taskTable->execution->range('101-110');
$taskTable->gen(20);

$buildTable = zdTable('build');
$buildTable->execution->range('101-110');
$buildTable->gen(20);

zdTable('project')->config('execution')->gen(10);
zdTable('user')->gen(5);
su('admin');

$executions = array(0, 101, 106);

$docTester = new docTest();
r($docTester->getLinkedExecutionDataTest($executions[0])) && p('0')      && e('~~');   // 获取系统中关联执行ID=0的数据
r($docTester->getLinkedExecutionDataTest($executions[1])) && p('1', ';') && e('1,11'); // 获取系统中关联执行ID=101的数据
r($docTester->getLinkedExecutionDataTest($executions[2])) && p('2', ';') && e('6,16'); // 获取系统中关联执行ID=106的数据
