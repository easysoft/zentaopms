#!/usr/bin/env php
<?php

/**

title=测试 jobZen::checkRepoEmpty();
timeout=0
cid=0

- 步骤1：存在版本库时不跳转 @success
- 步骤2：无版本库时应跳转到创建页面 @redirect_to_create
- 步骤3：存在已删除版本库时应跳转 @redirect_to_create
- 步骤4：存在多个版本库时不跳转 @success
- 步骤5：版本库为devops类型时的处理 @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('repo');
$table->id->range('1-5');
$table->name->range('devops-repo{2},test-repo{1},main-repo{2}');
$table->SCM->range('Git{4},Subversion{1}');
$table->deleted->range('0{4},1{1}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$jobTest = new jobTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($jobTest->checkRepoEmptyTest()) && p() && e('success'); // 步骤1：存在版本库时不跳转

// 清空版本库数据测试无版本库情况
zenData('repo')->gen(0);
r($jobTest->checkRepoEmptyTest()) && p() && e('redirect_to_create'); // 步骤2：无版本库时应跳转到创建页面

// 重新生成数据，只包含已删除的版本库
$table = zenData('repo');
$table->id->range('1-2');
$table->name->range('deleted-repo{2}');
$table->deleted->range('1{2}');
$table->gen(2);
r($jobTest->checkRepoEmptyTest()) && p() && e('redirect_to_create'); // 步骤3：存在已删除版本库时应跳转

// 重新生成多个正常版本库，确保有devops类型
zenData('repo')->gen(0); // 先清空
$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('devops-repo1{1},devops-repo2{1},test-repo{3},main-repo{5}');
$table->SCM->range('Git{8},Subversion{2}');  
$table->deleted->range('0{10}');
$table->gen(10);
r($jobTest->checkRepoEmptyTest()) && p() && e('success'); // 步骤4：存在多个版本库时不跳转

// 专门测试只有devops类型版本库的情况  
zenData('repo')->gen(0); // 先清空
$table = zenData('repo');
$table->id->range('1-5');
$table->name->range('devops-main{2},devops-test{2},devops-prod{1}');
$table->SCM->range('Git{5}');
$table->deleted->range('0{5}');
$table->gen(5);
r($jobTest->checkRepoEmptyTest()) && p() && e('success'); // 步骤5：版本库为devops类型时的处理