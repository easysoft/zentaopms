#!/usr/bin/env php
<?php

/**

title=测试 deptModel::getDeptPairs();
timeout=0
cid=15971

- 步骤1：正常情况获取所有部门数量 @10
- 步骤2：验证返回类型为数组 @1
- 步骤3：验证ID为1的部门名称属性1 @总经理办公室
- 步骤4：验证ID为4的部门名称属性4 @研发部
- 步骤5：验证ID为6的部门名称属性6 @销售部
- 步骤6：空数据库情况 @1
- 步骤7：空数据库数量验证 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('dept');
$table->id->range('1-10');
$table->name->range('总经理办公室,人力资源部,财务部,研发部,测试部,销售部,市场部,客服部,运维部,产品部');
$table->parent->range('0{5},1{5}');
$table->path->range(',1,,1,2,,1,3,,1,4,,1,5,,6,,7,,8,,9,,10,');
$table->grade->range('1{5},2{5}');
$table->order->range('1-10:1');
$table->manager->range('admin,manager1,manager2,manager3,manager4,manager5,manager6,manager7,manager8,manager9');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$deptTest = new deptModelTest();

// 5. 测试步骤执行（包含至少7个测试步骤）
r($deptTest->getDeptPairsTest('count')) && p() && e('10'); // 步骤1：正常情况获取所有部门数量
r($deptTest->getDeptPairsTest('array')) && p() && e('1'); // 步骤2：验证返回类型为数组
r($deptTest->getDeptPairsTest()) && p('1') && e('总经理办公室'); // 步骤3：验证ID为1的部门名称
r($deptTest->getDeptPairsTest()) && p('4') && e('研发部'); // 步骤4：验证ID为4的部门名称
r($deptTest->getDeptPairsTest()) && p('6') && e('销售部'); // 步骤5：验证ID为6的部门名称

// 清空数据表测试边界情况
$table = zenData('dept');
$table->gen(0);

r($deptTest->getDeptPairsTest('empty')) && p() && e('1'); // 步骤6：空数据库情况
r($deptTest->getDeptPairsTest('count')) && p() && e('0'); // 步骤7：空数据库数量验证
