#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 reporeviewflowTestModel->create();
timeout=0
cid=0

- 创建评审流程
 - 属性repo @1
 - 属性name @test_flow5
 - 属性branchType @1
 - 属性desc @desc
 - 属性status @enable
- 重复创建第name条的0属性 @『name』已经有『test_flow5』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
*/
zenData('repo')->gen(10);

$reviewFlow = new stdClass();
$reviewFlow->repo             = 1;
$reviewFlow->name             = 'test_flow5';
$reviewFlow->isAllBranchTypes = true;
$reviewFlow->branchType       = 1;
$reviewFlow->desc             = 'desc';
$reviewFlow->status           = 'enable';
$reviewFlow->createdBy        = 'admin';
$reviewFlow->createdDate      = '2025-12-22 00:00:00';

$flowTest = new reporeviewflowTest();

zenData('ops_review_flow')->gen(0);
r($flowTest->createTest(1, $reviewFlow)) && p('repo,name,branchType,desc,status') && e('1,test_flow5,1,desc,enable'); // 创建评审流程
r($flowTest->createTest(1, $reviewFlow)) && p('name:0')                           && e('『name』已经有『test_flow5』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');//重复创建
