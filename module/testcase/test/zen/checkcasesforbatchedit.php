#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::checkCasesForBatchEdit();
timeout=0
cid=19084

- 执行testcaseTest模块的checkCasesForBatchEditTest方法，参数是$validCases 第0条的title属性 @测试用例1
- 执行testcaseTest模块的checkCasesForBatchEditTest方法，参数是$casesWithEmptyTitle 属性title[0] @『用例名称』不能为空。
- 执行testcaseTest模块的checkCasesForBatchEditTest方法，参数是$casesWithEmptyType 属性type[0] @『用例类型』不能为空。
- 执行testcaseTest模块的checkCasesForBatchEditTest方法，参数是$casesWithMultipleEmpty 
 - 属性title[0] @『用例名称』不能为空。
 - 属性type[0] @『用例类型』不能为空。
- 执行testcaseTest模块的checkCasesForBatchEditTest方法，参数是$casesWithEmptyStage 第0条的title属性 @测试用例1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 所有必填字段都有值
$validCases = array(
    (object)array('id' => 1, 'title' => '测试用例1', 'type' => 'feature'),
    (object)array('id' => 2, 'title' => '测试用例2', 'type' => 'interface')
);
r($testcaseTest->checkCasesForBatchEditTest($validCases)) && p('0:title') && e('测试用例1');

// 步骤2：边界值 - title字段为空
$casesWithEmptyTitle = array(
    (object)array('id' => 1, 'title' => '', 'type' => 'feature'),
    (object)array('id' => 2, 'title' => '测试用例2', 'type' => 'interface')
);
r($testcaseTest->checkCasesForBatchEditTest($casesWithEmptyTitle)) && p('title[0]') && e('『用例名称』不能为空。');

// 步骤3：异常输入 - type字段为空
$casesWithEmptyType = array(
    (object)array('id' => 1, 'title' => '测试用例1', 'type' => ''),
    (object)array('id' => 2, 'title' => '测试用例2', 'type' => 'interface')
);
r($testcaseTest->checkCasesForBatchEditTest($casesWithEmptyType)) && p('type[0]') && e('『用例类型』不能为空。');

// 步骤4：权限验证 - 多个必填字段为空
$casesWithMultipleEmpty = array(
    (object)array('id' => 1, 'title' => '', 'type' => ''),
    (object)array('id' => 2, 'title' => '测试用例2', 'type' => 'interface')
);
r($testcaseTest->checkCasesForBatchEditTest($casesWithMultipleEmpty)) && p('title[0],type[0]') && e('『用例名称』不能为空。,『用例类型』不能为空。');

// 步骤5：业务规则 - 数组类型字段验证（stage字段）
$casesWithEmptyStage = array(
    (object)array('id' => 1, 'title' => '测试用例1', 'type' => 'feature', 'stage' => ''),
);
r($testcaseTest->checkCasesForBatchEditTest($casesWithEmptyStage)) && p('0:title') && e('测试用例1');