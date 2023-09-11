#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-6');
$execution->name->range('项目1,迭代1,迭代2,迭代3,迭代4,迭代5');
$execution->type->range('project,stage,sprint,stage{2},sprint');
$execution->parent->range('0,1{3},2{2}');
$execution->status->range('wait');
$execution->gen(6);

su('admin');

/**

title=测试executionModel->setTreePathTest();
timeout=0
cid=1

*/

$executionIDList = array('2', '4', '10');

$execution = new executionTest();
r($execution->setTreePathTest($executionIDList[0])) && p('2:project,parent') && e('0,1');   // 子阶段设置path
r($execution->setTreePathTest($executionIDList[1])) && p('4:path', ';')      && e(',1,4,'); // 子阶段设置path
r($execution->setTreePathTest($executionIDList[2])) && p()                   && e('0');     // 不存在阶段设置path
