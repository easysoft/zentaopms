#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,waterfall,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);
su('admin');

/**

title=executionModel->getByID();
timeout=0
cid=1

- 根据executionID查找任务详情属性name @迭代1
- 根据executionID查找任务详情并替换图片链接属性name @迭代1
- 查询存在的执行 @0

*/

$execution = new executionTest();
r($execution->getByIDTest(3))       && p('name') && e('迭代1'); // 根据executionID查找任务详情
r($execution->getByIDTest(3, true)) && p('name') && e('迭代1'); // 根据executionID查找任务详情并替换图片链接
r($execution->getByIDTest(6))       && p()       && e('0');     // 查询存在的执行
