#!/usr/bin/env php
<?php

/**

title=测试 projectZen::prepareBranchForBug();
timeout=0
cid=17948

- 步骤1：正常情况-传入多分支产品
 - 属性branchOptionCount @1
 - 属性branchTagOptionCount @1
- 步骤2：边界值-传入空产品列表
 - 属性branchOptionCount @0
 - 属性branchTagOptionCount @0
- 步骤3：业务规则-只返回指定产品的分支
 - 属性branchOptionCount @1
 - 属性branchTagOptionCount @1
- 步骤4：分支状态处理-包含已关闭分支
 - 属性branchOptionCount @1
 - 属性branchTagOptionCount @1
- 步骤5：普通产品处理-不生成分支选项
 - 属性branchOptionCount @0
 - 属性branchTagOptionCount @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('product')->loadYaml('preparebranchforbug/product', false, 2)->gen(10);
zendata('branch')->loadYaml('preparebranchforbug/branch', false, 2)->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectzenTest();

// 准备测试数据
global $tester;
$productModel = $tester->loadModel('product');

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->prepareBranchForBugTest(array((object)array('id' => 3, 'name' => '产品3', 'type' => 'branch'), (object)array('id' => 4, 'name' => '产品4', 'type' => 'branch')), 3)) && p('branchOptionCount,branchTagOptionCount') && e('1,1'); // 步骤1：正常情况-传入多分支产品
r($projectTest->prepareBranchForBugTest(array(), 0)) && p('branchOptionCount,branchTagOptionCount') && e('0,0'); // 步骤2：边界值-传入空产品列表
r($projectTest->prepareBranchForBugTest(array((object)array('id' => 5, 'name' => '产品5', 'type' => 'branch'), (object)array('id' => 6, 'name' => '产品6', 'type' => 'branch')), 5)) && p('branchOptionCount,branchTagOptionCount') && e('1,1'); // 步骤3：业务规则-只返回指定产品的分支
r($projectTest->prepareBranchForBugTest(array((object)array('id' => 7, 'name' => '产品7', 'type' => 'platform')), 7)) && p('branchOptionCount,branchTagOptionCount') && e('1,1'); // 步骤4：分支状态处理-包含已关闭分支
r($projectTest->prepareBranchForBugTest(array((object)array('id' => 1, 'name' => '产品1', 'type' => 'normal'), (object)array('id' => 2, 'name' => '产品2', 'type' => 'normal')), 1)) && p('branchOptionCount,branchTagOptionCount') && e('0,0'); // 步骤5：普通产品处理-不生成分支选项