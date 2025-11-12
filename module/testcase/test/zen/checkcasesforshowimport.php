#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::checkCasesForShowImport();
timeout=0
cid=0

- 执行testcaseTest模块的checkCasesForShowImportTest方法，参数是$validCases 第0条的title属性 @测试用例1
- 执行testcaseTest模块的checkCasesForShowImportTest方法，参数是$casesWithEmptyTitle 属性title[0] @『用例名称』不能为空。
- 执行testcaseTest模块的checkCasesForShowImportTest方法，参数是$casesWithEmptyType 属性type[0] @『用例类型』不能为空。
- 执行testcaseTest模块的checkCasesForShowImportTest方法，参数是$casesWithExpectButNoStep 属性steps[0] @步骤1不能为空
- 执行testcaseTest模块的checkCasesForShowImportTest方法，参数是$casesWithMultipleErrors
 - 属性title[0] @『用例名称』不能为空。
 - 属性type[0] @『用例类型』不能为空。

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 所有必填字段都有值，步骤与期望对应
$validCases = array(
    (object)array('id' => 1, 'title' => '测试用例1', 'type' => 'feature', 'steps' => array(1 => '步骤1', 2 => '步骤2'), 'expects' => array(1 => '期望1', 2 => '期望2')),
    (object)array('id' => 2, 'title' => '测试用例2', 'type' => 'interface', 'steps' => array(1 => '步骤1'), 'expects' => array(1 => '期望1'))
);
r($testcaseTest->checkCasesForShowImportTest($validCases)) && p('0:title') && e('测试用例1');

// 步骤2：边界值 - title字段为空
$casesWithEmptyTitle = array(
    (object)array('id' => 1, 'title' => '', 'type' => 'feature', 'steps' => array(1 => '步骤1'), 'expects' => array(1 => '期望1')),
    (object)array('id' => 2, 'title' => '测试用例2', 'type' => 'interface', 'steps' => array(1 => '步骤1'), 'expects' => array(1 => '期望1'))
);
r($testcaseTest->checkCasesForShowImportTest($casesWithEmptyTitle)) && p('title[0]') && e('『用例名称』不能为空。');

// 步骤3：异常输入 - type字段为空
$casesWithEmptyType = array(
    (object)array('id' => 1, 'title' => '测试用例1', 'type' => '', 'steps' => array(1 => '步骤1'), 'expects' => array(1 => '期望1')),
    (object)array('id' => 2, 'title' => '测试用例2', 'type' => 'interface', 'steps' => array(1 => '步骤1'), 'expects' => array(1 => '期望1'))
);
r($testcaseTest->checkCasesForShowImportTest($casesWithEmptyType)) && p('type[0]') && e('『用例类型』不能为空。');

// 步骤4：业务规则验证 - 期望有值但步骤为空
$casesWithExpectButNoStep = array(
    (object)array('id' => 1, 'title' => '测试用例1', 'type' => 'feature', 'steps' => array(), 'expects' => array(1 => '期望1')),
    (object)array('id' => 2, 'title' => '测试用例2', 'type' => 'interface', 'steps' => array(1 => '步骤1'), 'expects' => array(1 => '期望1'))
);
r($testcaseTest->checkCasesForShowImportTest($casesWithExpectButNoStep)) && p('steps[0]') && e('步骤1不能为空');

// 步骤5：多个错误 - title和type都为空
$casesWithMultipleErrors = array(
    (object)array('id' => 1, 'title' => '', 'type' => '', 'steps' => array(1 => '步骤1'), 'expects' => array(1 => '期望1')),
    (object)array('id' => 2, 'title' => '测试用例2', 'type' => 'interface', 'steps' => array(1 => '步骤1'), 'expects' => array(1 => '期望1'))
);
r($testcaseTest->checkCasesForShowImportTest($casesWithMultipleErrors)) && p('title[0],type[0]') && e('『用例名称』不能为空。,『用例类型』不能为空。');