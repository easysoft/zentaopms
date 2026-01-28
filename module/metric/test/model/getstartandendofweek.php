#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getStartAndEndOfWeek();
timeout=0
cid=0

- 步骤1：正常情况，2024年第1周带时间格式
 -  @2024-01-01 00:00:00
 - 属性1 @2024-01-07 23:59:59
- 步骤2：边界值，2024年第52周日期格式
 -  @2024-12-23
 - 属性1 @2024-12-29
- 步骤3：年初测试，2023年第1周
 -  @2022-12-26 00:00:00
 - 属性1 @2023-01-01 23:59:59
- 步骤4：第53周边界测试
 -  @2020-12-28
 - 属性1 @2021-01-03
- 步骤5：默认参数测试，应返回datetime格式
 -  @2024-03-04 00:00:00
 - 属性1 @2024-03-10 23:59:59

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricModelTest();

// 4. 必须包含至少5个测试步骤
r($metricTest->getStartAndEndOfWeekTest(2024, 1, 'datetime')) && p('0,1') && e('2024-01-01 00:00:00,2024-01-07 23:59:59'); // 步骤1：正常情况，2024年第1周带时间格式
r($metricTest->getStartAndEndOfWeekTest(2024, 52, 'date')) && p('0,1') && e('2024-12-23,2024-12-29'); // 步骤2：边界值，2024年第52周日期格式
r($metricTest->getStartAndEndOfWeekTest(2023, 1, 'datetime')) && p('0,1') && e('2022-12-26 00:00:00,2023-01-01 23:59:59'); // 步骤3：年初测试，2023年第1周
r($metricTest->getStartAndEndOfWeekTest(2020, 53, 'date')) && p('0,1') && e('2020-12-28,2021-01-03'); // 步骤4：第53周边界测试
r($metricTest->getStartAndEndOfWeekTest(2024, 10)) && p('0,1') && e('2024-03-04 00:00:00,2024-03-10 23:59:59'); // 步骤5：默认参数测试，应返回datetime格式