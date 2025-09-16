#!/usr/bin/env php
<?php

/**

title=测试 jobZen::checkRepoEmpty();
timeout=0
cid=0

- 步骤1：存在devops版本库时不触发跳转 @no_redirect
- 步骤2：不存在任何版本库时触发跳转 @redirect_triggered
- 步骤3：只存在已删除的版本库时触发跳转 @redirect_triggered
- 步骤4：存在多个devops版本库时不触发跳转 @no_redirect
- 步骤5：混合正常和已删除版本库时不触发跳转 @no_redirect

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('repo');
$table->id->range('1-5');
$table->name->range('devops-repo-1,devops-repo-2,test-repo-1,main-repo-1,main-repo-2');
$table->SCM->range('Git{4},Subversion{1}');
$table->deleted->range('0{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$jobTest = new jobTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($jobTest->checkRepoEmptyTest()) && p() && e('no_redirect'); // 步骤1：存在devops版本库时不触发跳转

// 清空版本库数据测试无版本库情况
zenData('repo')->gen(0);
r($jobTest->checkRepoEmptyTest()) && p() && e('redirect_triggered'); // 步骤2：不存在任何版本库时触发跳转

// 重新生成数据，只包含已删除的版本库
$table = zenData('repo');
$table->id->range('1-3');
$table->name->range('deleted-repo-1,deleted-repo-2,deleted-repo-3');
$table->SCM->range('Git{3}');
$table->deleted->range('1{3}');
$table->gen(3);
r($jobTest->checkRepoEmptyTest()) && p() && e('redirect_triggered'); // 步骤3：只存在已删除的版本库时触发跳转

// 重新生成多个正常的devops版本库
zenData('repo')->gen(0);
$table = zenData('repo');
$table->id->range('1-8');
$table->name->range('devops-main{2},devops-test{2},other-repo{2},temp-repo{2}');
$table->SCM->range('Git{6},Subversion{2}');
$table->deleted->range('0{8}');
$table->gen(8);
r($jobTest->checkRepoEmptyTest()) && p() && e('no_redirect'); // 步骤4：存在多个devops版本库时不触发跳转

// 测试混合正常和已删除版本库的情况
zenData('repo')->gen(0);
$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('active-devops-1,active-devops-2,active-test-1,deleted-devops-1,deleted-devops-2,deleted-test-1,normal-repo-1,normal-repo-2,normal-repo-3,normal-repo-4');
$table->SCM->range('Git{8},Subversion{2}');
$table->deleted->range('0{6},1{4}');
$table->gen(10);
r($jobTest->checkRepoEmptyTest()) && p() && e('no_redirect'); // 步骤5：混合正常和已删除版本库时不触发跳转