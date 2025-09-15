#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printIssueBlock();
timeout=0
cid=0

- 步骤1：正常情况属性hasValidation @1
- 步骤2：边界值属性projectID @0
- 步骤3：异常输入属性hasValidation @0
- 步骤4：权限验证属性hasValidation @1
- 步骤5：JSON视图属性viewType @json

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('issue');
$table->id->range('1-10');
$table->project->range('1,2,3');
$table->title->range('问题{1-10}');
$table->type->range('active,resolved,closed');
$table->status->range('active{5},resolved{3},closed{2}');
$table->owner->range('admin,user1,user2');
$table->pri->range('1,2,3,4');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printIssueBlockTest('active', 1, 5, 'id_desc')) && p('hasValidation') && e(1); // 步骤1：正常情况
r($blockTest->printIssueBlockTest('active', 0, 5, 'id_desc')) && p('projectID') && e(0); // 步骤2：边界值
r($blockTest->printIssueBlockTest('active<script>', 1, 5, 'id_desc')) && p('hasValidation') && e(0); // 步骤3：异常输入
r($blockTest->printIssueBlockTest('resolved', 1, 10, 'pri_desc')) && p('hasValidation') && e(1); // 步骤4：权限验证
r($blockTest->printIssueBlockTest('active', 1, 0, 'id_desc', 'json')) && p('viewType') && e('json'); // 步骤5：JSON视图