#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignCreateSceneVars();
timeout=0
cid=19063

- 步骤1:正常产品ID,空分支,无模块属性executed @1
- 步骤2:有分支的产品,指定分支属性executed @1
- 步骤3:正常产品,指定模块ID属性executed @1
- 步骤4:分支产品但分支为空属性executed @1
- 步骤5:正常产品,指定大的模块ID属性executed @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备
zendata('product')->loadYaml('assigncreatescenevars/product', false, 2)->gen(10);
zendata('branch')->loadYaml('assigncreatescenevars/branch', false, 2)->gen(10);
zendata('module')->loadYaml('assigncreatescenevars/module', false, 2)->gen(20);
zendata('scene')->loadYaml('assigncreatescenevars/scene', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseTest = new testcaseZenTest();

// 5. 测试步骤
r($testcaseTest->assignCreateSceneVarsTest(1, '', 0)) && p('executed') && e('1'); // 步骤1:正常产品ID,空分支,无模块
r($testcaseTest->assignCreateSceneVarsTest(6, '1', 0)) && p('executed') && e('1'); // 步骤2:有分支的产品,指定分支
r($testcaseTest->assignCreateSceneVarsTest(1, '', 5)) && p('executed') && e('1'); // 步骤3:正常产品,指定模块ID
r($testcaseTest->assignCreateSceneVarsTest(7, '', 0)) && p('executed') && e('1'); // 步骤4:分支产品但分支为空
r($testcaseTest->assignCreateSceneVarsTest(2, '', 10)) && p('executed') && e('1'); // 步骤5:正常产品,指定大的模块ID