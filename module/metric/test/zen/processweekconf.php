#!/usr/bin/env php
<?php

/**

title=测试 metricZen::processWeekConf();
timeout=0
cid=17205

- 步骤1：单个工作日 @星期一
- 步骤2：多个工作日 @星期一、星期二、星期三
- 步骤3：周末日期 @星期日、星期六
- 步骤4：完整工作周 @星期一、星期二、星期三、星期四、星期五
- 步骤5：重复日期 @星期一、星期一、星期二

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例
$metricZenTest = new metricZenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($metricZenTest->processWeekConfZenTest('1')) && p() && e('星期一'); // 步骤1：单个工作日
r($metricZenTest->processWeekConfZenTest('1,2,3')) && p() && e('星期一、星期二、星期三'); // 步骤2：多个工作日
r($metricZenTest->processWeekConfZenTest('0,6')) && p() && e('星期日、星期六'); // 步骤3：周末日期
r($metricZenTest->processWeekConfZenTest('1,2,3,4,5')) && p() && e('星期一、星期二、星期三、星期四、星期五'); // 步骤4：完整工作周
r($metricZenTest->processWeekConfZenTest('1,1,2')) && p() && e('星期一、星期一、星期二'); // 步骤5：重复日期