#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::checkTestcasesForBatchCreate();
timeout=0
cid=19086

- 执行testcaseTest模块的checkTestcasesForBatchCreateTest方法，参数是$validTestcases 第0条的title属性 @正常测试用例1
- 执行testcaseTest模块的checkTestcasesForBatchCreateTest方法，参数是$emptyTestcases  @0
- 执行testcaseTest模块的checkTestcasesForBatchCreateTest方法，参数是$missingTitleTestcases 属性title[0] @『用例名称』不能为空。
- 执行testcaseTest模块的checkTestcasesForBatchCreateTest方法，参数是$missingTypeTestcases2 属性type[0] @『用例类型』不能为空。
- 执行testcaseTest模块的checkTestcasesForBatchCreateTest方法，参数是$validationTestcases 第0条的product属性 @1
- 执行testcaseTest模块的checkTestcasesForBatchCreateTest方法，参数是$mixedTestcases 属性title[1] @『用例名称』不能为空。

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：正常情况，所有必填字段都存在
$validTestcases = array();
$validTestcases[0] = new stdClass();
$validTestcases[0]->product = 1;
$validTestcases[0]->title = '正常测试用例1';
$validTestcases[0]->type = 'feature';

$validTestcases[1] = new stdClass();
$validTestcases[1]->product = 1;
$validTestcases[1]->title = '正常测试用例2';
$validTestcases[1]->type = 'performance';

r($testcaseTest->checkTestcasesForBatchCreateTest($validTestcases)) && p('0:title') && e('正常测试用例1');

// 测试步骤2：边界值，空数组输入
$emptyTestcases = array();
r($testcaseTest->checkTestcasesForBatchCreateTest($emptyTestcases)) && p() && e('0');

// 测试步骤3：无效输入，缺少title必填字段
$missingTitleTestcases = array();
$missingTitleTestcases[0] = new stdClass();
$missingTitleTestcases[0]->product = 1;
$missingTitleTestcases[0]->type = 'feature';
r($testcaseTest->checkTestcasesForBatchCreateTest($missingTitleTestcases)) && p('title[0]') && e('『用例名称』不能为空。');

// 测试步骤4：无效输入，缺少type必填字段
$missingTypeTestcases2 = array();
$missingTypeTestcases2[0] = new stdClass();
$missingTypeTestcases2[0]->product = 1;
$missingTypeTestcases2[0]->title = '缺少type的测试用例';
r($testcaseTest->checkTestcasesForBatchCreateTest($missingTypeTestcases2)) && p('type[0]') && e('『用例类型』不能为空。');

// 测试步骤5：测试字段验证正确性
$validationTestcases = array();
$validationTestcases[0] = new stdClass();
$validationTestcases[0]->product = 1;
$validationTestcases[0]->title = '用于验证的测试用例';
$validationTestcases[0]->type = 'feature';
r($testcaseTest->checkTestcasesForBatchCreateTest($validationTestcases)) && p('0:product') && e('1');

// 测试步骤6：复合场景，多个用例中部分有错误
$mixedTestcases = array();
$mixedTestcases[0] = new stdClass();
$mixedTestcases[0]->product = 1;
$mixedTestcases[0]->title = '正常用例';
$mixedTestcases[0]->type = 'feature';

$mixedTestcases[1] = new stdClass();
$mixedTestcases[1]->product = 1;
$mixedTestcases[1]->type = 'performance'; // 缺少title

r($testcaseTest->checkTestcasesForBatchCreateTest($mixedTestcases)) && p('title[1]') && e('『用例名称』不能为空。');