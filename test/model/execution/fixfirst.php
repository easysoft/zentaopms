#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->code->range('1-5')->prefix('code');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$burn = zdTable('burn');
$burn->execution->range('3{5},4{5},5{5}');
$burn->date->range('20220111 000000:1D')->type('timestamp')->format('YY/MM/DD');
$burn->estimate->range('94.3,56.3,55.3,37.8,33.8');
$burn->left->range('95.3,68.5,73.9,40.2,36,3');
$burn->consumed->range('20.1,33.4,41,56.55,59.55');
$burn->storyPoint->range('0,16.5,16,11.5,9');
$burn->gen(15);

$task = zdTable('task');
$task->id->range('1-10');
$task->execution->range('3');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->consumed->range('1-10');
$task->gen(10);

/**

title=测试executionModel->fixFirstTest();
cid=1
pid=1

不传入withLeft >> 25
敏捷执行更新首日剩余工时 >> 5
瀑布执行更新首日剩余工时 >> 17

*/

$executionIDList = array(3, 4);

$scrumEstimate     = array('estimate' => '25');
$withLeft          = array('estimate' => '5', 'withLeft' => '5');
$waterfallEstimate = array('estimate' => '17', 'withLeft' => '1');
$date              = '2022-01-12';

$executionTester = new executionTest();
r($executionTester->fixFirstTest($executionIDList[0], $scrumEstimate, $date))     && p('0:estimate') && e('25');           // 不传入withLeft
r($executionTester->fixFirstTest($executionIDList[0], $withLeft, $date))          && p('0:left')     && e('5');                // 敏捷执行更新首日剩余工时
r($executionTester->fixFirstTest($executionIDList[1], $waterfallEstimate, $date)) && p('0:estimate') && e('17'); // 瀑布执行更新首日剩余工时
