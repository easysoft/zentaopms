#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getMiniProgramsByID();
timeout=0
cid=15041

- 步骤1：多个有效ID不排序，期望返回3个记录 @3
- 步骤2：多个有效ID需要排序，期望返回3个记录 @3
- 步骤3：单个有效ID，期望返回1个记录 @1
- 步骤4：空数组，期望返回0个记录 @0
- 步骤5：不存在的ID，期望返回0个记录 @0
- 步骤6：验证排序后第一个记录ID为3第0条的id属性 @3
- 步骤7：验证排序后第二个记录ID为1第1条的id属性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_miniprogram');
$table->id->range('1-5');
$table->name->range('职业发展导航,工作汇报,市场分析报告,项目管理助手,代码审查工具');
$table->category->range('personal,work,life,project,development');
$table->desc->range('这是描述1,这是描述2,这是描述3,这是描述4,这是描述5');
$table->model->range('1,2,3,1,2');
$table->icon->range('technologist-6,writinghand-7,chart-7,project-7,code-7');
$table->createdBy->range('admin');
$table->createdDate->range('`2024-01-01 00:00:00`');
$table->editedBy->range('admin');
$table->editedDate->range('`2024-01-02 00:00:00`');
$table->published->range('1,0,1,1,0');
$table->publishedDate->range('`2024-01-01 00:00:00`');
$table->deleted->range('0');
$table->prompt->range('提示内容1,提示内容2,提示内容3,提示内容4,提示内容5');
$table->builtIn->range('1,0,1,0,0');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($aiTest->getMiniProgramsByIDTest(array(1, 3, 5), false))) && p() && e(3); // 步骤1：多个有效ID不排序，期望返回3个记录
r(count($aiTest->getMiniProgramsByIDTest(array(3, 1, 5), true))) && p() && e(3); // 步骤2：多个有效ID需要排序，期望返回3个记录
r(count($aiTest->getMiniProgramsByIDTest(array(2), false))) && p() && e(1); // 步骤3：单个有效ID，期望返回1个记录
r(count($aiTest->getMiniProgramsByIDTest(array(), false))) && p() && e(0); // 步骤4：空数组，期望返回0个记录
r(count($aiTest->getMiniProgramsByIDTest(array(999, 1000), false))) && p() && e(0); // 步骤5：不存在的ID，期望返回0个记录
r($aiTest->getMiniProgramsByIDTest(array(3, 1, 5), true)) && p('0:id') && e('3'); // 步骤6：验证排序后第一个记录ID为3
r($aiTest->getMiniProgramsByIDTest(array(3, 1, 5), true)) && p('1:id') && e('1'); // 步骤7：验证排序后第二个记录ID为1