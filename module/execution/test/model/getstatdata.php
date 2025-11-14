#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
/**

title=测试 executionModel->getStatData();
timeout=0
cid=16340

- 测试默认值 @3
- 测试传入空值 @3
- 测试projectID为10的所有未关闭执行 @0
- 测试projectID为10的所有执行 @0
- 测试projectID为10的未开始执行 @0
- 测试projectID为10的进行中执行 @0
- 测试projectID为10的已挂起执行 @0
- 测试projectID为10的已关闭执行 @0
- 测试projectID为10的我参与执行 @0
- 测试projectID为10的搜索出来的执行 @0
- 测试projectID为10的评审执行 @0
- 测试projectID为10, productID为1, branchID为0的所有执行 @0
- 测试projectID为10, productID为1, branchID为0的所有执行和任务 @0
- 测试projectID为10, productID为1, branchID为0的非父阶段 @0
- 测试projectID为2的所有未关闭执行 @1
- 测试projectID为2的所有执行 @1
- 测试projectID为2的未开始执行 @1
- 测试projectID为2的进行中执行 @1
- 测试projectID为2的已挂起执行 @1
- 测试projectID为2的已关闭执行 @1
- 测试projectID为2的我参与执行 @1
- 测试projectID为2的搜索出来的执行 @1
- 测试projectID为2的评审执行 @1
- 测试projectID为2, productID为1, branchID为0的所有执行 @1
- 测试projectID为2, productID为1, branchID为0的所有执行和任务 @1
- 测试projectID为2, productID为1, branchID为0的非父阶段 @1
- 测试默认值 @3
- 测试传入空值 @3
- 测试projectID为10的所有未关闭执行 @0
- 测试projectID为10的所有执行 @0
- 测试projectID为10的未开始执行 @0
- 测试projectID为10的进行中执行 @0
- 测试projectID为10的已挂起执行 @0
- 测试projectID为10的已关闭执行 @0
- 测试projectID为10的我参与执行 @0
- 测试projectID为10的搜索出来的执行 @0
- 测试projectID为10的评审执行 @0
- 测试projectID为10, productID为1, branchID为0的所有执行 @0
- 测试projectID为10, productID为1, branchID为0的所有执行和任务 @0
- 测试projectID为10, productID为1, branchID为0的非父阶段 @0
- 测试projectID为2的所有未关闭执行 @1
- 测试projectID为2的所有执行 @1
- 测试projectID为2的未开始执行 @1
- 测试projectID为2的进行中执行 @1
- 测试projectID为2的已挂起执行 @1
- 测试projectID为2的已关闭执行 @1
- 测试projectID为2的我参与执行 @1
- 测试projectID为2的搜索出来的执行 @1
- 测试projectID为2的评审执行 @1
- 测试projectID为2, productID为1, branchID为0的所有执行 @1
- 测试projectID为2, productID为1, branchID为0的所有执行和任务 @1
- 测试projectID为2, productID为1, branchID为0的非父阶段 @1

*/

$execution = zenData('project')->loadYaml('execution');
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

$task = zenData('task')->loadYaml('task');
$task->id->range('1-10');
$task->name->range('1-10')->prefix('任务');
$task->execution->range('3-5');
$task->type->range('test,devel');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->consumed->range('1-10');
$task->gen(10);

$product = zenData('product')->loadYaml('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$projectproduct = zenData('projectproduct')->loadYaml('projectproduct');
$projectproduct->project->range('3-5');
$projectproduct->product->range('1');
$projectproduct->plan->range('1-3');
$projectproduct->gen(3);

$team = zenData('team')->loadYaml('team');
$team->root->range('3-5');
$team->account->range('1-5')->prefix('user');
$team->role->range('研发{3},测试{2}');
$team->type->range('execution');
$team->join->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$team->gen(5);

zenData('user')->gen(5);
su('admin');

$projectIdList  = array(0, 10, 2);
$browseTypeList = array('all', 'wait', 'doing', 'suspended', 'closed', 'involved', 'bySearch', 'review');
$productID      = 1;
$branchID       = 0;
$withTasksList  = array(false, true);
$param          = 'skipParent';

$execution = new executionTest();

r($execution->getStatDataTest())                                                                                        && p() && e('3'); // 测试默认值
r($execution->getStatDataTest($projectIdList[0]))                                                                       && p() && e('3'); // 测试传入空值
r($execution->getStatDataTest($projectIdList[1]))                                                                       && p() && e('0'); // 测试projectID为10的所有未关闭执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[0]))                                                   && p() && e('0'); // 测试projectID为10的所有执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[1]))                                                   && p() && e('0'); // 测试projectID为10的未开始执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[2]))                                                   && p() && e('0'); // 测试projectID为10的进行中执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[3]))                                                   && p() && e('0'); // 测试projectID为10的已挂起执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[4]))                                                   && p() && e('0'); // 测试projectID为10的已关闭执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[5]))                                                   && p() && e('0'); // 测试projectID为10的我参与执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[6]))                                                   && p() && e('0'); // 测试projectID为10的搜索出来的执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[7]))                                                   && p() && e('0'); // 测试projectID为10的评审执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[0], $productID, $branchID))                            && p() && e('0'); // 测试projectID为10, productID为1, branchID为0的所有执行
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[0], $productID, $branchID), $withTasksList[1])         && p() && e('0'); // 测试projectID为10, productID为1, branchID为0的所有执行和任务
r($execution->getStatDataTest($projectIdList[1], $browseTypeList[0], $productID, $branchID), $withTasksList[0], $param) && p() && e('0'); // 测试projectID为10, productID为1, branchID为0的非父阶段
r($execution->getStatDataTest($projectIdList[2]))                                                                       && p() && e('1'); // 测试projectID为2的所有未关闭执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[0]))                                                   && p() && e('1'); // 测试projectID为2的所有执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[1]))                                                   && p() && e('1'); // 测试projectID为2的未开始执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[2]))                                                   && p() && e('1'); // 测试projectID为2的进行中执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[3]))                                                   && p() && e('1'); // 测试projectID为2的已挂起执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[4]))                                                   && p() && e('1'); // 测试projectID为2的已关闭执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[5]))                                                   && p() && e('1'); // 测试projectID为2的我参与执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[6]))                                                   && p() && e('1'); // 测试projectID为2的搜索出来的执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[7]))                                                   && p() && e('1'); // 测试projectID为2的评审执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[0], $productID, $branchID))                            && p() && e('1'); // 测试projectID为2, productID为1, branchID为0的所有执行
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[0], $productID, $branchID), $withTasksList[1])         && p() && e('1'); // 测试projectID为2, productID为1, branchID为0的所有执行和任务
r($execution->getStatDataTest($projectIdList[2], $browseTypeList[0], $productID, $branchID), $withTasksList[0], $param) && p() && e('1'); // 测试projectID为2, productID为1, branchID为0的非父阶段
