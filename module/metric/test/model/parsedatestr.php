#!/usr/bin/env php
<?php

/**

title=测试 metricModel::parseDateStr();
timeout=0
cid=17145

- 步骤1：默认all类型测试，检查年份第year条的year属性 @2024
- 步骤2：年份类型测试属性year @2024
- 步骤3：月份类型测试
 - 属性year @2024
 - 属性month @06
- 步骤4：日期类型测试
 - 属性year @2024
 - 属性month @06
 - 属性day @15
- 步骤5：周类型测试
 - 属性year @2024
 - 属性week @24

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->parseDateStrTest('2024-06-15')) && p('year:year') && e('2024'); // 步骤1：默认all类型测试，检查年份
r($metricTest->parseDateStrTest('2024-06-15', 'year')) && p('year') && e('2024'); // 步骤2：年份类型测试
r($metricTest->parseDateStrTest('2024-06-15', 'month')) && p('year,month') && e('2024,06'); // 步骤3：月份类型测试
r($metricTest->parseDateStrTest('2024-06-15', 'day')) && p('year,month,day') && e('2024,06,15'); // 步骤4：日期类型测试
r($metricTest->parseDateStrTest('2024-06-15', 'week')) && p('year,week') && e('2024,24'); // 步骤5：周类型测试