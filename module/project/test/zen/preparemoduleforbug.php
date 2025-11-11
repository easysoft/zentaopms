#!/usr/bin/env php
<?php

/**

title=测试 projectZen::prepareModuleForBug();
timeout=0
cid=0

- 步骤1:正常情况-单产品多模块场景
 - 属性moduleID @0
 - 属性moduleName @所有模块
- 步骤2:边界值-空产品列表场景
 - 属性moduleTree @0
 - 属性moduleID @0
- 步骤3:业务规则-多产品单模块场景
 - 属性moduleID @0
 - 属性moduleName @所有模块
- 步骤4:特殊场景-按搜索类型查询属性moduleID @0
- 步骤5:特殊场景-指定模块ID查询属性moduleID @5

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('module')->loadYaml('module_preparemoduleforbug', false, 2)->gen(20);
zendata('project')->loadYaml('project', false, 2)->gen(10);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$projectTest = new projectzenTest();

// 准备测试数据
$products1 = array((object)array('id' => 1, 'name' => '产品1', 'type' => 'normal'));
$products2 = array();
$products3 = array((object)array('id' => 2, 'name' => '产品2', 'type' => 'normal'), (object)array('id' => 3, 'name' => '产品3', 'type' => 'branch'));
$products4 = array((object)array('id' => 4, 'name' => '产品4', 'type' => 'normal'));
$products5 = array((object)array('id' => 5, 'name' => '产品5', 'type' => 'normal'));

// 5. 强制要求:必须包含至少5个测试步骤
r($projectTest->prepareModuleForBugTest(1, 1, 'all', 0, 'id_desc', 0, '0', $products1)) && p('moduleID,moduleName') && e('0,所有模块'); // 步骤1:正常情况-单产品多模块场景
r($projectTest->prepareModuleForBugTest(0, 1, 'all', 0, 'id_desc', 0, '0', $products2)) && p('moduleTree,moduleID') && e('0,0'); // 步骤2:边界值-空产品列表场景
r($projectTest->prepareModuleForBugTest(2, 2, 'all', 0, 'id_desc', 0, '0', $products3)) && p('moduleID,moduleName') && e('0,所有模块'); // 步骤3:业务规则-多产品单模块场景
r($projectTest->prepareModuleForBugTest(4, 3, 'bysearch', 10, 'id_desc', 0, '0', $products4)) && p('moduleID') && e('0'); // 步骤4:特殊场景-按搜索类型查询
r($projectTest->prepareModuleForBugTest(5, 4, 'byModule', 5, 'id_desc', 0, '0', $products5)) && p('moduleID') && e('5'); // 步骤5:特殊场景-指定模块ID查询