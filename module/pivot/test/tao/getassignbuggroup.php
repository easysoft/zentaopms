#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getAssignBugGroup();
timeout=0
cid=0

- 步骤1：正常情况验证返回的用户key @user1,user2,user3

- 步骤2：验证返回用户数量 @3
- 步骤3：验证user1存在 @1
- 步骤4：验证user2存在 @1
- 步骤5：验证user3存在 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备  
$table = zenData('bug');
$table->id->range('1-20');
$table->product->range('1{5},2{7},3{8}');
$table->assignedTo->range('user1{4},user2{5},user3{3},{2},closed{2},user4{4}');
$table->status->range('active{15},resolved{3},closed{2}');
$table->deleted->range('0{18},1{2}');
$table->openedBy->range('admin{10},user1{5},user2{5}');
$table->openedDate->range('20240101000000{5},20240601120000{10},20240901180000{5}');
$table->title->range('Bug Title %02d');
$table->severity->range('1{5},2{7},3{5},4{3}');
$table->resolution->range('{12},fixed{3},bydesign{2},duplicate{3}');
$table->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$pivotTest = new pivotTest();

// 5. 测试步骤 - 至少5个测试步骤
$result = $pivotTest->getAssignBugGroupTest();
r(implode(',', array_keys($result))) && p() && e('user1,user2,user3'); // 步骤1：正常情况验证返回的用户key
r(count($result)) && p() && e('3'); // 步骤2：验证返回用户数量
r(isset($result['user1'])) && p() && e('1'); // 步骤3：验证user1存在
r(isset($result['user2'])) && p() && e('1'); // 步骤4：验证user2存在  
r(isset($result['user3'])) && p() && e('1'); // 步骤5：验证user3存在