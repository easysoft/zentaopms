#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

/**

title=测试 repoModel->updateReviewFlow();
timeout=0
cid=0

- 创建评审流程
 - 属性repo @1
 - 属性name @update_flow1
 - 属性branchType @1
 - 属性desc @desc
 - 属性status @enable
- 重复创建第name条的0属性 @『name』已经有『update_flow1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
*/
zenData('repo')->gen(10);
zenData('ops_review_flow')->gen(10);

$reviewFlow = new stdClass();
$reviewFlow->repo             = 1;
$reviewFlow->name             = 'update_flow1';
$reviewFlow->isAllBranchTypes = true;
$reviewFlow->branchType       = 1;
$reviewFlow->desc             = 'desc';
$reviewFlow->status           = 'enable';
$reviewFlow->editedBy         = 'admin';
$reviewFlow->editedDate       = '2025-12-22 00:00:00';

$repoTest = new repoTest();

r($repoTest->createReviewFlowTest(1, $reviewFlow)) && p('repo,name,branchType,desc,status') && e('1,update_flow1,1,desc,enable'); // 创建评审流程
r($repoTest->createReviewFlowTest(1, $reviewFlow)) && p('name:0')                           && e('『name』已经有『update_flow1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');//重复创建
