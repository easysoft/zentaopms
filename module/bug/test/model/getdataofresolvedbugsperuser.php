#!/usr/bin/env php
<?php

/**

title=测试 bugModel::getDataOfResolvedBugsPerUser();
timeout=0
cid=15379

- 测试步骤1：验证admin用户解决的bug数量第admin条的value属性 @8
- 测试步骤2：验证user1用户解决的bug数量第user1条的value属性 @6
- 测试步骤3：验证user2用户解决的bug数量第user2条的value属性 @4
- 测试步骤4：验证tester用户解决的bug数量第tester条的value属性 @2
- 测试步骤5：验证admin用户名称显示正确第admin条的name属性 @admin
- 测试步骤6：验证无已解决bug时返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$bugTable = zenData('bug');
$bugTable->id->range('1-20');
$bugTable->product->range('1-3');
$bugTable->module->range('1-5');
$bugTable->execution->range('101-103');
$bugTable->title->range('Bug测试{20}');
$bugTable->type->range('codeerror,designdefect,others');
$bugTable->severity->range('1-4');
$bugTable->pri->range('1-4');
$bugTable->status->range('resolved{15},closed{5}');
$bugTable->openedBy->range('admin,user1,user2,test');
$bugTable->openedDate->range('`2023-01-01 00:00:00`');
$bugTable->resolvedBy->range('admin{8},user1{6},user2{4},tester{2}');
$bugTable->resolvedDate->range('`2023-02-01 00:00:00`');
$bugTable->closedBy->range('admin,user1,user2,manager');
$bugTable->closedDate->range('`2023-03-01 00:00:00`');
$bugTable->deleted->range('0');
$bugTable->gen(20);

$userTable = zenData('user');
$userTable->account->range('admin,user1,user2,tester,manager');
$userTable->realname->range('admin,user1,user2,tester,manager');
$userTable->deleted->range('0');
$userTable->gen(5);

su('admin');

$bugTest = new bugModelTest();

r($bugTest->getDataOfResolvedBugsPerUserTest()) && p('admin:value') && e('8'); // 测试步骤1：验证admin用户解决的bug数量
r($bugTest->getDataOfResolvedBugsPerUserTest()) && p('user1:value') && e('6'); // 测试步骤2：验证user1用户解决的bug数量
r($bugTest->getDataOfResolvedBugsPerUserTest()) && p('user2:value') && e('4'); // 测试步骤3：验证user2用户解决的bug数量
r($bugTest->getDataOfResolvedBugsPerUserTest()) && p('tester:value') && e('2'); // 测试步骤4：验证tester用户解决的bug数量
r($bugTest->getDataOfResolvedBugsPerUserTest()) && p('admin:name') && e('admin'); // 测试步骤5：验证admin用户名称显示正确
r($bugTest->getDataOfResolvedBugsPerUserTestWithEmptyData()) && p() && e('0'); // 测试步骤6：验证无已解决bug时返回空数组