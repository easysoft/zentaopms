#!/usr/bin/env php
<?php

/**

title=测试 hostModel::getTreeModules();
timeout=0
cid=16759

- 步骤1：获取根模块树结构，期望第4个元素名称为模块4第3条的name属性 @模块4
- 步骤2：获取模块ID为1的子模块，期望第1个元素名称为子模块1第0条的name属性 @子模块1
- 步骤3：获取包含主机数据的模块树，期望第1个模块名称为模块1第0条的name属性 @模块1
- 步骤4：获取不存在模块ID的树结构，期望返回数组长度为0 @0
- 步骤5：获取模块ID为2的子模块，期望第1个元素名称为子模块3第0条的name属性 @子模块3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$moduleTable = zenData('module');
$moduleTable->id->range('1-10');
$moduleTable->name->range('模块1,模块2,子模块1,子模块2,子模块3,模块3,子模块4,子模块5,子模块6,模块4');
$moduleTable->parent->range('0,0,1,1,2,0,6,6,3,0');
$moduleTable->type->range('host{10}');
$moduleTable->order->range('1-10');
$moduleTable->deleted->range('0{10}');
$moduleTable->gen(10);

$hostTable = zenData('host');
$hostTable->id->range('1-5');
$hostTable->name->range('主机1,主机2,主机3,主机4,主机5');
$hostTable->group->range('1,2,3,1,2');
$hostTable->type->range('normal{5}');
$hostTable->deleted->range('0{5}');
$hostTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$hostTest = new hostModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($hostTest->getTreeModulesTest(0, array())) && p('3:name') && e('模块4'); // 步骤1：获取根模块树结构，期望第4个元素名称为模块4
r($hostTest->getTreeModulesTest(1, array())) && p('0:name') && e('子模块1'); // 步骤2：获取模块ID为1的子模块，期望第1个元素名称为子模块1
r($hostTest->getTreeModulesTest(0, array(1 => array((object)array('id' => 1, 'name' => '主机1')), 2 => array((object)array('id' => 2, 'name' => '主机2'))))) && p('0:name') && e('模块1'); // 步骤3：获取包含主机数据的模块树，期望第1个模块名称为模块1
r($hostTest->getTreeModulesTest(999, array())) && p() && e(0); // 步骤4：获取不存在模块ID的树结构，期望返回数组长度为0
r($hostTest->getTreeModulesTest(2, array())) && p('0:name') && e('子模块3'); // 步骤5：获取模块ID为2的子模块，期望第1个元素名称为子模块3