#!/usr/bin/env php
<?php

/**

title=测试 treeZen::printOptionMenuArray();
timeout=0
cid=19395

- 步骤1：空数组 @[]
- 步骤2：单个元素 @[{"text":"Module1","value":1,"keys":"Module1"}]

- 步骤3：多个元素 @[{"text":"Module1","value":1,"keys":"Module1"},{"text":"Module2","value":2,"keys":"Module2"},{"text":"Module3","value":3,"keys":"Module3"}]

- 步骤4：特殊字符 @[{"text":"Module Test","value":1,"keys":"Module Test"},{"text":"Module & Co","value":2,"keys":"Module & Co"}]

- 步骤5：中文字符 @[{"text":"\u6a21\u5757\u4e00","value":1,"keys":"\u6a21\u5757\u4e00"},{"text":"\u6a21\u5757\u4e8c","value":2,"keys":"\u6a21\u5757\u4e8c"}]

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/treezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$treeTest = new treeTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($treeTest->printOptionMenuArrayTest(array())) && p() && e('[]'); // 步骤1：空数组
r($treeTest->printOptionMenuArrayTest(array('1' => 'Module1'))) && p() && e('[{"text":"Module1","value":1,"keys":"Module1"}]'); // 步骤2：单个元素
r($treeTest->printOptionMenuArrayTest(array('1' => 'Module1', '2' => 'Module2', '3' => 'Module3'))) && p() && e('[{"text":"Module1","value":1,"keys":"Module1"},{"text":"Module2","value":2,"keys":"Module2"},{"text":"Module3","value":3,"keys":"Module3"}]'); // 步骤3：多个元素
r($treeTest->printOptionMenuArrayTest(array('1' => 'Module Test', '2' => 'Module & Co'))) && p() && e('[{"text":"Module Test","value":1,"keys":"Module Test"},{"text":"Module & Co","value":2,"keys":"Module & Co"}]'); // 步骤4：特殊字符
r($treeTest->printOptionMenuArrayTest(array('1' => '模块一', '2' => '模块二'))) && p() && e('[{"text":"\u6a21\u5757\u4e00","value":1,"keys":"\u6a21\u5757\u4e00"},{"text":"\u6a21\u5757\u4e8c","value":2,"keys":"\u6a21\u5757\u4e8c"}]'); // 步骤5：中文字符