#!/usr/bin/env php
<?php

/**

title=测试 todoTao::getCycleList();
timeout=0
cid=19274

- 空输入返回0 @0
- 验证返回5条记录 @5
- 无效输入返回0 @0
- 验证按ID倒序排序后第一个key是10 @10
- 验证混合输入只返回2条有效记录 @2

- 获取通过周期待办的生成的待办数据为空的情况，结果为 0 @0
- 获取有周期待办生成的待办数量，结果为 3 @3
- 获取有周期待办生成的待办数据详细信息
 - 第2条的account属性 @admin
 - 第2条的name属性 @周期待办：每月的每天，提前1天生成
 - 第2条的begin属性 @0801
 - 第2条的end属性 @1801

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 准备测试数据
$todoTable = zenData('todo');
$todoTable->loadYaml('todo_getcyclelist', false, 2)->gen(15);

// 模拟用户登录
su('admin');

// 创建测试实例
$todoTest = new todoTaoTest();

// 测试步骤1：测试空的todoList输入
r($todoTest->getCycleListTaoTest(array())) && p() && e('0'); // 空输入返回0

// 测试步骤2：测试包含有效objectID的todoList（objectID存在且type=custom的记录）
$validTodoList = array(1 => 'todo1', 2 => 'todo2', 6 => 'todo6', 8 => 'todo8', 10 => 'todo10');
$result2 = $todoTest->getCycleListTaoTest($validTodoList);
r(count($result2)) && p() && e('5'); // 验证返回5条记录

// 测试步骤3：测试包含无效objectID的todoList（objectID不存在）
$invalidTodoList = array(999 => 'invalid', 1000 => 'invalid2');
r($todoTest->getCycleListTaoTest($invalidTodoList)) && p() && e('0'); // 无效输入返回0

// 测试步骤4：测试不同排序规则的有效输入（id_desc降序排列）
$validTodoList2 = array(1 => 'todo1', 6 => 'todo6', 10 => 'todo10');
$result4 = $todoTest->getCycleListTaoTest($validTodoList2, 'id_desc');
r(key($result4)) && p() && e('10'); // 验证按ID倒序排序后第一个key是10

// 测试步骤5：测试混合有效无效objectID的todoList
$mixedTodoList = array(1 => 'todo1', 999 => 'invalid', 6 => 'todo6', 1000 => 'invalid2');
$result5 = $todoTest->getCycleListTaoTest($mixedTodoList);
r(count($result5)) && p() && e('2'); // 验证混合输入只返回2条有效记录
