#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getMiniPrograms();
timeout=0
cid=15040

- 步骤1：无筛选条件获取所有小程序 @5
- 步骤2：按类别筛选personal @2
- 步骤3：筛选已发布状态active @5
- 步骤4：筛选草稿状态draft @0
- 步骤5：筛选我创建的程序createdByMe @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_miniprogram');
$table->id->range('1-5');
$table->name->range('职业发展导航,OKR目标达人,健身计划,工作汇报,市场分析报告');
$table->category->range('personal{2},life,work{2}');
$table->desc->range('AI小程序描述1,AI小程序描述2,AI小程序描述3,AI小程序描述4,AI小程序描述5');
$table->published->range('1{5}');
$table->deleted->range('0{5}');
$table->createdBy->range('system{5}');
$table->createdDate->range('`2023-01-01 10:00:00`,`2023-01-02 10:00:00`,`2023-01-03 10:00:00`,`2023-01-04 10:00:00`,`2023-01-05 10:00:00`');
$table->editedBy->range('system{5}');
$table->editedDate->range('`2023-01-01 10:00:00`,`2023-01-02 10:00:00`,`2023-01-03 10:00:00`,`2023-01-04 10:00:00`,`2023-01-05 10:00:00`');
$table->prompt->range('测试提示词1,测试提示词2,测试提示词3,测试提示词4,测试提示词5');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($aiTest->getMiniProgramsTest())) && p() && e(5); // 步骤1：无筛选条件获取所有小程序
r(count($aiTest->getMiniProgramsTest('personal'))) && p() && e(2); // 步骤2：按类别筛选personal
r(count($aiTest->getMiniProgramsTest('', 'active'))) && p() && e(5); // 步骤3：筛选已发布状态active
r(count($aiTest->getMiniProgramsTest('', 'draft'))) && p() && e(0); // 步骤4：筛选草稿状态draft
r(count($aiTest->getMiniProgramsTest('', 'createdByMe'))) && p() && e(0); // 步骤5：筛选我创建的程序createdByMe