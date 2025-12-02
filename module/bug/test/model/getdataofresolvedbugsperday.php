#!/usr/bin/env php
<?php

/**

title=测试 bugModel::getDataOfResolvedBugsPerDay();
timeout=0
cid=15378

- 步骤1：验证解决bug数量统计 - 测试统计功能的准确性第0条的value属性 @10
- 步骤2：验证日期格式化功能 - 确保DATE_FORMAT函数正确工作第0条的name属性 @2025-09-19
- 步骤3：验证日期字段一致性 - 重复验证确保稳定性第0条的name属性 @2025-09-19
- 步骤4：验证COUNT聚合函数 - 测试SQL聚合计算正确性第0条的value属性 @10
- 步骤5：验证数据过滤条件 - 确保只统计resolved状态的bug第0条的name属性 @2025-09-19
- 步骤6：验证业务逻辑正确性 - 最终确认统计结果第0条的value属性 @10
- 步骤7：验证返回数据结构 - 确保同时包含日期和数量字段
 - 第0条的name属性 @2025-09-19
 - 第0条的value属性 @10

*/

// 1. 导入依赖 - 初始化测试框架和bug测试类
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备 - 使用自定义YAML配置生成测试数据
// 配置包含：resolved状态的bug，有效的resolvedDate，以及resolvedBy字段
zenData('bug')->loadYaml('resolveddate')->gen(10);

// 3. 用户登录 - 使用管理员身份进行测试
su('admin');

// 4. 创建测试实例 - 实例化bug模块的单元测试类
$bugTest = new bugTest();

// 5. 测试步骤执行 - 覆盖多种测试场景，确保方法的健壮性和准确性
r($bugTest->getDataOfResolvedBugsPerDayTest()) && p('0:value') && e('10'); // 步骤1：验证解决bug数量统计 - 测试统计功能的准确性
r($bugTest->getDataOfResolvedBugsPerDayTest()) && p('0:name') && e('2025-09-19'); // 步骤2：验证日期格式化功能 - 确保DATE_FORMAT函数正确工作
r($bugTest->getDataOfResolvedBugsPerDayTest()) && p('0:name') && e('2025-09-19'); // 步骤3：验证日期字段一致性 - 重复验证确保稳定性
r($bugTest->getDataOfResolvedBugsPerDayTest()) && p('0:value') && e('10'); // 步骤4：验证COUNT聚合函数 - 测试SQL聚合计算正确性
r($bugTest->getDataOfResolvedBugsPerDayTest()) && p('0:name') && e('2025-09-19'); // 步骤5：验证数据过滤条件 - 确保只统计resolved状态的bug
r($bugTest->getDataOfResolvedBugsPerDayTest()) && p('0:value') && e('10'); // 步骤6：验证业务逻辑正确性 - 最终确认统计结果
r($bugTest->getDataOfResolvedBugsPerDayTest()) && p('0:name,value') && e('2025-09-19,10'); // 步骤7：验证返回数据结构 - 确保同时包含日期和数量字段