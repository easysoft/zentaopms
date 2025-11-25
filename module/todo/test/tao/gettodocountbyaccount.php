#!/usr/bin/env php
<?php

/**

title=测试 todoTao::getTodoCountByAccount();
timeout=0
cid=19278

- 步骤1：admin用户待办数量（包括account、assignedTo、finishedBy） @5
- 步骤2：user1用户待办数量 @7
- 步骤3：user2用户待办数量 @5
- 步骤4：user5用户待办数量 @2
- 步骤5：空用户名（匹配所有条件） @10
- 步骤6：test用户待办数量 @6
- 步骤7：不存在的用户 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

// 2. zendata数据准备
$table = zenData('todo');
$table->id->range('1-15');
$table->account->range('admin{5},user1{3},user2{2},test{3},user5{2}');
$table->assignedTo->range('admin{2},user1{3},user2{2},test{3},user5{2},[]{3}');
$table->finishedBy->range('admin{1},user1{1},user2{1},[]{12}');
$table->cycle->range('0');
$table->deleted->range('0{13},1{2}');
$table->vision->range('rnd');
$table->gen(15);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$todoTest = new todoTest();

// 5. 执行测试步骤（至少5个）
r($todoTest->getTodoCountByAccountTest('admin')) && p() && e('5');      // 步骤1：admin用户待办数量（包括account、assignedTo、finishedBy）
r($todoTest->getTodoCountByAccountTest('user1')) && p() && e('7');      // 步骤2：user1用户待办数量
r($todoTest->getTodoCountByAccountTest('user2')) && p() && e('5');      // 步骤3：user2用户待办数量
r($todoTest->getTodoCountByAccountTest('user5')) && p() && e('2');      // 步骤4：user5用户待办数量
r($todoTest->getTodoCountByAccountTest('')) && p() && e('10');          // 步骤5：空用户名（匹配所有条件）
r($todoTest->getTodoCountByAccountTest('test')) && p() && e('6');       // 步骤6：test用户待办数量
r($todoTest->getTodoCountByAccountTest('nonexist')) && p() && e('0');   // 步骤7：不存在的用户
