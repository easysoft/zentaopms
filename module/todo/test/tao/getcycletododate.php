#!/usr/bin/env php
<?php

/**

title=测试 todoTao::getCycleTodoDate();
timeout=0
cid=19275

- 按天类型测试，返回0说明不符合间隔要求 @0
- 按周类型测试，返回1说明符合周配置 @1
- 按月类型测试，返回1说明符合月配置 @1
- 有效配置但返回空结果 @0
- 无效类型应返回empty @0
- 空lastCycle按天类型，不符合间隔返回0 @0
- 过期配置应返回false或empty @0

- 获取类型为按天生成的周期待办的日期，结果1 @1
- 获取类型为按周生成的周期待办的日期，结果1 @1
- 获取类型为按月生成的周期待办的日期，结果1 @1
- 生成周期为按天生成的周期待办的日期，结果0 @0
- 生成周期为空的周期待办的日期，结果0 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

// 准备测试数据 - 使用原来的简单方式
zenData('todo')->loadYaml('getcycletododate')->gen(3);

// 用户登录
su('admin');

// 创建测试实例
$todoTest = new todoTest();

// 测试步骤1：测试按天类型周期待办（使用原来的测试数据ID=1）
r($todoTest->getCycleTodoDateTestSimple('day', 1)) && p() && e('0'); // 按天类型测试，返回0说明不符合间隔要求

// 测试步骤2：测试按周类型周期待办（使用原来的测试数据ID=2）
r($todoTest->getCycleTodoDateTestSimple('week', 2)) && p() && e('1'); // 按周类型测试，返回1说明符合周配置

// 测试步骤3：测试按月类型周期待办（使用原来的测试数据ID=3）
r($todoTest->getCycleTodoDateTestSimple('month', 3)) && p() && e('1'); // 按月类型测试，返回1说明符合月配置

// 测试步骤4：测试有效但结果为空的配置情况
r($todoTest->getCycleTodoDateTestEdgeCase('valid_empty_result')) && p() && e('0'); // 有效配置但返回空结果

// 测试步骤5：测试无效配置类型的情况
r($todoTest->getCycleTodoDateTestEdgeCase('invalid_type')) && p() && e('0'); // 无效类型应返回empty

// 测试步骤6：测试空lastCycle对象的情况（按天类型）
r($todoTest->getCycleTodoDateTestEdgeCase('empty_lastcycle')) && p() && e('0'); // 空lastCycle按天类型，不符合间隔返回0

// 测试步骤7：测试过期配置的情况
r($todoTest->getCycleTodoDateTestEdgeCase('past_config')) && p() && e('0'); // 过期配置应返回false或empty
