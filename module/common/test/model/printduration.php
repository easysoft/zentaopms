#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printDuration();
timeout=0
cid=0

- 步骤1：标准完整时间格式 @1年2月3天4小时5分6秒
- 步骤2：小时分钟秒格式（包含y格式） @1小时1分1秒
- 步骤3：仅显示天数格式（包含y格式） @1天
- 步骤4：零秒输入测试 @0
- 步骤5：大数值多年测试 @2年

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 3. 强制要求：必须包含至少5个测试步骤
r($commonTest->printDurationTest(36993906, 'y-m-d-h-i-s')) && p() && e('1年2月3天4小时5分6秒'); // 步骤1：标准完整时间格式
r($commonTest->printDurationTest(3661, 'y-h-i-s')) && p() && e('1小时1分1秒');                 // 步骤2：小时分钟秒格式（包含y格式）
r($commonTest->printDurationTest(86400, 'y-d')) && p() && e('1天');                           // 步骤3：仅显示天数格式（包含y格式）
r($commonTest->printDurationTest(0, 'y-m-d-h-i-s')) && p() && e('0');                         // 步骤4：零秒输入测试
r($commonTest->printDurationTest(63072000, 'y-m')) && p() && e('2年');                        // 步骤5：大数值多年测试