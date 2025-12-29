#!/usr/bin/env php
<?php

/**

title=测试 entryModel::updateCalledTime();
timeout=0
cid=0

- 测试步骤1：正常entry代号更新calledTime属性calledTime @1234567890
- 测试步骤2：边界值时间戳0更新calledTime属性calledTime @0
- 测试步骤3：不存在的entry代号更新 @0
- 测试步骤4：空字符串代号更新 @0
- 测试步骤5：最大时间戳值更新calledTime属性calledTime @4294967295
- 测试步骤6：特殊字符代号更新 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/entry.unittest.class.php';

$table = zenData('entry');
$table->id->range('1-10');
$table->name->range('应用1,应用2,应用3,应用4,应用5,应用6,应用7,应用8,应用9,应用10');
$table->account->range('admin,user,test{3},guest{3},pm{2}');
$table->code->range('code1,code2,code3,code4,code5,code6,code7,code8,code9,code10');
$table->key->range('key1,key2,key3,key4,key5,key6,key7,key8,key9,key10');
$table->freePasswd->range('0');
$table->ip->range('127.0.0.1,192.168.1.{100-110}');
$table->desc->range('描述1,描述2,描述3{5},测试描述{2}');
$table->createdBy->range('admin');
$table->createdDate->range('`2023-01-01 00:00:00`');
$table->editedBy->range('');
$table->deleted->range('0');
$table->gen(10);

zenData('user')->gen(5);
su('admin');

$entryTest = new entryTest();

r($entryTest->updateCalledTimeTest('code1', 1234567890)) && p('calledTime') && e('1234567890'); // 测试步骤1：正常entry代号更新calledTime
r($entryTest->updateCalledTimeTest('code2', 0)) && p('calledTime') && e('0'); // 测试步骤2：边界值时间戳0更新calledTime
r($entryTest->updateCalledTimeTest('nonexistent', 1234567890)) && p() && e('0'); // 测试步骤3：不存在的entry代号更新
r($entryTest->updateCalledTimeTest('', 1234567890)) && p() && e('0'); // 测试步骤4：空字符串代号更新
r($entryTest->updateCalledTimeTest('code4', 4294967295)) && p('calledTime') && e('4294967295'); // 测试步骤5：最大时间戳值更新calledTime
r($entryTest->updateCalledTimeTest('code@#$', 1234567890)) && p() && e('0'); // 测试步骤6：特殊字符代号更新
