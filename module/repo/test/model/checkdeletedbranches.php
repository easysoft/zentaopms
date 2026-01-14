#!/usr/bin/env php
<?php

/**

title=测试 repoModel::checkDeletedBranches();
timeout=0
cid=18031

- 步骤1：正常删除已删除的分支数据
 - 属性repoHistoryCount @4
 - 属性repoBranchCount @4
 - 属性repoFilesCount @4
- 步骤2：测试空分支列表输入
 - 属性repoHistoryCount @4
 - 属性repoBranchCount @4
 - 属性repoFilesCount @4
- 步骤3：测试master分支不被删除（master存在但不在最新列表中）
 - 属性repoHistoryCount @4
 - 属性repoBranchCount @4
 - 属性repoFilesCount @4
- 步骤4：测试多个分支删除场景
 - 属性repoHistoryCount @2
 - 属性repoBranchCount @2
 - 属性repoFilesCount @2
- 步骤5：测试不存在代码库ID
 - 属性repoHistoryCount @2
 - 属性repoBranchCount @2
 - 属性repoFilesCount @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据 - 使用简单的数据生成方式
zenData('repo')->loadYaml('repo')->gen(5);

// 手动设置分支数据以确保测试准确性
$repoBranch = zenData('repobranch');
$repoBranch->repo->range('1{6}, 2{3}');
$repoBranch->revision->range('1,2,3,4,5,6,7,8,9');
$repoBranch->branch->range('master,develop,feature-branch,hotfix-branch,deleted-branch,tag-branch,main,dev,test');
$repoBranch->gen(9);

$repoHistory = zenData('repohistory');
$repoHistory->repo->range('1{6}, 2{3}');
$repoHistory->revision->range('1,2,3,4,5,6,7,8,9');
$repoHistory->gen(9);

$repoFiles = zenData('repofiles');
$repoFiles->repo->range('1{6}, 2{3}');
$repoFiles->revision->range('1,2,3,4,5,6,7,8,9');
$repoFiles->gen(9);

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

r($repoTest->checkDeletedBranchesTest(1, array('main' => 'main'))) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('4,4,4'); // 步骤1：正常删除已删除的分支数据
r($repoTest->checkDeletedBranchesTest(1, array())) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('4,4,4'); // 步骤2：测试空分支列表输入
r($repoTest->checkDeletedBranchesTest(1, array('develop' => 'develop'))) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('4,4,4'); // 步骤3：测试master分支不被删除（master存在但不在最新列表中）
r($repoTest->checkDeletedBranchesTest(2, array('main' => 'main'))) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('2,2,2'); // 步骤4：测试多个分支删除场景
r($repoTest->checkDeletedBranchesTest(999, array('master' => 'master'))) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('2,2,2'); // 步骤5：测试不存在代码库ID