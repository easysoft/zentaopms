#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$bug = zenData('bug');
$bug->id->range('1-100');
$bug->product->range('1,2');
$bug->branch->range('0,1');
$bug->project->range('0,2,2,0');
$bug->execution->range('0,3,3,0');
$bug->module->range('1,0,0,1');
$bug->status->range("resolved,active,closed");
$bug->title->prefix("BUG")->range('1-10');
$bug->assignedTo->range('admin');
$bug->openedBy->range('admin');
$bug->resolvedBy->range('admin');
$bug->confirmed->range('0,1,1,0');
$bug->resolution->range('postponed,fixed');
$bug->gen(50);

zendata('story')->loadYaml('storyconfirm')->gen(50);

zendata('user')->gen(1);

su('admin');

/**

title=bugModel->getNeedConfirmList();
timeout=0
cid=15419

- 获取产品 1 项目 0 执行 空 分支 all 模块 空 的待确认bug @4
- 获取产品 1 项目 2 执行 空 分支 all 模块 空 的待确认bug @0
- 获取产品 1 项目 0 执行 3  分支 all 模块 空 的待确认bug @3
- 获取产品 1 项目 0 执行 空 分支 1   模块 空 的待确认bug @0
- 获取产品 1 项目 0 执行 空 分支 all 模块 1  的待确认bug @4
- 获取产品 1 项目 2 执行 3  分支 1   模块 1  的待确认bug @0
- 获取产品 2 项目 0 执行 空 分支 all 模块 空 的待确认bug @3
- 获取产品 2 项目 2 执行 空 分支 all 模块 空 的待确认bug @0
- 获取产品 2 项目 0 执行 3  分支 all 模块 空 的待确认bug @3
- 获取产品 2 项目 0 执行 空 分支 1   模块 空 的待确认bug @3
- 获取产品 2 项目 0 执行 空 分支 all 模块 1  的待确认bug @3
- 获取产品 2 项目 2 执行 3  分支 1   模块 1  的待确认bug @0
- 获取产品 1 项目 0 执行 空 分支 0 模块 空 的待确认bug @13,9,5,1

- 获取产品 1 项目 2 执行 空 分支 0 模块 空 的待确认bug @0
- 获取产品 1 项目 0 执行 3  分支 0 模块 空 的待确认bug @11,7,3

- 获取产品 1 项目 0 执行 空 分支 1 模块 空 的待确认bug @0
- 获取产品 1 项目 0 执行 空 分支 0 模块 1  的待确认bug @13,9,5,1

- 获取产品 1 项目 2 执行 3  分支 1 模块 1  的待确认bug @0
- 获取产品 2 项目 0 执行 空 分支 0 模块 空 的待确认bug @12,8,4

- 获取产品 2 项目 2 执行 空 分支 0 模块 空 的待确认bug @0
- 获取产品 2 项目 0 执行 3  分支 0 模块 空 的待确认bug @10,6,2

- 获取产品 2 项目 0 执行 空 分支 1 模块 空 的待确认bug @12,8,4

- 获取产品 2 项目 0 执行 空 分支 0 模块 1  的待确认bug @12,8,4

- 获取产品 2 项目 2 执行 3  分支 1 模块 1  的待确认bug @0

*/
$productIdList   = array(array(1), array(2), array(1000001));
$projectID       = array(0, 2, 1000001);
$executionIdList = array(array(), array(3), array(1000001));
$branch          = array('all', 1, 1000001);
$moduleID        = array(array(), array(1), array(1000001));

global $tester;
$bug = $tester->loadModel('bug');
$result1  = $bug->getNeedConfirmList($productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0], 'id_desc');
$result2  = $bug->getNeedConfirmList($productIdList[0], $projectID[1], $executionIdList[0], $branch[0], $moduleID[0], 'id_desc');
$result3  = $bug->getNeedConfirmList($productIdList[0], $projectID[0], $executionIdList[1], $branch[0], $moduleID[0], 'id_desc');
$result4  = $bug->getNeedConfirmList($productIdList[0], $projectID[0], $executionIdList[0], $branch[1], $moduleID[0], 'id_desc');
$result5  = $bug->getNeedConfirmList($productIdList[0], $projectID[0], $executionIdList[0], $branch[0], $moduleID[1], 'id_desc');
$result6  = $bug->getNeedConfirmList($productIdList[0], $projectID[1], $executionIdList[1], $branch[1], $moduleID[1], 'id_desc');
$result7  = $bug->getNeedConfirmList($productIdList[1], $projectID[0], $executionIdList[0], $branch[0], $moduleID[0], 'id_desc');
$result8  = $bug->getNeedConfirmList($productIdList[1], $projectID[1], $executionIdList[0], $branch[0], $moduleID[0], 'id_desc');
$result9  = $bug->getNeedConfirmList($productIdList[1], $projectID[0], $executionIdList[1], $branch[0], $moduleID[0], 'id_desc');
$result10 = $bug->getNeedConfirmList($productIdList[1], $projectID[0], $executionIdList[0], $branch[1], $moduleID[0], 'id_desc');
$result11 = $bug->getNeedConfirmList($productIdList[1], $projectID[0], $executionIdList[0], $branch[0], $moduleID[1], 'id_desc');
$result12 = $bug->getNeedConfirmList($productIdList[1], $projectID[1], $executionIdList[1], $branch[1], $moduleID[1], 'id_desc');

r(count($result1))  && p() && e('4'); // 获取产品 1 项目 0 执行 空 分支 all 模块 空 的待确认bug
r(count($result2))  && p() && e('0'); // 获取产品 1 项目 2 执行 空 分支 all 模块 空 的待确认bug
r(count($result3))  && p() && e('3'); // 获取产品 1 项目 0 执行 3  分支 all 模块 空 的待确认bug
r(count($result4))  && p() && e('0'); // 获取产品 1 项目 0 执行 空 分支 1   模块 空 的待确认bug
r(count($result5))  && p() && e('4'); // 获取产品 1 项目 0 执行 空 分支 all 模块 1  的待确认bug
r(count($result6))  && p() && e('0'); // 获取产品 1 项目 2 执行 3  分支 1   模块 1  的待确认bug
r(count($result7))  && p() && e('3'); // 获取产品 2 项目 0 执行 空 分支 all 模块 空 的待确认bug
r(count($result8))  && p() && e('0'); // 获取产品 2 项目 2 执行 空 分支 all 模块 空 的待确认bug
r(count($result9))  && p() && e('3'); // 获取产品 2 项目 0 执行 3  分支 all 模块 空 的待确认bug
r(count($result10)) && p() && e('3'); // 获取产品 2 项目 0 执行 空 分支 1   模块 空 的待确认bug
r(count($result11)) && p() && e('3'); // 获取产品 2 项目 0 执行 空 分支 all 模块 1  的待确认bug
r(count($result12)) && p() && e('0'); // 获取产品 2 项目 2 执行 3  分支 1   模块 1  的待确认bug

r(implode(',', array_keys($result1)))  && p() && e('13,9,5,1'); // 获取产品 1 项目 0 执行 空 分支 0 模块 空 的待确认bug
r(implode(',', array_keys($result2)))  && p() && e('0');        // 获取产品 1 项目 2 执行 空 分支 0 模块 空 的待确认bug
r(implode(',', array_keys($result3)))  && p() && e('11,7,3');   // 获取产品 1 项目 0 执行 3  分支 0 模块 空 的待确认bug
r(implode(',', array_keys($result4)))  && p() && e('0');        // 获取产品 1 项目 0 执行 空 分支 1 模块 空 的待确认bug
r(implode(',', array_keys($result5)))  && p() && e('13,9,5,1'); // 获取产品 1 项目 0 执行 空 分支 0 模块 1  的待确认bug
r(implode(',', array_keys($result6)))  && p() && e('0');        // 获取产品 1 项目 2 执行 3  分支 1 模块 1  的待确认bug
r(implode(',', array_keys($result7)))  && p() && e('12,8,4');   // 获取产品 2 项目 0 执行 空 分支 0 模块 空 的待确认bug
r(implode(',', array_keys($result8)))  && p() && e('0');        // 获取产品 2 项目 2 执行 空 分支 0 模块 空 的待确认bug
r(implode(',', array_keys($result9)))  && p() && e('10,6,2');   // 获取产品 2 项目 0 执行 3  分支 0 模块 空 的待确认bug
r(implode(',', array_keys($result10))) && p() && e('12,8,4');   // 获取产品 2 项目 0 执行 空 分支 1 模块 空 的待确认bug
r(implode(',', array_keys($result11))) && p() && e('12,8,4');   // 获取产品 2 项目 0 执行 空 分支 0 模块 1  的待确认bug
r(implode(',', array_keys($result12))) && p() && e('0');        // 获取产品 2 项目 2 执行 3  分支 1 模块 1  的待确认bug
