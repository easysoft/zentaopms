#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
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

$task = zdTable('task');
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
cid=1
pid=1

测试通过sql语句获取3个任务 id 倒序 execution = '3' and deleted = '0' and parent >= 0      >> 10:name:任务10;9:name:任务9;8:name:任务8;
测试通过sql语句获取3个任务 id 正序 execution = '3' and deleted = '0' and parent >= 0      >> 1:name:任务1;3:name:任务3;
测试通过sql语句获取5个任务 id 倒序 execution = '3' and deleted = '0' and parent >= 0      >> 10:name:任务10;9:name:任务9;8:name:任务8;7:name:任务7;6:name:任务6;
测试通过sql语句获取5个任务 id 正序 execution = '3' and deleted = '0' and parent >= 0      >> 1:name:任务1;3:name:任务3;5:name:任务5;
测试通过sql语句获取3个任务 id 倒序 type = 'devel' and fromBug != '0' and parent >= 0      >> 8:name:任务8;2:name:任务2;
测试通过sql语句获取3个任务 id 正序 type = 'devel' and fromBug != '0' and parent >= 0      >> 2:name:任务2;8:name:任务8;
测试通过sql语句获取5个任务 id 倒序 type = 'devel' and fromBug != '0' and parent >= 0      >> 8:name:任务8;2:name:任务2;
测试通过sql语句获取5个任务 id 正序 type = 'devel' and fromBug != '0' and parent >= 0      >> 2:name:任务2;8:name:任务8;
测试通过sql语句获取3个任务 id 倒序 execution = '3' and story != '0' and parent >= 0       >> 10:name:任务10;8:name:任务8;6:name:任务6;
测试通过sql语句获取3个任务 id 正序 execution = '3' and story != '0' and parent >= 0       >> 2:name:任务2;4:name:任务4;6:name:任务6;
测试通过sql语句获取5个任务 id 倒序 execution = '3' and story != '0' and parent >= 0       >> 10:name:任务10;8:name:任务8;6:name:任务6;4:name:任务4;2:name:任务2;
测试通过sql语句获取5个任务 id 正序 execution = '3' and story != '0' and parent >= 0       >> 2:name:任务2;4:name:任务4;6:name:任务6;8:name:任务8;10:name:任务10;
测试通过sql语句获取3个任务 id 倒序 module like '%2%' and type = 'design' and parent >= 0  >> 6:name:任务6;
测试通过sql语句获取3个任务 id 正序 module like '%2%' and type = 'design' and parent >= 0  >> 6:name:任务6;
测试通过sql语句获取5个任务 id 倒序 module like '%2%' and type = 'design' and parent >= 0  >> 6:name:任务6;
测试通过sql语句获取5个任务 id 正序 module like '%2%' and type = 'design' and parent >= 0  >> 6:name:任务6;

*/

$condition = array();
$condition[] = "execution = '3' and deleted = '0' and parent >= 0";
$condition[] = "type = 'devel' and fromBug != '0' and parent >= 0";
$condition[] = "execution = '3' and story != '0' and parent >= 0";
$condition[] = "module like '%2%' and type = 'design' and parent >= 0";

$recPerPage = array('3', '5');
$orderBy = array('id_desc', 'id_asc');

$execution = new executionTest();
r($execution->getSearchTasksTest($condition[0], $recPerPage[0], $orderBy[0])) && p() && e('10:name:任务10;9:name:任务9;8:name:任务8;');                           // 测试通过sql语句获取3个任务 id 倒序 execution = '3' and deleted = '0' and parent >= 0
r($execution->getSearchTasksTest($condition[0], $recPerPage[0], $orderBy[1])) && p() && e('1:name:任务1;3:name:任务3;');                                          // 测试通过sql语句获取3个任务 id 正序 execution = '3' and deleted = '0' and parent >= 0
r($execution->getSearchTasksTest($condition[0], $recPerPage[1], $orderBy[0])) && p() && e('10:name:任务10;9:name:任务9;8:name:任务8;7:name:任务7;6:name:任务6;'); // 测试通过sql语句获取5个任务 id 倒序 execution = '3' and deleted = '0' and parent >= 0
r($execution->getSearchTasksTest($condition[0], $recPerPage[1], $orderBy[1])) && p() && e('1:name:任务1;3:name:任务3;5:name:任务5;');                             // 测试通过sql语句获取5个任务 id 正序 execution = '3' and deleted = '0' and parent >= 0
r($execution->getSearchTasksTest($condition[1], $recPerPage[0], $orderBy[0])) && p() && e('8:name:任务8;2:name:任务2;');                                          // 测试通过sql语句获取3个任务 id 倒序 type = 'devel' and fromBug != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[1], $recPerPage[0], $orderBy[1])) && p() && e('2:name:任务2;8:name:任务8;');                                          // 测试通过sql语句获取3个任务 id 正序 type = 'devel' and fromBug != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[1], $recPerPage[1], $orderBy[0])) && p() && e('8:name:任务8;2:name:任务2;');                                          // 测试通过sql语句获取5个任务 id 倒序 type = 'devel' and fromBug != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[1], $recPerPage[1], $orderBy[1])) && p() && e('2:name:任务2;8:name:任务8;');                                          // 测试通过sql语句获取5个任务 id 正序 type = 'devel' and fromBug != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[2], $recPerPage[0], $orderBy[0])) && p() && e('10:name:任务10;8:name:任务8;6:name:任务6;');                           // 测试通过sql语句获取3个任务 id 倒序 execution = '3' and story != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[2], $recPerPage[0], $orderBy[1])) && p() && e('2:name:任务2;4:name:任务4;6:name:任务6;');                             // 测试通过sql语句获取3个任务 id 正序 execution = '3' and story != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[2], $recPerPage[1], $orderBy[0])) && p() && e('10:name:任务10;8:name:任务8;6:name:任务6;4:name:任务4;2:name:任务2;'); // 测试通过sql语句获取5个任务 id 倒序 execution = '3' and story != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[2], $recPerPage[1], $orderBy[1])) && p() && e('2:name:任务2;4:name:任务4;6:name:任务6;8:name:任务8;10:name:任务10;'); // 测试通过sql语句获取5个任务 id 正序 execution = '3' and story != '0' and parent >= 0
r($execution->getSearchTasksTest($condition[3], $recPerPage[0], $orderBy[0])) && p() && e('6:name:任务6;');                                                       // 测试通过sql语句获取3个任务 id 倒序 module like '%2%' and type = 'design' and parent >= 0
r($execution->getSearchTasksTest($condition[3], $recPerPage[0], $orderBy[1])) && p() && e('6:name:任务6;');                                                       // 测试通过sql语句获取3个任务 id 正序 module like '%2%' and type = 'design' and parent >= 0
r($execution->getSearchTasksTest($condition[3], $recPerPage[1], $orderBy[0])) && p() && e('6:name:任务6;');                                                       // 测试通过sql语句获取5个任务 id 倒序 module like '%2%' and type = 'design' and parent >= 0
r($execution->getSearchTasksTest($condition[3], $recPerPage[1], $orderBy[1])) && p() && e('6:name:任务6;');                                                       // 测试通过sql语句获取5个任务 id 正序 module like '%2%' and type = 'design' and parent >= 0
