#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::checkCasesForShowImport();
timeout=0
cid=0

- 执行testcaseTest模块的checkCasesForShowImportTest方法，参数是$emptyCases  @0
- 执行testcaseTest模块的checkCasesForShowImportTest方法，参数是$validCases 第0条的title属性 @测试用例1
- 执行testcaseTest模块的checkCasesForShowImportTest方法，参数是$invalidStepsCases 属性steps[0] @步骤2不能为空
- 执行testcaseTest模块的checkCasesForShowImportTest方法，参数是$emptyTitleCases 属性title[0] @『用例名称』不能为空。
- 执行testcaseTest模块的checkCasesForShowImportTest方法，参数是$emptyTypeCases 属性type[0] @『用例类型』不能为空。

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 空数组
$emptyCases = array();
r($testcaseTest->checkCasesForShowImportTest($emptyCases)) && p() && e('0');

// 步骤2：正常情况 - 有效测试用例数组
$validCases = array(
    (object)array(
        'title' => '测试用例1',
        'type' => 'feature',
        'steps' => array(1 => '步骤1', 2 => '步骤2'),
        'expects' => array(1 => '期望1', 2 => '期望2')
    )
);
r($testcaseTest->checkCasesForShowImportTest($validCases)) && p('0:title') && e('测试用例1');

// 步骤3：边界值 - 有期望但无对应步骤
$invalidStepsCases = array(
    (object)array(
        'title' => '测试用例2',
        'type' => 'feature',
        'steps' => array(1 => '步骤1'),
        'expects' => array(1 => '期望1', 2 => '期望2')
    )
);
r($testcaseTest->checkCasesForShowImportTest($invalidStepsCases)) && p('steps[0]') && e('步骤2不能为空');

// 步骤4：异常输入 - title字段为空
$emptyTitleCases = array(
    (object)array(
        'title' => '',
        'type' => 'feature',
        'steps' => array(1 => '步骤1'),
        'expects' => array(1 => '期望1')
    )
);
r($testcaseTest->checkCasesForShowImportTest($emptyTitleCases)) && p('title[0]') && e('『用例名称』不能为空。');

// 步骤5：权限验证 - type字段为空
$emptyTypeCases = array(
    (object)array(
        'title' => '测试用例',
        'type' => '',
        'steps' => array(1 => '步骤1'),
        'expects' => array(1 => '期望1')
    )
);
r($testcaseTest->checkCasesForShowImportTest($emptyTypeCases)) && p('type[0]') && e('『用例类型』不能为空。');