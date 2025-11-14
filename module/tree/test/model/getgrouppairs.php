#!/usr/bin/env php
<?php

/**

title=测试 treeModel::getGroupPairs();
timeout=0
cid=19368

- 步骤1：正常查询chart类型的二级分组 @0
- 步骤2：查询一级分组数据 @0
- 步骤3：查询不存在的dimensionID @0
- 步骤4：查询story类型的分组数据 @0
- 步骤5：测试无效参数组合 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';

// 2. zendata数据准备
$table = zenData('module');
$table->loadYaml('module_getgrouppairs', false, 2)->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$treeTest = new treeTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($treeTest->getGroupPairsTest(1, 0, 2, 'chart')) && p() && e('0'); // 步骤1：正常查询chart类型的二级分组
r($treeTest->getGroupPairsTest(1, 0, 1, 'chart')) && p() && e('0'); // 步骤2：查询一级分组数据
r($treeTest->getGroupPairsTest(999, 0, 2, 'chart')) && p() && e('0'); // 步骤3：查询不存在的dimensionID
r($treeTest->getGroupPairsTest(2, 0, 2, 'story')) && p() && e('0'); // 步骤4：查询story类型的分组数据
r($treeTest->getGroupPairsTest(0, 0, 3, 'invalid')) && p() && e('0'); // 步骤5：测试无效参数组合