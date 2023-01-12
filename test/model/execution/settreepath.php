#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

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
cid=1
pid=1

子阶段设置path >> 0,1,,1,2,
子阶段设置path >> 0,1,,1,4,
子阶段设置path >> 0,2,,1,2,5,

*/

$executionIDList  = array('2', '4', '5');

$execution = new executionTest();
r($execution->setTreePathTest($executionIDList[0])) && p('2:project,parent,path') && e('0,1,,1,2,');   // 子阶段设置path
r($execution->setTreePathTest($executionIDList[1])) && p('4:project,parent,path') && e('0,1,,1,4,');   // 子阶段设置path
r($execution->setTreePathTest($executionIDList[2])) && p('5:project,parent,path') && e('0,2,,1,2,5,'); // 子阶段设置path
