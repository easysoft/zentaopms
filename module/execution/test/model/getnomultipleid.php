#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-3');
$execution->name->range('项目集1,项目1,迭代1');
$execution->type->range('program,project,sprint');
$execution->parent->range('0,1,0');
$execution->project->range('0{2},2');
$execution->status->range('wait');
$execution->multiple->range('1{2},0');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(3);

/**

title=测试executionModel->getNoMultipleID();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('execution');

r($tester->execution->getNoMultipleID(2)) & p() && e('3'); // 根据正确项目ID获取被隐藏的执行id
r($tester->execution->getNoMultipleID(0)) & p() && e('0'); // 根据空项目ID获取被隐藏的执行id
r($tester->execution->getNoMultipleID(5)) & p() && e('0'); // 根据不存在的项目ID获取被隐藏的执行id
