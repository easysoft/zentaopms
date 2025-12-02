#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

/**

title=测试 todoTao::getListBy();
timeout=0
cid=19276

- 正常获取指定类型和用户的待办列表 @1
- 获取空结果的待办列表 @0
- 按日期范围过滤待办列表 @2
- 按状态过滤待办列表 @2
- 限制返回数量的待办列表 @1

*/

su('admin');

// 准备最基本的测试数据
$table = zenData('todo');
$table->id->range('1-5');
$table->account->range('admin,user1,user2,admin,user1');
$table->date->range('`2025-08-24`,`2025-08-24`,`2025-08-24`,`2025-08-25`,`2025-08-25`');
$table->type->range('custom');
$table->status->range('wait,doing,done,wait,done');
$table->name->range('测试待办1,测试待办2,测试待办3,测试待办4,测试待办5');
$table->assignedTo->range('admin,user1,user2,admin,user1');
$table->vision->range('rnd');
$table->deleted->range('0');
$table->cycle->range('0');
$table->gen(5);

global $tester;
$todo = new todoTest();

r(is_array($todo->getListByTest('today', 'admin', 'all', '2025-08-24', '2025-08-24', 0, 'date_desc'))) && p() && e('1'); // 正常获取指定类型和用户的待办列表
r(count($todo->getListByTest('assignedtoother', 'admin', 'all', '', '', 0, 'date_desc'))) && p() && e('0'); // 获取空结果的待办列表
r(count($todo->getListByTest('today', 'admin', 'all', '2025-08-24', '2025-08-25', 0, 'date_desc'))) && p() && e('2'); // 按日期范围过滤待办列表
r(count($todo->getListByTest('today', 'admin', 'wait', '2025-08-24', '2025-08-25', 0, 'date_desc'))) && p() && e('2'); // 按状态过滤待办列表
r(count($todo->getListByTest('today', 'admin', 'all', '2025-08-24', '2025-08-25', 1, 'date_desc'))) && p() && e('1'); // 限制返回数量的待办列表