#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);
su('admin');

/**

title=测试executionModel->getByIdListTest();
timeout=0
cid=1

*/

$executionIdList = array(3, 4, 5);

global $tester;
$tester->loadModel('execution');

$executionList = $tester->execution->getByIdList($executionIdList);

r($executionList) && p('3:name')   && e('迭代1'); // 敏捷项目查询
r($executionList) && p('4:type')   && e('stage'); // 瀑布项目查询
r($executionList) && p('5:status') && e('doing'); // 看板项目查询
