#!/usr/bin/env php
<?php

/**

title=测试 jobZen::checkRepoEmpty();
timeout=0
cid=0

- 步骤1：存在正常devops版本库时不跳转 @success
- 步骤2：无版本库时跳转到创建页面 @redirect_to_create
- 步骤3：只有已删除版本库时跳转 @redirect_to_create
- 步骤4：存在多个版本库时不跳转 @success
- 步骤5：混合正常和已删除版本库时不跳转 @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('repo');
$table->id->range('1-5');
$table->name->range('devops-repo1{1},devops-repo2{1},test-repo{1},main-repo{2}');
$table->SCM->range('Git{4},Subversion{1}');
$table->deleted->range('0{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$jobTest = new jobTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($jobTest->checkRepoEmptyTest()) && p() && e('success'); // 步骤1：存在正常devops版本库时不跳转

// 清空版本库数据测试无版本库情况
zenData('repo')->gen(0);
r($jobTest->checkRepoEmptyTest()) && p() && e('redirect_to_create'); // 步骤2：无版本库时跳转到创建页面

// 重新生成数据，只包含已删除的版本库
$table = zenData('repo');
$table->id->range('1-3');
$table->name->range('deleted-repo1{1},deleted-repo2{1},deleted-repo3{1}');
$table->SCM->range('Git{3}');
$table->deleted->range('1{3}');
$table->gen(3);
r($jobTest->checkRepoEmptyTest()) && p() && e('redirect_to_create'); // 步骤3：只有已删除版本库时跳转

// 重新生成多个正常版本库
zenData('repo')->gen(0);
$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('devops-repo{3},test-repo{3},main-repo{4}');
$table->SCM->range('Git{8},Subversion{2}');
$table->deleted->range('0{10}');
$table->gen(10);
r($jobTest->checkRepoEmptyTest()) && p() && e('success'); // 步骤4：存在多个版本库时不跳转

// 测试混合正常和已删除版本库的情况
zenData('repo')->gen(0);
$table = zenData('repo');
$table->id->range('1-8');
$table->name->range('devops-active{2},devops-deleted{2},test-active{2},test-deleted{2}');
$table->SCM->range('Git{6},Subversion{2}');
$table->deleted->range('0{4},1{4}');
$table->gen(8);
r($jobTest->checkRepoEmptyTest()) && p() && e('success'); // 步骤5：混合正常和已删除版本库时不跳转