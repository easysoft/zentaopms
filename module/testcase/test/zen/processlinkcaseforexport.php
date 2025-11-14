#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processLinkCaseForExport();
timeout=0
cid=0

- 步骤1：单个关联用例且有映射数据 @1
- 步骤2：多个关联用例且有映射数据 @2
- 步骤3：单个关联用例但无映射数据属性linkCase @999
- 步骤4：多个关联用例混合有无映射数据 @1
- 步骤5：空linkCase属性linkCase @~~

*/

// 1. 导入依赖（路径固定,不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 准备测试数据：relatedCases映射表
$relatedCases = array(
    '1' => 'Case1',
    '2' => 'Case2',
    '3' => 'Case3'
);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result1 = $testcaseTest->processLinkCaseForExportTest((object)array('linkCase' => '1'), $relatedCases);
r(strpos($result1->linkCase, 'Case1') !== false && strpos($result1->linkCase, '#1') !== false) && p() && e('1'); // 步骤1：单个关联用例且有映射数据
$result2 = $testcaseTest->processLinkCaseForExportTest((object)array('linkCase' => '1,2'), $relatedCases);
r(substr_count($result2->linkCase, '#')) && p() && e('2'); // 步骤2：多个关联用例且有映射数据
r($testcaseTest->processLinkCaseForExportTest((object)array('linkCase' => '999'), array())) && p('linkCase') && e('999'); // 步骤3：单个关联用例但无映射数据
$result4 = $testcaseTest->processLinkCaseForExportTest((object)array('linkCase' => '1,999'), $relatedCases);
r(strpos($result4->linkCase, 'Case1') !== false && strpos($result4->linkCase, '999') !== false) && p() && e('1'); // 步骤4：多个关联用例混合有无映射数据
r($testcaseTest->processLinkCaseForExportTest((object)array('linkCase' => ''), $relatedCases)) && p('linkCase') && e('~~'); // 步骤5：空linkCase