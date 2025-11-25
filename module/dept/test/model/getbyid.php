#!/usr/bin/env php
<?php

/**

title=测试 deptModel::getByID();
cid=15968

- 测试步骤1：正常ID查询 >> 期望返回正确的部门信息
- 测试步骤2：不存在的ID查询 >> 期望返回false
- 测试步骤3：负数ID查询 >> 期望返回false
- 测试步骤4：零值ID查询 >> 期望返回false
- 测试步骤5：大数值ID查询 >> 期望返回false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dept.unittest.class.php';

$table = zenData('dept');
$table->id->range('1-5');
$table->name->range('产品部,研发部,测试部,市场部,人事部');
$table->parent->range('0,1,1,0,0');
$table->path->range(',1,,2,,3,,4,,5,');
$table->grade->range('1,2,2,1,1');
$table->order->range('5,10,15,20,25');
$table->manager->range('admin,user1,user2,user3,user4');
$table->gen(5);

su('admin');

$deptTest = new deptTest();

r($deptTest->getByIDTest(1)) && p('name,parent,grade') && e('产品部,0,1');
r($deptTest->getByIDTest(999)) && p() && e('0');
r($deptTest->getByIDTest(-1)) && p() && e('0');
r($deptTest->getByIDTest(0)) && p() && e('0');
r($deptTest->getByIDTest(999999)) && p() && e('0');