#!/usr/bin/env php
<?php

/**

title=测试 bugModel::getDataOfOpenedBugsPerDay();
timeout=0
cid=15376

- 步骤1：验证第一天的日期格式第0条的name属性 @2023-01-01
- 步骤2：验证第一天的bug数量第0条的value属性 @5
- 步骤3：验证第二天的日期格式第1条的name属性 @2023-01-02
- 步骤4：验证第二天的bug数量第1条的value属性 @3
- 步骤5：验证第三天的完整数据
 - 第2条的name属性 @2023-01-03
 - 第2条的value属性 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 1. 准备测试数据
zenData('bug')->loadYaml('getdataofopenedbugsperday/openeddate')->gen(10);

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$bugTest = new bugModelTest();

// 4. 执行测试步骤（强制要求：至少5个测试步骤）
r($bugTest->getDataOfOpenedBugsPerDayTest()) && p('0:name') && e('2023-01-01'); // 步骤1：验证第一天的日期格式
r($bugTest->getDataOfOpenedBugsPerDayTest()) && p('0:value') && e('5'); // 步骤2：验证第一天的bug数量
r($bugTest->getDataOfOpenedBugsPerDayTest()) && p('1:name') && e('2023-01-02'); // 步骤3：验证第二天的日期格式
r($bugTest->getDataOfOpenedBugsPerDayTest()) && p('1:value') && e('3'); // 步骤4：验证第二天的bug数量
r($bugTest->getDataOfOpenedBugsPerDayTest()) && p('2:name,value') && e('2023-01-03,2'); // 步骤5：验证第三天的完整数据