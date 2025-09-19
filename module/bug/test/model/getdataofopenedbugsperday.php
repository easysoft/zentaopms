#!/usr/bin/env php
<?php

/**

title=测试 bugModel::getDataOfOpenedBugsPerDay();
timeout=0
cid=0

- 步骤1：验证第一天统计的bug数量第0条的value属性 @1
- 步骤2：验证第一天的日期格式化输出第0条的name属性 @2025-08-19
- 步骤3：验证第二天的bug数量第1条的value属性 @1
- 步骤4：验证最后一天的日期第9条的name属性 @2025-08-28
- 步骤5：验证中间某天的完整数据
 - 第5条的name属性 @2025-08-24
 - 第5条的value属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 1. 准备测试数据
zenData('bug')->gen(10);

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$bugTest = new bugTest();

// 4. 执行测试步骤（强制要求：至少5个测试步骤）
r($bugTest->getDataOfOpenedBugsPerDayTest()) && p('0:value') && e('1'); // 步骤1：验证第一天统计的bug数量
r($bugTest->getDataOfOpenedBugsPerDayTest()) && p('0:name') && e('2025-08-19'); // 步骤2：验证第一天的日期格式化输出
r($bugTest->getDataOfOpenedBugsPerDayTest()) && p('1:value') && e('1'); // 步骤3：验证第二天的bug数量
r($bugTest->getDataOfOpenedBugsPerDayTest()) && p('9:name') && e('2025-08-28'); // 步骤4：验证最后一天的日期
r($bugTest->getDataOfOpenedBugsPerDayTest()) && p('5:name,value') && e('2025-08-24,1'); // 步骤5：验证中间某天的完整数据