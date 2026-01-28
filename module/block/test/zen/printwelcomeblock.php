#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printWelcomeBlock();
timeout=0
cid=15315

- 步骤1：验证todaySummary是非空字符串 @1
- 步骤2：验证welcomeType是时间段标识 @1
- 步骤3：验证usageDays是非空字符串 @1
- 步骤4：验证assignToMe是数组类型 @1
- 步骤5：验证reviewByMe是数组类型 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('user')->loadYaml('user_printwelcomeblock', false, 2)->gen(10);
zendata('company')->loadYaml('company_printwelcomeblock', false, 2)->gen(1);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
$result = $blockTest->printWelcomeBlockTest();
r(is_string($result->todaySummary) && strlen($result->todaySummary) > 0) && p() && e('1'); // 步骤1：验证todaySummary是非空字符串
r(in_array($result->welcomeType, array('06:00', '11:30', '13:30', '19:00'))) && p() && e('1'); // 步骤2：验证welcomeType是时间段标识
r(is_string($result->usageDays) && strlen($result->usageDays) > 0) && p() && e('1'); // 步骤3：验证usageDays是非空字符串
r(is_array($result->assignToMe)) && p() && e('1'); // 步骤4：验证assignToMe是数组类型
r(is_array($result->reviewByMe)) && p() && e('1'); // 步骤5：验证reviewByMe是数组类型