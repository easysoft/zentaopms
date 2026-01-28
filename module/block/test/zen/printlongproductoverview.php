#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printLongProductOverview();
timeout=0
cid=15266

- 步骤1：无参数调用属性currentYear @2025
- 步骤2：指定有效年份属性currentYear @2023
- 步骤3：无效年份字符串属性currentYear @0
- 步骤4：未来年份属性currentYear @2025
- 步骤5：过去年份属性currentYear @2020

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($blockTest->printLongProductOverviewTest()) && p('currentYear') && e('2025'); // 步骤1：无参数调用
r($blockTest->printLongProductOverviewTest(array('year' => 2023))) && p('currentYear') && e('2023'); // 步骤2：指定有效年份
r($blockTest->printLongProductOverviewTest(array('year' => 'abc'))) && p('currentYear') && e('0'); // 步骤3：无效年份字符串
r($blockTest->printLongProductOverviewTest(array('year' => 2025))) && p('currentYear') && e('2025'); // 步骤4：未来年份
r($blockTest->printLongProductOverviewTest(array('year' => 2020))) && p('currentYear') && e('2020'); // 步骤5：过去年份