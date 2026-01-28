#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getPromptsForUser();
timeout=0
cid=15045

- 步骤1：正常情况 - story模块有数据 @3
- 步骤2：边界值 - 不存在的模块 @0
- 步骤3：异常输入 - 空字符串 @0
- 步骤4：权限验证 - task模块有数据 @3
- 步骤5：业务规则 - bug模块有数据 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('ai_agent');
$table->id->range('1-10');
$table->name->range('prompt1{3},prompt2{3},prompt3{2},prompt4{1},prompt5{1}');
$table->module->range('story{3},task{3},bug{2},project{1},user{1}');
$table->status->range('active{8},draft{2}');
$table->deleted->range('0{9},1{1}');
$table->createdBy->range('admin{10}');
$table->createdDate->range('`2024-01-01 00:00:00`');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->getPromptsForUserTest('story')) && p() && e('3'); // 步骤1：正常情况 - story模块有数据
r($aiTest->getPromptsForUserTest('nonexistent')) && p() && e('0'); // 步骤2：边界值 - 不存在的模块
r($aiTest->getPromptsForUserTest('')) && p() && e('0'); // 步骤3：异常输入 - 空字符串
r($aiTest->getPromptsForUserTest('task')) && p() && e('3'); // 步骤4：权限验证 - task模块有数据
r($aiTest->getPromptsForUserTest('bug')) && p() && e('2'); // 步骤5：业务规则 - bug模块有数据