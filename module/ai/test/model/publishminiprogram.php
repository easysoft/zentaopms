#!/usr/bin/env php
<?php

/**

title=测试 aiModel::publishMiniProgram();
timeout=0
cid=0

- 步骤1：发布新的小程序（首次发布） @rue
- 步骤2：发布已发布的小程序（版本更新） @rue
- 步骤3：取消发布小程序（设置为未发布状态） @rue
- 步骤4：测试无效的小程序ID @rue
- 步骤5：测试边界值published参数 @rue

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_miniprogram');
$table->id->range('1-3');
$table->name->range('测试小程序1,测试小程序2,测试小程序3');
$table->category->range('work,personal,creative');
$table->published->range('0,1,0');
$table->publishedDate->range('~~,2024-01-01 10:00:00,~~');
$table->deleted->range('0');
$table->createdBy->range('admin');
$table->createdDate->range('2024-01-01 09:00:00');
$table->editedBy->range('admin');
$table->editedDate->range('2024-01-01 09:00:00');
$table->desc->range('测试描述');
$table->prompt->range('测试提示');
$table->builtIn->range('0');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->publishMiniProgramTest(1, '1')) && p() && e(true); // 步骤1：发布新的小程序（首次发布）
r($aiTest->publishMiniProgramTest(2, '1')) && p() && e(true); // 步骤2：发布已发布的小程序（版本更新）
r($aiTest->publishMiniProgramTest(2, '0')) && p() && e(true); // 步骤3：取消发布小程序（设置为未发布状态）
r($aiTest->publishMiniProgramTest(999, '1')) && p() && e(true); // 步骤4：测试无效的小程序ID
r($aiTest->publishMiniProgramTest(3, '')) && p() && e(true); // 步骤5：测试边界值published参数