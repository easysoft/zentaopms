#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignForBatchCreate();
timeout=0
cid=0

- 步骤1:正常产品ID,空分支,无模块,无需求属性executed @1
- 步骤2:正常产品ID,指定分支,无模块,无需求属性executed @1
- 步骤3:正常产品ID,空分支,指定模块,无需求属性executed @1
- 步骤4:正常产品ID,空分支,无模块,指定需求属性executed @1
- 步骤5:正常产品ID,指定分支,指定模块,指定需求属性executed @1
- 步骤6:有分支的产品,指定分支,指定模块属性executed @1
- 步骤7:影子产品,空分支,无模块,无需求属性executed @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备
zendata('product')->loadYaml('assignforbatchcreate/product', false, 2)->gen(10);
zendata('branch')->loadYaml('assignforbatchcreate/branch', false, 2)->gen(5);
zendata('module')->loadYaml('assignforbatchcreate/module', false, 2)->gen(10);
zendata('story')->loadYaml('assignforbatchcreate/story', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseTest = new testcaseZenTest();

// 5. 测试步骤
r($testcaseTest->assignForBatchCreateTest(1, '', 0, 0)) && p('executed') && e('1'); // 步骤1:正常产品ID,空分支,无模块,无需求
r($testcaseTest->assignForBatchCreateTest(6, '1', 0, 0)) && p('executed') && e('1'); // 步骤2:正常产品ID,指定分支,无模块,无需求
r($testcaseTest->assignForBatchCreateTest(1, '', 5, 0)) && p('executed') && e('1'); // 步骤3:正常产品ID,空分支,指定模块,无需求
r($testcaseTest->assignForBatchCreateTest(1, '', 0, 1)) && p('executed') && e('1'); // 步骤4:正常产品ID,空分支,无模块,指定需求
r($testcaseTest->assignForBatchCreateTest(6, '1', 5, 1)) && p('executed') && e('1'); // 步骤5:正常产品ID,指定分支,指定模块,指定需求
r($testcaseTest->assignForBatchCreateTest(6, '1', 5, 0)) && p('executed') && e('1'); // 步骤6:有分支的产品,指定分支,指定模块
r($testcaseTest->assignForBatchCreateTest(10, '', 0, 0)) && p('executed') && e('1'); // 步骤7:影子产品,空分支,无模块,无需求