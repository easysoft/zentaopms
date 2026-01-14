#!/usr/bin/env php
<?php

/**

title=测试 todoTao::fetchRows();
timeout=0
cid=19271

- 执行todoTest模块的fetchRowsTest方法，参数是array 第1条的name属性 @待办任务1
- 执行todoTest模块的fetchRowsTest方法，参数是array 第1条的id属性 @1
- 执行todoTest模块的fetchRowsTest方法，参数是array  @0
- 执行todoTest模块的fetchRowsTest方法，参数是array  @0
- 执行todoTest模块的fetchRowsTest方法，参数是array 第1条的id属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// zendata数据准备
$table = zenData('todo');
$table->id->range('1-10');
$table->account->range('admin,user1,user2');
$table->name->range('待办任务1,待办任务2,待办任务3,待办任务4,待办任务5,待办任务6,待办任务7,待办任务8,待办任务9,待办任务10');
$table->type->range('custom,task,bug,story');
$table->status->range('wait,doing,done');
$table->date->range('2024-01-01:2024-12-31');
$table->begin->range('900,1000,1400,1500');
$table->end->range('1200,1300,1700,1800');
$table->pri->range('1,2,3,4');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

// 用户登录
su('admin');

// 创建测试实例
$todoTest = new todoTaoTest();

// 测试步骤1：正常获取多个待办记录，使用关联数组形式的todoIdList
r($todoTest->fetchRowsTest(array(1 => 'test', 2 => 'test', 3 => 'test'))) && p('1:name') && e('待办任务1');

// 测试步骤2：获取包含键值的待办ID列表，验证返回的记录按ID索引
r($todoTest->fetchRowsTest(array(1 => 'value1', 2 => 'value2'))) && p('1:id') && e('1');

// 测试步骤3：获取不存在的待办ID列表，返回空数组
r($todoTest->fetchRowsTest(array(999 => 'test', 1000 => 'test'))) && p() && e('0');

// 测试步骤4：传入空数组参数，返回空数组
r($todoTest->fetchRowsTest(array())) && p() && e('0');

// 测试步骤5：获取单个待办记录
r($todoTest->fetchRowsTest(array(1 => 'single'))) && p('1:id') && e('1');
