#!/usr/bin/env php
<?php

/**

title=测试 stakeholderModel::deleteExpect();
timeout=0
cid=18427

- 测试步骤1：删除有效期望记录属性deleted @1
- 测试步骤2：删除无效ID(0)的期望记录 @0
- 测试步骤3：删除不存在ID的期望记录 @0
- 测试步骤4：删除已删除的期望记录(原本deleted=1)属性deleted @1
- 测试步骤5：验证删除操作的数据库完整性属性deleted @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$expectTable = zenData('expect');
$expectTable->id->range('1-10');
$expectTable->userID->range('1-5');
$expectTable->project->range('1-3');
$expectTable->expect->range('期望内容1,期望内容2,期望内容3{3}');
$expectTable->progress->range('进展内容1,进展内容2,进展内容3{3}');
$expectTable->createdBy->range('admin,user1,user2{3}');
$expectTable->createdDate->range('`2023-01-01`,`2023-01-02`,`2023-01-03`{3}');
$expectTable->deleted->range('0{8},1{2}');
$expectTable->gen(10);

zenData('stakeholder')->gen(5);
zenData('user')->gen(10);

su('admin');

$stakeholderTest = new stakeholderModelTest();

r($stakeholderTest->deleteExpectTest(1)) && p('deleted') && e('1'); // 测试步骤1：删除有效期望记录
r($stakeholderTest->deleteExpectTest(0)) && p() && e('0'); // 测试步骤2：删除无效ID(0)的期望记录
r($stakeholderTest->deleteExpectTest(999)) && p() && e('0'); // 测试步骤3：删除不存在ID的期望记录
r($stakeholderTest->deleteExpectTest(9)) && p('deleted') && e('1'); // 测试步骤4：删除已删除的期望记录(原本deleted=1)
r($stakeholderTest->deleteExpectTest(2)) && p('deleted') && e('1'); // 测试步骤5：验证删除操作的数据库完整性