#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing');
$execution->parent->range('0,0,1,1,2');
$execution->project->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$task = zenData('task');
$task->id->range('1-10');
$task->name->range('1-10')->prefix('任务');
$task->execution->range('3');
$task->type->range('test,devel,design');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->consumed->range('1-10');
$task->fromBug->range('0,1');
$task->story->range('0,1');
$task->parent->range('0,1');
$task->module->range('0,2');
$task->gen(10);

su('admin');

/**

title=executionModel->getSearchTasks();
timeout=0
cid=16337

- 测试通过sql语句获取3个任务 id 倒序 execution = '3' and deleted = '0' and parent >= 0第10条的name属性 @任务10
- 测试通过sql语句获取3个任务 id 正序 execution = '3' and deleted = '0' and parent >= 0第1条的name属性 @任务1
- 测试通过sql语句获取5个任务 id 倒序 execution = '3' and deleted = '0' and parent >= 0第10条的name属性 @任务10
- 测试通过sql语句获取5个任务 id 正序 execution = '3' and deleted = '0' and parent >= 0第1条的name属性 @任务1
- 测试通过sql语句获取3个任务 id 倒序 type = 'devel' and fromBug != '0' and parent >= 0第8条的name属性 @任务8
- 测试通过sql语句获取3个任务 id 正序 type = 'devel' and fromBug != '0' and parent >= 0第2条的name属性 @任务2
- 测试通过sql语句获取5个任务 id 倒序 type = 'devel' and fromBug != '0' and parent >= 0第8条的name属性 @任务8
- 测试通过sql语句获取5个任务 id 正序 type = 'devel' and fromBug != '0' and parent >= 0第2条的name属性 @任务2
- 测试通过sql语句获取3个任务 id 倒序 execution = '3' and story != '0' and parent >= 0第10条的name属性 @任务10
- 测试通过sql语句获取3个任务 id 正序 execution = '3' and story != '0' and parent >= 0第2条的name属性 @任务2
- 测试通过sql语句获取5个任务 id 倒序 execution = '3' and story != '0' and parent >= 0第10条的name属性 @任务10
- 测试通过sql语句获取5个任务 id 正序 execution = '3' and story != '0' and parent >= 0第2条的name属性 @任务2
- 测试通过sql语句获取3个任务 id 倒序 module like '%2%' and type = 'design' and parent >= 0第6条的name属性 @任务6
- 测试通过sql语句获取3个任务 id 正序 module like '%2%' and type = 'design' and parent >= 0第6条的name属性 @任务6
- 测试通过sql语句获取5个任务 id 倒序 module like '%2%' and type = 'design' and parent >= 0第6条的name属性 @任务6
- 测试通过sql语句获取5个任务 id 正序 module like '%2%' and type = 'design' and parent >= 0第6条的name属性 @任务6

*/

global $app;
$app->rawModule = 'execution';
$app->rawMethod = 'task';

$condition = array();
$condition[] = "t1.execution = '3' AND t1.deleted = '0' AND t1.parent >= 0";
$condition[] = "t1.type = 'devel' AND t1.fromBug != '0' AND t1.parent >= 0";
$condition[] = "t1.execution = '3' AND t1.story != '0' AND t1.parent >= 0";
$condition[] = "t1.module LIKE '%2%' AND t1.type = 'design' AND t1.parent >= 0";

$recPerPage = array('3', '5');
$orderBy = array('id_desc', 'id_asc');

$execution = new executionTest();
r($execution->getSearchTasksTest($condition[0], $orderBy[0], $recPerPage[0])) && p('10:name') && e('任务10'); // 测试通过sql语句获取3个任务 id 倒序 execution = '3' and deleted = '0' and parent >= 0
r($execution->getSearchTasksTest($condition[0], $orderBy[1], $recPerPage[0])) && p('1:name')  && e('任务1');  // 测试通过sql语句获取3个任务 id 正序 execution = '3' and deleted = '0' and parent >= 0
r($execution->getSearchTasksTest($condition[0], $orderBy[0], $recPerPage[1])) && p('10:name') && e('任务10'); // 测试通过sql语句获取5个任务 id 倒序 execution = '3' and deleted = '0' and parent >= 0
r($execution->getSearchTasksTest($condition[0], $orderBy[1], $recPerPage[1])) && p('1:name')  && e('任务1');  // 测试通过sql语句获取5个任务 id 正序 execution = '3' and deleted = '0' and parent >= 0
r($execution->getSearchTasksTest($condition[1], $orderBy[0], $recPerPage[0])) && p('8:name')  && e('任务8');  // 测试通过sql语句获取3个任务 id 倒序 type = 'devel' and fromBug != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[1], $orderBy[1], $recPerPage[0])) && p('2:name')  && e('任务2');  // 测试通过sql语句获取3个任务 id 正序 type = 'devel' and fromBug != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[1], $orderBy[0], $recPerPage[1])) && p('8:name')  && e('任务8');  // 测试通过sql语句获取5个任务 id 倒序 type = 'devel' and fromBug != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[1], $orderBy[1], $recPerPage[1])) && p('2:name')  && e('任务2');  // 测试通过sql语句获取5个任务 id 正序 type = 'devel' and fromBug != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[2], $orderBy[0], $recPerPage[0])) && p('10:name') && e('任务10'); // 测试通过sql语句获取3个任务 id 倒序 execution = '3' and story != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[2], $orderBy[1], $recPerPage[0])) && p('2:name')  && e('任务2');  // 测试通过sql语句获取3个任务 id 正序 execution = '3' and story != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[2], $orderBy[0], $recPerPage[1])) && p('10:name') && e('任务10'); // 测试通过sql语句获取5个任务 id 倒序 execution = '3' and story != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[2], $orderBy[1], $recPerPage[1])) && p('2:name')  && e('任务2');  // 测试通过sql语句获取5个任务 id 正序 execution = '3' and story != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[3], $orderBy[0], $recPerPage[0])) && p('6:name')  && e('任务6');  // 测试通过sql语句获取3个任务 id 倒序 module like '%2%' and type = 'design' and parent >= 0
r($execution->getSearchTasksTest($condition[3], $orderBy[1], $recPerPage[0])) && p('6:name')  && e('任务6');  // 测试通过sql语句获取3个任务 id 正序 module like '%2%' and type = 'design' and parent >= 0
r($execution->getSearchTasksTest($condition[3], $orderBy[0], $recPerPage[1])) && p('6:name')  && e('任务6');  // 测试通过sql语句获取5个任务 id 倒序 module like '%2%' and type = 'design' and parent >= 0
r($execution->getSearchTasksTest($condition[3], $orderBy[1], $recPerPage[1])) && p('6:name')  && e('任务6');  // 测试通过sql语句获取5个任务 id 正序 module like '%2%' and type = 'design' and parent >= 0