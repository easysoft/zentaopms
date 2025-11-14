#!/usr/bin/env php
<?php

/**

title=测试 docTao::getSpacePairs();
timeout=0
cid=16176

- 步骤1：测试product类型空间数量 @10
- 步骤2：测试空字符串类型返回空数组 @0
- 步骤3：测试不存在的类型返回空数组 @0
- 步骤4：测试custom类型空间数量 @0
- 步骤5：测试project类型空间数量 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('doclib')->loadYaml('doclib_getspacepairs', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($docTest->getSpacePairsTest('product'))) && p() && e('10');     // 步骤1：测试product类型空间数量
r(count($docTest->getSpacePairsTest(''))) && p() && e('0');             // 步骤2：测试空字符串类型返回空数组
r(count($docTest->getSpacePairsTest('nonexistent'))) && p() && e('0');  // 步骤3：测试不存在的类型返回空数组
r(count($docTest->getSpacePairsTest('custom'))) && p() && e('0');       // 步骤4：测试custom类型空间数量
r(count($docTest->getSpacePairsTest('project'))) && p() && e('0');      // 步骤5：测试project类型空间数量