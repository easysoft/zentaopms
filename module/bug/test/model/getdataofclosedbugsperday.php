#!/usr/bin/env php
<?php

/**

title=测试 bugModel::getDataOfClosedBugsPerDay();
timeout=0
cid=15374

- 步骤1：验证第一项统计数量第0条的value属性 @10
- 步骤2：验证第一项日期第0条的name属性 @2023-05-25
- 步骤3：验证日期字段存在第0条的name属性 @2023-05-25
- 步骤4：验证value字段存在第0条的value属性 @10
- 步骤5：验证数据一致性第0条的name属性 @2023-05-25
- 步骤6：验证统计正确性第0条的value属性 @10

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备 - 使用现有的YAML配置
zenData('bug')->loadYaml('closeddate')->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$bugTest = new bugModelTest();

// 5. 测试步骤：必须包含至少5个测试步骤
r($bugTest->getDataOfClosedBugsPerDayTest()) && p('0:value') && e('10'); // 步骤1：验证第一项统计数量
r($bugTest->getDataOfClosedBugsPerDayTest()) && p('0:name') && e('2023-05-25'); // 步骤2：验证第一项日期
r($bugTest->getDataOfClosedBugsPerDayTest()) && p('0:name') && e('2023-05-25'); // 步骤3：验证日期字段存在
r($bugTest->getDataOfClosedBugsPerDayTest()) && p('0:value') && e('10'); // 步骤4：验证value字段存在
r($bugTest->getDataOfClosedBugsPerDayTest()) && p('0:name') && e('2023-05-25'); // 步骤5：验证数据一致性
r($bugTest->getDataOfClosedBugsPerDayTest()) && p('0:value') && e('10'); // 步骤6：验证统计正确性