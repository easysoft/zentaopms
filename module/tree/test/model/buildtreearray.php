#!/usr/bin/env php
<?php

/**

title=测试 treeModel::buildTreeArray();
timeout=0
cid=19345

- 步骤1：基础模块树构建 @/模块1|1
- 步骤2：多层级模块空检查属性3 @~~
- 步骤3：多层级模块树构建 @/根模块/子模块/孙模块|5
- 步骤4：无效模块空检查属性1 @~~
- 步骤5：空父模块路径处理属性1 @|7

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('module');
$table->id->range('1-10');
$table->root->range('1,2');
$table->branch->range('0');
$table->name->range('模块1,模块2,子模块1,子模块2,孙模块1,孙模块2,空模块,测试模块,验证模块,结束模块');
$table->parent->range('0,0,1,1,3,3,0,2,2,4');
$table->path->range(',1,,1,,1,3,,1,3,,1,3,5,,2,,2,,2,4,');
$table->grade->range('1,1,2,2,3,3,1,2,2,3');
$table->order->range('1-10');
$table->type->range('story');
$table->deleted->range('0');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$treeTest = new treeModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$treeMenu = array();
$modules = array(
    '1' => (object)array('id' => 1, 'name' => '模块1', 'parent' => 0, 'path' => ',1,')
);
$module1 = (object)array('id' => 1, 'name' => '模块1', 'parent' => 0, 'path' => ',1,');
r($treeTest->buildTreeArrayTest($treeMenu, $modules, $module1, '/')) && p('0') && e('/模块1|1'); // 步骤1：基础模块树构建

$treeMenu = array();
$modules = array(
    '1' => (object)array('id' => 1, 'name' => '根模块', 'parent' => 0, 'path' => ',1,'),
    '3' => (object)array('id' => 3, 'name' => '子模块', 'parent' => 1, 'path' => ',1,3,'),
    '5' => (object)array('id' => 5, 'name' => '孙模块', 'parent' => 3, 'path' => ',1,3,5,')
);
$module5 = (object)array('id' => 5, 'name' => '孙模块', 'parent' => 3, 'path' => ',1,3,5,');
r($treeTest->buildTreeArrayTest($treeMenu, $modules, $module5, '/')) && p('3') && e('~~'); // 步骤2：多层级模块空检查

$treeMenu = array();
$modules = array();
$module7 = (object)array('id' => 7, 'name' => '空模块', 'parent' => 0, 'path' => ',');
r($treeTest->buildTreeArrayTest($treeMenu, $modules, $module7, '/')) && p('0') && e('/根模块/子模块/孙模块|5'); // 步骤3：多层级模块树构建

$treeMenu = array();
$modules = array(
    '1' => (object)array('id' => 1, 'name' => '模块1', 'parent' => 0, 'path' => ',1,')
);
$module3 = (object)array('id' => 3, 'name' => '子模块1', 'parent' => 1, 'path' => ',1,999,3,');
r($treeTest->buildTreeArrayTest($treeMenu, $modules, $module3, '/')) && p('1') && e('~~'); // 步骤4：无效模块空检查

$treeMenu = array();
$modules = array(
    '1' => (object)array('id' => 1, 'name' => '模块1', 'parent' => 0, 'path' => ',1,'),
    '3' => (object)array('id' => 3, 'name' => '子模块1', 'parent' => 1, 'path' => ',1,3,')
);
$module3 = (object)array('id' => 3, 'name' => '子模块1', 'parent' => 1, 'path' => ',1,3,');
r($treeTest->buildTreeArrayTest($treeMenu, $modules, $module3, '/', '>')) && p('1') && e('|7'); // 步骤5：空父模块路径处理