#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignForBatchEdit();
timeout=0
cid=0

- 步骤1：正常产品情况属性products @1
- 步骤2：分支产品情况属性branchProduct @0
- 步骤3：多分支情况属性branchProduct @0
- 步骤4：空用例数组属性products @1
- 步骤5：多产品用例属性products @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('product')->loadYaml('product_assignforbatchedit', false, 2)->gen(5);
zendata('branch')->loadYaml('branch_assignforbatchedit', false, 2)->gen(8);
zendata('case')->loadYaml('case_assignforbatchedit', false, 2)->gen(10);
zendata('module')->loadYaml('module_assignforbatchedit', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 准备测试用例数据
$normalCases = array(
    (object)array('id' => 1, 'product' => 1, 'branch' => 0, 'lib' => 0, 'module' => 1821, 'story' => 1),
    (object)array('id' => 2, 'product' => 1, 'branch' => 0, 'lib' => 0, 'module' => 1822, 'story' => 2),
    (object)array('id' => 3, 'product' => 1, 'branch' => 0, 'lib' => 0, 'module' => 1823, 'story' => 0)
);

$branchCases = array(
    (object)array('id' => 4, 'product' => 2, 'branch' => 1, 'lib' => 0, 'module' => 1824, 'story' => 3),
    (object)array('id' => 5, 'product' => 2, 'branch' => 2, 'lib' => 0, 'module' => 1825, 'story' => 4)
);

$multiBranchCases = array(
    (object)array('id' => 6, 'product' => 3, 'branch' => 1, 'lib' => 0, 'module' => 1826, 'story' => 1),
    (object)array('id' => 7, 'product' => 3, 'branch' => 2, 'lib' => 0, 'module' => 1827, 'story' => 2)
);

$emptyCases = array();

$multiProductCases = array(
    (object)array('id' => 8, 'product' => 1, 'branch' => 0, 'lib' => 0, 'module' => 1828, 'story' => 1),
    (object)array('id' => 9, 'product' => 2, 'branch' => 1, 'lib' => 0, 'module' => 1829, 'story' => 2),
    (object)array('id' => 10, 'product' => 3, 'branch' => 0, 'lib' => 0, 'module' => 1830, 'story' => 0)
);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignForBatchEditTest(1, '0', 'case', $normalCases)) && p('products') && e('1'); // 步骤1：正常产品情况
r($testcaseTest->assignForBatchEditTest(2, '1', 'case', $branchCases)) && p('branchProduct') && e('0'); // 步骤2：分支产品情况
r($testcaseTest->assignForBatchEditTest(3, 'all', 'case', $multiBranchCases)) && p('branchProduct') && e('0'); // 步骤3：多分支情况
r($testcaseTest->assignForBatchEditTest(1, '0', 'case', $emptyCases)) && p('products') && e('1'); // 步骤4：空用例数组
r($testcaseTest->assignForBatchEditTest(1, '0', 'case', $multiProductCases)) && p('products') && e('1'); // 步骤5：多产品用例