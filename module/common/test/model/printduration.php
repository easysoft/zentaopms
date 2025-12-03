#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printDuration();
timeout=0
cid=0

- 步骤1: 测试标准时间转换(1小时1分钟1秒) @1小时1分1秒
- 步骤2: 测试边界值0秒,期望返回空字符串 @0
- 步骤3: 测试大值1年(365天) @1年
- 步骤4: 测试1天1分钟1秒(86400+60+1) @1天1分1秒
- 步骤5: 测试完整年月日时分秒 @1年1月1天1小时11分1秒
- 步骤6: 测试只有分钟的情况 @1分
- 步骤7: 测试天时分秒组合(86400+3600+60+1) @1天1小时1分1秒

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$commonTest = new commonModelTest();

// 4. 测试步骤
r($commonTest->printDurationTest(3661)) && p() && e('1小时1分1秒'); // 步骤1: 测试标准时间转换(1小时1分钟1秒)
r($commonTest->printDurationTest(0)) && p() && e('0'); // 步骤2: 测试边界值0秒,期望返回空字符串
r($commonTest->printDurationTest(31536000)) && p() && e('1年'); // 步骤3: 测试大值1年(365天)
r($commonTest->printDurationTest(86461)) && p() && e('1天1分1秒'); // 步骤4: 测试1天1分钟1秒(86400+60+1)
r($commonTest->printDurationTest(34218661)) && p() && e('1年1月1天1小时11分1秒'); // 步骤5: 测试完整年月日时分秒
r($commonTest->printDurationTest(60)) && p() && e('1分'); // 步骤6: 测试只有分钟的情况
r($commonTest->printDurationTest(90061)) && p() && e('1天1小时1分1秒'); // 步骤7: 测试天时分秒组合(86400+3600+60+1)