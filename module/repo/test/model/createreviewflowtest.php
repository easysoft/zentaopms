#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

/**

title=测试 repoModel->create();
timeout=0
cid=18035

- 客户端为空创建gitea版本库第client条的0属性 @『客户端』不能为空。
- 正常创建gitea版本库属性SCM @Gitea
- 客户端为空创建git版本库第client条的0属性 @『客户端』不能为空。
- 正常创建git版本库属性SCM @Git
- 客户端为空创建svn版本库第client条的0属性 @『客户端』不能为空。
- 正常创建svn版本库属性SCM @Subversion

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

$repoTest = new repoTest();

zenData('ops_review_flow')->gen(0);
r($repoTest->createReviewFlowTest(1, $reviewFlow)) && p('repo,name,branchType,desc,status') && e('1,test_flow5,1,desc,enable'); // 创建评审流程
r($repoTest->createReviewFlowTest(1, $reviewFlow)) && p('name:0')                           && e('『name』已经有『test_flow5』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');//重复创建
