#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getGroupCases();
timeout=0
cid=0

- 步骤1：正常情况，按story分组验证rowspan第1条的rowspan属性 @1
- 步骤2：边界值，productID=0 @0
- 步骤3：非story分组时rowspan为0第1条的rowspan属性 @0
- 步骤4：不存在用例数据的产品 @0
- 步骤5：验证caseID正确设置第1条的caseID属性 @1
- 步骤6：验证用例标题第1条的title属性 @这个是测试用例1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('case')->loadYaml('case_getgroupcases', false, 2)->gen(10);
zendata('story')->loadYaml('story_getgroupcases', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->getGroupCasesTest(1, '0', 'story', 'feature', 'all')) && p('1:rowspan') && e('1'); // 步骤1：正常情况，按story分组验证rowspan
r($testcaseTest->getGroupCasesTest(0, '0', 'story', 'feature', 'all')) && p() && e('0'); // 步骤2：边界值，productID=0
r($testcaseTest->getGroupCasesTest(1, '0', 'module', 'feature', 'all')) && p('1:rowspan') && e('0'); // 步骤3：非story分组时rowspan为0
r($testcaseTest->getGroupCasesTest(999, '0', 'story', 'feature', 'all')) && p() && e('0'); // 步骤4：不存在用例数据的产品
r($testcaseTest->getGroupCasesTest(1, '0', 'story', 'feature', 'all')) && p('1:caseID') && e('1'); // 步骤5：验证caseID正确设置
r($testcaseTest->getGroupCasesTest(1, '0', 'story', 'feature', 'all')) && p('1:title') && e('这个是测试用例1'); // 步骤6：验证用例标题