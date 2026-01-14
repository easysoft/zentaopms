#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
/**

title=测试executionModel->importBug();
timeout=0
cid=16349

- 测试在迭代中导入Bug第273条的taskID属性 @11
- 测试在阶段中导入Bug第273条的taskID属性 @15
- 测试在看板中导入Bug第273条的taskID属性 @19
- 测试在迭代中导入Bug的数量 @4
- 测试在阶段中导入Bug的数量 @4
- 测试在看板中导入Bug的数量 @4

*/

$execution = zenData('project');
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

$task = zenData('task');
$task->id->range('1-10');
$task->name->range('1-10')->prefix('任务');
$task->execution->range('3,4,5');
$task->status->range('wait,doing');
$task->estimate->range('6');
$task->left->range('3');
$task->consumed->range('3');
$task->gen(10);

$bug = zenData('bug');
$bug->id->range('1-3,273');
$bug->title->range('1-4')->prefix('Bug');
$bug->project->range('1,2,1');
$bug->execution->range('3,4,5');
$bug->task->range('1-10');
$bug->status->range('active');
$bug->gen(4);

su('admin');

$executionIDList = array(3, 4, 5);
$bugIdList       = array(273 => 273, 3 => 3, 2 => 2, 1 => 1);
$priList         = array(273 => 1, 3 => 1, 2 => 2, 1 => 2);
$estimateList    = array(273 => 7, 3 => 6, 2 => 5, 1 => 4);
$estStartedList  = array(273 => '2020-03-10', 3 => '2020-03-12', 2 => '2020-03-12', 1 => '2020-03-13');
$deadlineList    = array(273 => '2020-03-17', 3 => '2020-03-17', 2 => '2020-03-18', 1 => '2020-03-19');

$postData = array();
foreach($bugIdList as $bugID)
{
    $postData[$bugID] = new stdclass();
    $postData[$bugID]->pri        = $priList[$bugID];
    $postData[$bugID]->estimate   = $estimateList[$bugID];
    $postData[$bugID]->estStarted = $estStartedList[$bugID];
    $postData[$bugID]->deadline   = $deadlineList[$bugID];
}


$executionModel = new executionModelTest();
r($executionModel->importBugTest($executionIDList[0], $postData))        && p('273:taskID') && e('11'); // 测试在迭代中导入Bug
r($executionModel->importBugTest($executionIDList[1], $postData))        && p('273:taskID') && e('15'); // 测试在阶段中导入Bug
r($executionModel->importBugTest($executionIDList[1], $postData))        && p('273:taskID') && e('19'); // 测试在看板中导入Bug
r(count($executionModel->importBugTest($executionIDList[0], $postData))) && p()             && e('4');  // 测试在迭代中导入Bug的数量
r(count($executionModel->importBugTest($executionIDList[1], $postData))) && p()             && e('4');  // 测试在阶段中导入Bug的数量
r(count($executionModel->importBugTest($executionIDList[1], $postData))) && p()             && e('4');  // 测试在看板中导入Bug的数量
