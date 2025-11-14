#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::getGroupList();
timeout=0
cid=16919

- 执行kanban模块的getGroupListTest方法，参数是1  @,1,2,3,1,2

- 执行kanban模块的getGroupListTest方法，参数是2  @,3,1,2

- 执行kanban模块的getGroupListTest方法，参数是3  @,3,1

- 执行kanban模块的getGroupListTest方法，参数是100  @0
- 执行kanban模块的getGroupListObjectsTest方法，参数是1
 - 第1条的id属性 @1
 - 第1条的kanban属性 @1
 - 第1条的region属性 @1
 - 第1条的order属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 准备测试数据
$table = zenData('kanbangroup');
$table->id->range('1-15');
$table->kanban->range('1-3');
$table->region->range('1{5},2{3},3{2},4{1},5{4}');
$table->order->range('1-5,1-3,1-2,1,1-4');
$table->gen(15);

// 用户登录
su('admin');

// 创建测试实例
$kanban = new kanbanTest();

// 测试步骤1：正常获取区域1的分组列表（根据实际数据分布）
r($kanban->getGroupListTest(1)) && p() && e(',1,2,3,1,2');

// 测试步骤2：正常获取区域2的分组列表（根据实际数据分布）
r($kanban->getGroupListTest(2)) && p() && e(',3,1,2');

// 测试步骤3：获取区域3的分组列表（根据实际数据分布）
r($kanban->getGroupListTest(3)) && p() && e(',3,1');

// 测试步骤4：获取不存在区域的分组列表（实际输出为0）
r($kanban->getGroupListTest(100)) && p() && e('0');

// 测试步骤5：验证返回数据结构完整性（检查第一个分组的字段）
r($kanban->getGroupListObjectsTest(1)) && p('1:id,kanban,region,order') && e('1,1,1,1');