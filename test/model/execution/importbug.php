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
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$task = zdTable('task');
$task->id->range('1-10');
$task->name->range('1-10')->prefix('任务');
$task->execution->range('3,4,5');
$task->status->range('wait,doing');
$task->estimate->range('6');
$task->left->range('3');
$task->consumed->range('3');
$task->gen(10);

$bug = zdTable('bug');
$bug->id->range('1-3,273');
$bug->title->range('1-4')->prefix('Bug');
$bug->project->range('1,2,1');
$bug->execution->range('3,4,5');
$bug->task->range('1-10');
$bug->status->range('wait,doing');
$bug->gen(4);

su('admin');

/**

title=测试executionModel->importBugTest();
cid=1
pid=1

预计输入错误 >> 最初预计"必须为数字
Bug转任务统计 >> 4

*/

$executionIDList = array('3', '4', '5');
$import          = array('273' => '273', '3' => '3', '2' => '2', '1' => '1');
$id              = array('273' => '273', '3' => '3', '2' => '2', '1' => '1');
$pri             = array('273' => '1', '3' => '1', '2' => '2', '1' => '2');
$errorestimate   = array('273' => '2020-03-01', '3' => '2020-03-02', '2' => '2020-03-02', '1' => '2020-03-03');
$estStarted      = array('273' => '2020-03-10', '3' => '2020-03-12', '2' => '2020-03-12', '1' => '2020-03-13');
$estimate        = array('273' => '7', '3' => '6', '2' => '5', '1' => '4');
$deadline        = array('273' => '2020-03-17', '3' => '2020-03-17', '2' => '2020-03-18', '1' => '2020-03-19');
$count           = array('0','1');

$errorimport = array('import' => $import, 'id' => $id, 'pri' => $pri, 'estimate' => $errorestimate, 'deadline' => $deadline, 'estStarted' => $estStarted);
$importBugs  = array('import' => $import, 'id' => $id, 'pri' => $pri, 'estimate' => $estimate, 'deadline' => $deadline, 'estStarted' => $estStarted);

$execution = new executionTest();
r($execution->importBugTest($executionIDList[0], $count[0], $errorimport)) && p('message:0') && e('最初预计"必须为数字');  // 预计输入错误
r($execution->importBugTest($executionIDList[0], $count[1], $importBugs))  && p()            && e('4');                    // Bug转任务统计
