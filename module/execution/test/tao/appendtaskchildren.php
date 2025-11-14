#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

/**

title=executionModel->appendTaskChildren();
cid=16381

- 不传入子任务，检查返回值
 - 属性id @1
 - 属性name @test
- 不传入子任务，检查是否有children属性 @0
- 传入一级子任务，检查主任务返回值
 - 属性id @1
 - 属性name @test
- 传入一级子任务，检查主任务是否有children属性 @1
- 传入一级子任务，检查一级子任务的id，name属性
 - 属性id @2
 - 属性name @test2
- 传入二级子任务，检查主任务返回值
 - 属性id @1
 - 属性name @test
- 传入二级子任务，检查主任务是否有children属性 @1
- 传入二级子任务，检查一级子任务的id，name属性
 - 属性id @2
 - 属性name @test2
- 传入二级子任务，检查一级子任务是否有children属性 @1
- 传入二级子任务，检查二级子任务的id，name属性
 - 属性id @3
 - 属性name @ test3

*/

$task = new stdclass();
$task->id   = 1;
$task->name = 'test';

global $tester;
$executionModel = $tester->loadModel('execution');

$cloneTask   = clone $task;
$noChildTask = $executionModel->appendTaskChildren($cloneTask, array());
r($noChildTask)                       && p('id,name') && e('1,test'); //不传入子任务，检查返回值
r((int)isset($noChildTask->children)) && p()          && e('0');      //不传入子任务，检查是否有children属性

$childTasks[1] = array();
$childTasks[1][0] = new stdclass();
$childTasks[1][0]->id   = 2;
$childTasks[1][0]->name = 'test2';

$cloneTask    = clone $task;
$oneLevelTask = $executionModel->appendTaskChildren($cloneTask, $childTasks);
r($oneLevelTask)                       && p('id,name') && e('1,test');  //传入一级子任务，检查主任务返回值
r((int)isset($oneLevelTask->children)) && p()          && e('1');       //传入一级子任务，检查主任务是否有children属性
r($oneLevelTask->children[0])          && p('id,name') && e('2,test2'); //传入一级子任务，检查一级子任务的id，name属性

$childTasks[2] = array();
$childTasks[2][0] = new stdclass();
$childTasks[2][0]->id   = 3;
$childTasks[2][0]->name = 'test3';

$cloneTask    = clone $task;
$twoLevelTask = $executionModel->appendTaskChildren($cloneTask, $childTasks);
r($twoLevelTask)                                    && p('id,name') && e('1,test');   //传入二级子任务，检查主任务返回值
r((int)isset($twoLevelTask->children))              && p()          && e('1');        //传入二级子任务，检查主任务是否有children属性
r($twoLevelTask->children[0])                       && p('id,name') && e('2,test2');  //传入二级子任务，检查一级子任务的id，name属性
r((int)isset($twoLevelTask->children[0]->children)) && p()          && e('1');        //传入二级子任务，检查一级子任务是否有children属性
r($twoLevelTask->children[0]->children[0])          && p('id,name') && e('3, test3'); //传入二级子任务，检查二级子任务的id，name属性
