#!/usr/bin/env php
<?php

/**

title=测试 bugModel::getDataOfOpenedBugsPerUser();
timeout=0
cid=15377

- 测试步骤1：多用户不同数量bug统计
 - 第admin条的name属性 @admin
 - 第admin条的value属性 @5
 - 第dev1条的name属性 @dev1
 - 第dev1条的value属性 @3
 - 第test1条的name属性 @test1
 - 第test1条的value属性 @2
 - 第user1条的name属性 @用户1
 - 第user1条的value属性 @1
- 测试步骤2：单用户场景统计
 - 第admin条的name属性 @admin
 - 第admin条的value属性 @8
- 测试步骤3：无数据场景统计 @0
- 测试步骤4：验证相同数量用户都存在
 - 第user1条的value属性 @3
 - 第user2条的value属性 @3
 - 第user3条的value属性 @3
- 测试步骤5：用户名转换验证
 - 第admin条的name属性 @admin
 - 第admin条的value属性 @2
 - 第test条的name属性 @test
 - 第test条的value属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 测试步骤1：多用户不同数量bug统计
$table = zenData('bug');
$table->openedBy->range('admin{5},dev1{3},test1{2},user1{1}');
$table->gen(11);

su('admin');
$bug = new bugTest();
r($bug->getDataOfOpenedBugsPerUserTest()) && p('admin:name,value;dev1:name,value;test1:name,value;user1:name,value') && e('admin,5;dev1,3;test1,2;用户1,1'); // 测试步骤1：多用户不同数量bug统计

// 测试步骤2：单用户场景统计
zenData('bug')->gen(0);
$table = zenData('bug');
$table->openedBy->range('admin{8}');
$table->gen(8);

r($bug->getDataOfOpenedBugsPerUserTest()) && p('admin:name,value') && e('admin,8'); // 测试步骤2：单用户场景统计

// 测试步骤3：无数据场景统计（通过设置reportCondition为不匹配条件）
zenData('bug')->gen(0);
$_SESSION['bugQueryCondition'] = '1=0';
$_SESSION['bugOnlyCondition'] = true;

r($bug->getDataOfOpenedBugsPerUserTest()) && p() && e('0'); // 测试步骤3：无数据场景统计

// 测试步骤4：多用户相同数量排序
unset($_SESSION['bugQueryCondition'], $_SESSION['bugOnlyCondition']);
$table = zenData('bug');
$table->openedBy->range('user1{3},user2{3},user3{3}');
$table->gen(9);

r($bug->getDataOfOpenedBugsPerUserTest()) && p('user1:value;user2:value;user3:value') && e('3;3;3'); // 测试步骤4：验证相同数量用户都存在

// 测试步骤5：用户名转换验证
zenData('bug')->gen(0);
$table = zenData('bug');
$table->openedBy->range('admin{2},test{1}');
$table->gen(3);

r($bug->getDataOfOpenedBugsPerUserTest()) && p('admin:name,value;test:name,value') && e('admin,2;test,1'); // 测试步骤5：用户名转换验证