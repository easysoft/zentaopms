#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-11');
$execution->name->setFields(array(
    array('field' => 'name1', 'range' => '项目{2},执行{3},迭代{2},阶段{2},看板{2}'),
    array('field' => 'name2', 'range' => '1-3'),
));
$execution->type->range('project{2},sprint{5},waterfall{2},kanban{2}');
$execution->status->range('doing{11}');
$execution->parent->range('0');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(11);
su('admin');

/**

title=测试executionModel->updateChildsTest();
cid=1
pid=1

修改敏捷执行的父执行 >> 6,迭代3
修改敏捷执行的父执行 >> 9,阶段3
修改敏捷执行的父执行 >> 10,看板1
修改敏捷执行的父执行 >> 2
修改敏捷执行的父执行 >> 2
修改敏捷执行的父执行 >> 2

*/

$executionIDList   = array('3', '4', '5');
$sprintExecutionID = array('6', '7');
$stageExecutionID  = array('8', '9');
$kanbanExecutionID = array('10', '11');
$count             = array('0','1');

$sprintChilds = array('childs' => $sprintExecutionID);
$stageChilds  = array('childs' => $stageExecutionID);
$kanbanChilds = array('childs' => $kanbanExecutionID);

$execution = new executionTest();
r($execution->updateChildsTest($executionIDList[0], $count[0], $sprintChilds)) && p('0:id,name') && e('6,迭代3');  // 修改敏捷执行的父执行
r($execution->updateChildsTest($executionIDList[1], $count[0], $stageChilds))  && p('1:id,name') && e('9,阶段3');  // 修改敏捷执行的父执行
r($execution->updateChildsTest($executionIDList[2], $count[0], $kanbanChilds)) && p('0:id,name') && e('10,看板1'); // 修改敏捷执行的父执行
r($execution->updateChildsTest($executionIDList[0], $count[1], $sprintChilds)) && p()            && e('2');        // 修改敏捷执行的父执行
r($execution->updateChildsTest($executionIDList[1], $count[1], $stageChilds))  && p()            && e('2');        // 修改敏捷执行的父执行
r($execution->updateChildsTest($executionIDList[2], $count[1], $kanbanChilds)) && p()            && e('2');        // 修改敏捷执行的父执行
