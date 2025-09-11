#!/usr/bin/env php
<?php

/**

title=测试 weeklyModel::getLeft();
timeout=0
cid=0

- 步骤1：正常项目和指定日期 @0.00
- 步骤2：正常项目和当前日期 @0.00
- 步骤3：不存在的项目ID @0.00
- 步骤4：其他项目测试 @0.00
- 步骤5：默认日期参数测试 @0.00

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. zendata数据准备
zenData('project')->gen(5);
zenData('execution')->gen(5);
zenData('task')->gen(10);

// 4. 创建测试实例
$weeklyTest = new weeklyTest();

// 5. 测试步骤（必须包含至少5个测试步骤）
r($weeklyTest->getLeftTest(1, '2023-06-15')) && p() && e('0.00'); // 步骤1：正常项目和指定日期
r($weeklyTest->getLeftTest(1, '')) && p() && e('0.00'); // 步骤2：正常项目和当前日期
r($weeklyTest->getLeftTest(999, '2023-06-15')) && p() && e('0.00'); // 步骤3：不存在的项目ID
r($weeklyTest->getLeftTest(2, '2023-06-15')) && p() && e('0.00'); // 步骤4：其他项目测试
r($weeklyTest->getLeftTest(1)) && p() && e('0.00'); // 步骤5：默认日期参数测试